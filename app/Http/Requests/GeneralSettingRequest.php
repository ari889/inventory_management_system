<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class GeneralSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'             => 'required|string',
            'address'           => 'required|string',
            'currency_code'     => 'required|string',
            'currency_position' => 'required|string',
            'timezone'          => 'nullable|string',
            'date_format'       => 'nullable|string',
            'invoice_prefix'    => 'required|string',
            'invoice_number'    => 'required|string',
            'logo'              => 'nullable|mimes:png,jpg,jpeg,gif,svg',
            'favicon'           => 'nullable|mimes:png,jpg,jpeg,gif,svg',
        ];
    }
}
