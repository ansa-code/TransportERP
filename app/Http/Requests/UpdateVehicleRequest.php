<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vehicleId = $this->route('vehicle');

        if ($vehicleId === null) {
            $vehicleId = $this->route('id');
        }

        if ($vehicleId instanceof \App\Models\Vehicle) {
            $vehicleId = $vehicleId->getKey();
        }

        return [
            'vehicle_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vehicles', 'vehicle_code')->ignore($vehicleId),
            ],

            'vehicle_type' => [
                'required',
                'string',
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
                Rule::unique('vehicles', 'plate_number')->ignore($vehicleId),
            ],

            'category' => ['nullable', 'string', 'max:255'],

            'load_capacity' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999999.99',
                'regex:/^\d{1,10}(\.\d{1,2})?$/',
            ],

            'load_capacity_unit' => [
                'nullable',
                'string',
                Rule::in(['KG', 'TON']),
            ],

            'ownership_type' => [
                'required',
                'string',
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
                'regex:/^\d{1,10}(\.\d{1,2})?$/',
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
                'string',
                Rule::in(['Months', 'Years']),
            ],

            'registration_expiry' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'insurance_expiry' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'current_odometer' => [
                'nullable',
                'integer',
                'min:0',
                'max:18446744073709551615',
            ],

            'status' => [
                'required',
                'string',
                Rule::in([
                    'Active',
                    'Assigned',
                    'Idle',
                    'Maintenance',
                    'Inactive',
                    'Archived',
                ]),
            ],

            'remarks' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_code.required' =>
                'Vehicle ID / Code is required.',
            'vehicle_code.max' =>
                'Vehicle ID / Code cannot exceed 255 characters.',
            'vehicle_code.unique' =>
                'This Vehicle ID / Code already exists. Please enter a different code.',

            'vehicle_type.required' =>
                'Please select a vehicle type.',
            'vehicle_type.in' =>
                'Please select a valid vehicle type.',

            'brand.required' =>
                'Please enter the vehicle brand.',
            'brand.max' =>
                'Brand cannot exceed 100 characters.',
            'model.required' =>
                'Please enter the vehicle model.',
            'model.max' =>
                'Model cannot exceed 100 characters.',

            'plate_number.required' =>
                'Plate number is required.',
            'plate_number.max' =>
                'Plate number cannot exceed 255 characters.',
            'plate_number.unique' =>
                'This plate number is already registered. Please enter a different plate number.',

            'category.max' =>
                'Category cannot exceed 255 characters.',

            'load_capacity.numeric' =>
                'Load capacity must be a valid number.',
            'load_capacity.min' =>
                'Load capacity cannot be negative.',
            'load_capacity.max' =>
                'Load capacity cannot exceed 9,999,999,999.99.',
            'load_capacity.regex' =>
                'Load capacity can have up to 10 digits before and 2 digits after the decimal point.',
            'load_capacity_unit.in' =>
                'Please select KG or TON as the load capacity unit.',

            'ownership_type.required' =>
                'Please select an ownership type.',
            'ownership_type.in' =>
                'Please select a valid ownership type.',

            'vendor_owner.required_if' =>
                'Please enter the Vendor / Owner for a hired vehicle.',
            'vendor_owner.max' =>
                'Vendor / Owner cannot exceed 255 characters.',

            'registered_company_name.max' =>
                'Registered Company Name cannot exceed 255 characters.',

            'bank_instalment_amount.required_if' =>
                'Please enter the instalment amount for a financed vehicle.',
            'bank_instalment_amount.numeric' =>
                'Instalment amount must be a valid number.',
            'bank_instalment_amount.min' =>
                'Instalment amount cannot be negative.',
            'bank_instalment_amount.max' =>
                'Instalment amount cannot exceed 9,999,999,999.99.',
            'bank_instalment_amount.regex' =>
                'Instalment amount can have up to 10 digits before and 2 digits after the decimal point.',

            'instalment_duration.required_if' =>
                'Please enter the instalment duration for a financed vehicle.',
            'instalment_duration.integer' =>
                'Instalment duration must be a whole number.',
            'instalment_duration.min' =>
                'Instalment duration must be at least 1.',
            'instalment_duration.max' =>
                'Instalment duration exceeds the allowed maximum.',

            'instalment_duration_unit.required_if' =>
                'Please select Months or Years for the instalment duration.',
            'instalment_duration_unit.in' =>
                'Instalment duration unit must be Months or Years.',

            'registration_expiry.date' =>
                'Please enter a valid registration expiry date.',
            'registration_expiry.after_or_equal' =>
                'Registration expiry date cannot be earlier than today.',
            'insurance_expiry.date' =>
                'Please enter a valid insurance expiry date.',
            'insurance_expiry.after_or_equal' =>
                'Insurance expiry date cannot be earlier than today.',
            'registration_expiry.required' =>
    'Registration expiry date is required.',

'insurance_expiry.required' =>
    'Insurance expiry date is required.',

            'current_odometer.integer' =>
                'Current odometer must be a whole number.',
            'current_odometer.min' =>
                'Current odometer cannot be negative.',
            'current_odometer.max' =>
                'Current odometer exceeds the allowed maximum.',

            'status.required' =>
                'Please select a vehicle status.',
            'status.in' =>
                'Please select a valid vehicle status.',

            'remarks.string' =>
                'Remarks must be valid text.',
            'remarks.max' =>
                'Remarks cannot exceed 255 characters.',
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
            'category' => 'Category',
            'load_capacity' => 'Load Capacity',
            'load_capacity_unit' => 'Load Capacity Unit',
            'ownership_type' => 'Ownership Type',
            'vendor_owner' => 'Vendor / Owner',
            'registered_company_name' => 'Registered Company Name',
            'bank_instalment_amount' => 'Bank Instalment Amount',
            'instalment_duration' => 'Instalment Duration',
            'instalment_duration_unit' => 'Instalment Duration Unit',
            'registration_expiry' => 'Registration Expiry',
            'insurance_expiry' => 'Insurance Expiry',
            'current_odometer' => 'Current Odometer',
            'status' => 'Status',
            'remarks' => 'Remarks',
        ];
    }
}