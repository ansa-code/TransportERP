<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'vehicle_code' => [
                'required',
                'string',
                'max:255',
                'unique:vehicles,vehicle_code',
            ],

            'vehicle_type' => [
                'required',
                Rule::in([
                    'Heavy Truck',
                    'Small Truck',
                    'Van',
                    'Pickup',
                    'Trailer',
                    'Other',
                ]),
            ],

            'brand' => 'nullable|string|max:100',

            'model' => 'nullable|string|max:100',

            'plate_number' => [
                'required',
                'string',
                'max:255',
                'unique:vehicles,plate_number',
            ],

            'category' => 'nullable|string|max:255',

            'load_capacity' => 'nullable|numeric|min:0',

            'load_capacity_unit' => 'nullable|string|max:50',

            'ownership_type' => [
                'required',
                Rule::in([
                    'Company Owned',
                    'Hired',
                    'Financed',
                    'Other',
                ]),
            ],

            'vendor_owner' => 'nullable|string|max:255',

            'registered_company_name' => 'nullable|string|max:255',

            'bank_instalment_amount' => 'nullable|numeric|min:0',

            'instalment_duration' => 'nullable|integer|min:1',

            'instalment_duration_unit' => 'nullable|string|max:50',

            'registration_expiry' => 'nullable|date',

            'insurance_expiry' => 'nullable|date',

            'current_odometer' => 'nullable|integer|min:0',

            'status' => [
                'required',
                Rule::in([
                    'Active',
                    'Assigned',
                    'Idle',
                    'Maintenance',
                    'Inactive',
                    'Archived',
                ]),
            ],

            'remarks' => 'nullable|string',

        ];
    }
}