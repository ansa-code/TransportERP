<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'vehicle_number' => 'required',

            'plate_number' => 'required|unique:vehicles',

            'vehicle_type' => 'required',

            'brand' => 'required',

            'model' => 'required',

            'manufacture_year' => 'required',

            'capacity' => 'required|numeric',

            'status' => 'required',

            'insurance_expiry' => 'required',

            'registration_expiry' => 'required',

        ];
    }
}