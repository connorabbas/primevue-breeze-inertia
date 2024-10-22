<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust this based on your authorization logic
    }

    public function rules()
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'parts' => 'required|array|min:1',
            'parts.*.part_id' => 'required|exists:parts,id',
            'parts.*.quantity_ordered' => 'required|integer|min:1',
            'parts.*.unit_cost' => 'required|numeric|min:0',
            'addresses' => 'required|array',
            'addresses.billTo' => 'required|array',
            'addresses.shipFrom' => 'required|array',
            'addresses.shipTo' => 'required|array',
            'addresses.returnTo' => 'nullable|array',
            'addresses.*.*.street1' => 'required|string',
            'addresses.*.*.street2' => 'nullable|string',
            'addresses.*.*.city' => 'required|string',
            'addresses.*.*.state' => 'required|string',
            'addresses.*.*.postal_code' => 'required|string',
            'addresses.*.*.country' => 'required|string',
            'addresses.*.*.type' => 'nullable|string',
            'addresses.*.*.phone_number' => 'nullable|string',
            'addresses.*.*.email_address' => 'nullable|email',
            'special_instructions' => 'nullable|string',
            'tax_rate' => 'required|numeric|min:0',
            'additional_costs' => 'required|numeric|min:0',
        ];
    }
}
