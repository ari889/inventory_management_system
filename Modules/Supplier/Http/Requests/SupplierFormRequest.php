<?php

namespace Modules\Supplier\Http\Requests;

use App\Http\Requests\FormRequest;

class SupplierFormRequest extends FormRequest
{
    protected $rules = [];
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->rules['name']         = ['required', 'string'];
        $this->rules['company_name'] = ['nullable', 'string'];
        $this->rules['vat_number']   = ['nullable', 'string'];
        $this->rules['phone']        = ['nullable', 'string', 'unique:suppliers,phone'];
        $this->rules['email']        = ['nullable', 'email', 'string', 'unique:suppliers,email'];
        $this->rules['address']      = ['nullable', 'string'];
        $this->rules['city']         = ['nullable', 'string'];
        $this->rules['state']        = ['nullable', 'string'];
        $this->rules['postal_code']  = ['nullable', 'numeric'];
        $this->rules['country']      = ['nullable', 'string'];

        if(request()->update_id){
            $this->rules['phone'][2] = 'unique:suppliers,phone,'.request()->update_id;
            $this->rules['email'][3] = 'unique:suppliers,email,'.request()->update_id;
        }
        return $this->rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
