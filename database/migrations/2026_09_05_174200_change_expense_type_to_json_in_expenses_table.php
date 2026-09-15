<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE expenses
            MODIFY expense_type TEXT NULL
        ");

        DB::table('expenses')
            ->whereNotNull('expense_type')
            ->orderBy('id')
            ->each(function ($expense) {

                $value = trim($expense->expense_type);

                if ($value === '') {
                    DB::table('expenses')
                        ->where('id', $expense->id)
                        ->update([
                            'expense_type' => null,
                        ]);

                    return;
                }

                $decoded = json_decode($value, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return;
                }

                DB::table('expenses')
                    ->where('id', $expense->id)
                    ->update([
                        'expense_type' => json_encode([$value]),
                    ]);
            });

        DB::statement("
            ALTER TABLE expenses
            MODIFY expense_type JSON NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE expenses
            MODIFY expense_type TEXT NULL
        ");

        DB::table('expenses')
            ->whereNotNull('expense_type')
            ->orderBy('id')
            ->each(function ($expense) {

                $decoded = json_decode($expense->expense_type, true);

                $value = is_array($decoded)
                    ? implode(', ', $decoded)
                    : $expense->expense_type;

                DB::table('expenses')
                    ->where('id', $expense->id)
                    ->update([
                        'expense_type' => $value,
                    ]);
            });

        DB::statement("
            ALTER TABLE expenses
            MODIFY expense_type VARCHAR(255) NULL
        ");
    }
};