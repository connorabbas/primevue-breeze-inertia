<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'number' => 'required|string',
            'date' => 'required|date',
            'status' => 'required|string',
            'supplier_id' => 'required|exists:suppliers,id',
            'location_id' => 'nullable|exists:locations,id',
            'parts' => 'required|array|min:1',
            'parts.*.part_id' => 'required|exists:parts,id',
            'parts.*.quantity_ordered' => 'required|integer|min:1',
            'parts.*.unit_cost' => 'required|numeric|min:0',
            'parts.*.total_cost' => 'required|numeric|min:0',
            'parts.*.part_number' => 'required|string',
            'parts.*.description' => 'required|string',
            'parts.*.lead_days' => 'required|integer|min:0',
            'addresses' => 'required|array',
            'addresses.billTo' => 'required|array',
            'addresses.shipFrom' => 'required|array',
            'addresses.shipTo' => 'required|array',
            'addresses.returnTo' => 'nullable|array',
            'special_instructions' => 'nullable|string',
            'tax_rate' => 'required|numeric|min:0',
            'additional_costs' => 'required|numeric|min:0',
            'total_cost' => 'required|numeric|min:0',
        ];
    }

    protected function prepareForValidation()
    {
        // Log the incoming request data
        Log::info('Preparing request data for validation:', $this->all());
    }
}
