<?php

namespace Modules\Customer\Http\Requests;

use App\Http\Requests\FormRequest;

class CustomerFormRequest extends FormRequest
{
    protected $rules = [];
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->rules['customer_group_id'] = ['required', 'integer'];
        $this->rules['name']              = ['required', 'string'];
        $this->rules['company_name']      = ['nullable', 'string'];
        $this->rules['tax_number']        = ['nullable', 'string'];
        $this->rules['phone']             = ['nullable', 'string', 'unique:customers,phone'];
        $this->rules['email']             = ['nullable', 'email', 'string', 'unique:customers,email'];
        $this->rules['address']           = ['nullable', 'string'];
        $this->rules['city']              = ['nullable', 'string'];
        $this->rules['state']             = ['nullable', 'string'];
        $this->rules['postal_code']       = ['nullable', 'numeric'];
        $this->rules['country']           = ['nullable', 'string'];

        if(request()->update_id){
            $this->rules['phone'][2] = 'unique:customers,phone,'.request()->update_id;
            $this->rules['email'][3] = 'unique:customers,email,'.request()->update_id;
        }
        return $this->rules;
    }

    /**
     * custom message
     */
    public function message(){
        return [
            'customer_group_id:required' => 'The customer group field is required!',
            'customer_group_id:integer' => 'The customer group field must be an integer!',
        ];
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
