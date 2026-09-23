<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Document;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $documentType = $request->input('document_type');
        $duePeriod = $request->input('due_period');

        $documentsQuery = Document::query()
            ->where('is_current', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('document_type', 'like', "%{$search}%")
                        ->orWhere('document_number', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhereHasMorph(
                            'related',
                            [
                                Vehicle::class,
                                Driver::class,
                                Client::class,
                                Vendor::class,
                            ],
                            function ($relatedQuery, $type) use ($search) {
                                if ($type === Vehicle::class) {
                                    $relatedQuery->where(
                                        'plate_number',
                                        'like',
                                        "%{$search}%"
                                    );
                                } elseif ($type === Driver::class) {
                                    $relatedQuery->where(
                                        'driver_name',
                                        'like',
                                        "%{$search}%"
                                    );
                                } elseif ($type === Client::class) {
                                    $relatedQuery->where(
                                        'client_name',
                                        'like',
                                        "%{$search}%"
                                    );
                                } elseif ($type === Vendor::class) {
                                    $relatedQuery->where(
                                        'vendor_name',
                                        'like',
                                        "%{$search}%"
                                    );
                                }
                            }
                        );
                });
            })
            ->when($documentType, function ($query) use ($documentType) {
                if ($documentType === 'License') {
                    $query->whereIn('document_type', [
                        'License',
                        'Driver License',
                        'Driving License',
                    ]);
                } elseif ($documentType === 'Registration') {
                    $query->whereIn('document_type', [
                        'Registration',
                        'Vehicle Registration',
                        'Truck Registration',
                    ]);
                } elseif ($documentType === 'Insurance') {
                    $query->whereIn('document_type', [
                        'Insurance',
                        'Vehicle Insurance',
                    ]);
                } else {
                    $query->where('document_type', $documentType);
                }
            })
            ->when($duePeriod, function ($query) use ($duePeriod) {
                $today = now()->startOfDay();

                if ($duePeriod === 'overdue') {
                    $query->whereNotNull('expiry_date')
                        ->whereDate('expiry_date', '<', $today);
                } elseif (in_array($duePeriod, ['7', '15', '30'], true)) {
                    $query->whereNotNull('expiry_date')
                        ->whereDate('expiry_date', '>=', $today)
                        ->whereDate(
                            'expiry_date',
                            '<=',
                            $today->copy()->addDays((int) $duePeriod)
                        );
                }
            });

        $documents = $documentsQuery
            ->orderByRaw('expiry_date IS NULL')
            ->orderBy('expiry_date')
            ->orderByDesc('created_at')
            ->get();

        $today = now()->startOfDay();

        $stats = [
            'visa_expiry' => Document::where('document_type', 'Visa')
                ->where('is_current', true)
                ->whereNotNull('expiry_date')
                ->count(),

            'license_expiry' => Document::whereIn('document_type', [
                'License',
                'Driver License',
                'Driving License',
            ])
                ->where('is_current', true)
                ->whereNotNull('expiry_date')
                ->count(),

            'registration_expiry' => Document::whereIn('document_type', [
                'Registration',
                'Vehicle Registration',
                'Truck Registration',
            ])
                ->where('is_current', true)
                ->whereNotNull('expiry_date')
                ->count(),

            'insurance_expiry' => Document::whereIn('document_type', [
                'Insurance',
                'Vehicle Insurance',
            ])
                ->where('is_current', true)
                ->whereNotNull('expiry_date')
                ->count(),

            'expired' => Document::where('is_current', true)
                ->whereNotNull('expiry_date')
                ->whereDate('expiry_date', '<', $today)
                ->count(),

            'expiring_30_days' => Document::where('is_current', true)
                ->whereNotNull('expiry_date')
                ->whereDate('expiry_date', '>=', $today)
                ->whereDate(
                    'expiry_date',
                    '<=',
                    $today->copy()->addDays(30)
                )
                ->count(),
        ];

        return view('documents.index', compact(
            'documents',
            'stats',
            'search',
            'documentType',
            'duePeriod'
        ));
    }

    public function create(): View
    {
        $vehicles = Vehicle::orderBy('plate_number')->get();
        $drivers = Driver::orderBy('driver_name')->get();
        $clients = Client::orderBy('client_name')->get();
        $vendors = Vendor::orderBy('vendor_name')->get();

        return view('documents.create', compact(
            'vehicles',
            'drivers',
            'clients',
            'vendors'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'document_type' => ['required', 'string', 'max:100'],
            'document_number' => ['nullable', 'string', 'max:150'],
            'related_type' => ['required', 'in:vehicle,driver,client,vendor'],
            'related_id' => ['required', 'integer'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,webp,doc,docx',
                'max:10240',
            ],
            'status' => ['required', 'in:active,expired,archived'],
            'notes' => ['nullable', 'string'],
        ]);

        $modelClass = $this->resolveRelatedModel($validated['related_type']);

        abort_unless(
            $modelClass::whereKey($validated['related_id'])->exists(),
            422,
            'Selected related record does not exist.'
        );

        $file = $request->file('file');
        $filePath = $file->store('documents', 'public');

        Document::create([
            'document_type' => $validated['document_type'],
            'document_number' => $validated['document_number'] ?? null,
            'issue_date' => $validated['issue_date'] ?? null,
            'expiry_date' => $validated['expiry_date'] ?? null,
            'related_type' => $modelClass,
            'related_id' => $validated['related_id'],
            'file_path' => $filePath,
            'original_file_name' => $file->getClientOriginalName(),
            'file_mime_type' => $file->getClientMimeType(),
            'status' => $validated['status'],
            'version' => 1,
            'is_current' => true,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document uploaded successfully.');
    }

    public function show(Document $document): View
    {
        $document->load([
            'parentDocument',
            'versions',
            'related',
        ]);

        return view('documents.show', compact('document'));
    }

    /**
     * Display document file.
     */
    public function file(Document $document)
    {
        abort_unless($document->file_path, 404);

        $disk = Storage::disk('public');

        abort_unless(
            $disk->exists($document->file_path),
            404
        );

        return $disk->response(
            $document->file_path,
            $document->original_file_name
        );
    }

    public function edit(Document $document): View
    {
        $vehicles = Vehicle::orderBy('plate_number')->get();
        $drivers = Driver::orderBy('driver_name')->get();
        $clients = Client::orderBy('client_name')->get();
        $vendors = Vendor::orderBy('vendor_name')->get();

        return view('documents.edit', compact(
            'document',
            'vehicles',
            'drivers',
            'clients',
            'vendors'
        ));
    }

    public function update(Request $request, Document $document): RedirectResponse
    {
        $validated = $request->validate([
            'document_type' => ['required', 'string', 'max:100'],
            'document_number' => ['nullable', 'string', 'max:150'],
            'related_type' => ['required', 'in:vehicle,driver,client,vendor'],
            'related_id' => ['required', 'integer'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'status' => ['required', 'in:active,expired,archived'],
            'notes' => ['nullable', 'string'],
        ]);

        $modelClass = $this->resolveRelatedModel($validated['related_type']);

        abort_unless(
            $modelClass::whereKey($validated['related_id'])->exists(),
            422,
            'Selected related record does not exist.'
        );

        $document->update([
            'document_type' => $validated['document_type'],
            'document_number' => $validated['document_number'] ?? null,
            'issue_date' => $validated['issue_date'] ?? null,
            'expiry_date' => $validated['expiry_date'] ?? null,
            'related_type' => $modelClass,
            'related_id' => $validated['related_id'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document updated successfully.');
    }

    public function replace(Request $request, Document $document): RedirectResponse
    {
        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,webp,doc,docx',
                'max:10240',
            ],
            'document_number' => ['nullable', 'string', 'max:150'],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'status' => ['required', 'in:active,expired,archived'],
            'notes' => ['nullable', 'string'],
        ]);

        $file = $request->file('file');
        $filePath = $file->store('documents', 'public');

        DB::transaction(function () use (
            $document,
            $validated,
            $file,
            $filePath
        ) {
            $document->update([
                'is_current' => false,
                'status' => 'archived',
            ]);

            $newVersion = ((int) $document->version) + 1;

            Document::create([
                'document_type' => $document->document_type,
                'document_number' => $validated['document_number']
                    ?? $document->document_number,
                'issue_date' => $validated['issue_date']
                    ?? $document->issue_date,
                'expiry_date' => $validated['expiry_date']
                    ?? $document->expiry_date,
                'related_type' => $document->related_type,
                'related_id' => $document->related_id,
                'file_path' => $filePath,
                'original_file_name' => $file->getClientOriginalName(),
                'file_mime_type' => $file->getClientMimeType(),
                'status' => $validated['status'],
                'parent_document_id' => $document->parent_document_id
                    ?: $document->id,
                'version' => $newVersion,
                'is_current' => true,
                'notes' => $validated['notes'] ?? $document->notes,
            ]);
        });

        return redirect()
            ->route('documents.index')
            ->with(
                'success',
                'Document replaced successfully. Version history has been preserved.'
            );
    }

    public function destroy(Document $document): RedirectResponse
    {
        DB::transaction(function () use ($document) {

            $documentsToDelete = collect([$document])
                ->merge($document->versions)
                ->unique('id');

            foreach ($documentsToDelete as $version) {
                if ($version->file_path) {
                    Storage::disk('public')->delete($version->file_path);
                }
            }

            $document->versions()->delete();

            if ($document->parent_document_id) {
                $document->parentDocument()->delete();
            }

            $document->delete();
        });

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    private function resolveRelatedModel(string $type): string
    {
        return match ($type) {
            'vehicle' => Vehicle::class,
            'driver' => Driver::class,
            'client' => Client::class,
            'vendor' => Vendor::class,
        };
    }
}