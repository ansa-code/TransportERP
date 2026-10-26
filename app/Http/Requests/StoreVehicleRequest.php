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

            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],

            'plate_number' => [
                'required',
                'string',
                'max:255',
                'unique:vehicles,plate_number',
            ],

            'category' => ['nullable', 'string', 'max:255'],

            'load_capacity' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],

            'load_capacity_unit' => [
                'nullable',
                Rule::in(['KG', 'TON']),
            ],

            'ownership_type' => [
                'required',
                Rule::in([
                    'Company Owned',
                    'Hired',
                    'Financed',
                    'Other',
                ]),
            ],

            'vendor_owner' => [
                'nullable',
                'required_if:ownership_type,Hired',
                'string',
                'max:255',
            ],

            'registered_company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'bank_instalment_amount' => [
                'nullable',
                'required_if:ownership_type,Financed',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],

            'instalment_duration' => [
                'nullable',
                'required_if:ownership_type,Financed',
                'integer',
                'min:1',
                'max:2147483647',
            ],

            'instalment_duration_unit' => [
                'nullable',
                'required_if:ownership_type,Financed',
                Rule::in(['Months', 'Years']),
            ],
             
            'registration_expiry' => [
    'nullable',
    'date',
    'after_or_equal:today',
],

'insurance_expiry' => [
    'nullable',
    'date',
    'after_or_equal:today',
],

            'current_odometer' => [
                'nullable',
                'integer',
                'min:0',
            ],

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

            'remarks' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_code.required' =>
                'Please enter a Vehicle ID / Code.',
            'vehicle_code.unique' =>
                'This Vehicle ID / Code already exists. Please enter a different code.',

            'vehicle_type.required' =>
                'Please select a vehicle type.',

            'brand.required' =>
                'Please enter the vehicle brand.',
            'model.required' =>
                'Please enter the vehicle model.',

            'plate_number.required' =>
                'Please enter a plate number.',
            'plate_number.unique' =>
                'This plate number is already registered. Please enter a different plate number.',

            'load_capacity.numeric' =>
                'Load capacity must be a valid number.',
            'load_capacity.min' =>
                'Load capacity cannot be negative.',
            'load_capacity.max' =>
                'Load capacity is too large.',

            'ownership_type.required' =>
                'Please select an ownership type.',

            'vendor_owner.required_if' =>
                'Please enter the Vendor / Owner for a hired vehicle.',

            'bank_instalment_amount.required_if' =>
                'Please enter the instalment amount for a financed vehicle.',
            'bank_instalment_amount.numeric' =>
                'Instalment amount must be a valid number.',
            'bank_instalment_amount.min' =>
                'Instalment amount cannot be negative.',
            'bank_instalment_amount.max' =>
                'Instalment amount is too large.',

            'instalment_duration.required_if' =>
                'Please enter the instalment duration for a financed vehicle.',
            'instalment_duration.integer' =>
                'Instalment duration must be a whole number.',
            'instalment_duration.min' =>
                'Instalment duration must be at least 1.',

            'instalment_duration_unit.required_if' =>
                'Please select Months or Years for the instalment duration.',

            'registration_expiry.date' =>
                'Please enter a valid registration expiry date.',
            'insurance_expiry.date' =>
                'Please enter a valid insurance expiry date.',

            'current_odometer.integer' =>
                'Current odometer must be a whole number.',
            'current_odometer.min' =>
                'Current odometer cannot be negative.',

            'registration_expiry.after_or_equal' =>
    'Registration expiry date cannot be earlier than today.',

'insurance_expiry.after_or_equal' =>
    'Insurance expiry date cannot be earlier than today.',
    
        ];
    }

    public function attributes(): array
    {
        return [
            'vehicle_code' => 'Vehicle ID / Code',
            'vehicle_type' => 'Vehicle Type',
            'brand' => 'Brand',
            'model' => 'Model',
            'plate_number' => 'Plate Number',
            'load_capacity' => 'Load Capacity',
            'ownership_type' => 'Ownership Type',
            'vendor_owner' => 'Vendor / Owner',
            'bank_instalment_amount' => 'Bank Instalment Amount',
            'instalment_duration' => 'Instalment Duration',
            'registration_expiry' => 'Registration Expiry',
            'insurance_expiry' => 'Insurance Expiry',
            'current_odometer' => 'Current Odometer',
        ];
    }
}