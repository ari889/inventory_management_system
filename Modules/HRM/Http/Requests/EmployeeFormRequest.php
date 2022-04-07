<?php

namespace Modules\HRM\Http\Requests;

use App\Http\Requests\FormRequest;

class EmployeeFormRequest extends FormRequest
{
    protected $rules = [];
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->rules['department_id'] = ['required', 'integer'];
        $this->rules['name']          = ['required', 'string'];
        $this->rules['phone']         = ['required', 'string', 'unique:employees,phone'];
        $this->rules['address']       = ['required', 'string'];
        $this->rules['city']          = ['required', 'string'];
        $this->rules['state']         = ['required', 'string'];
        $this->rules['postal_code']   = ['nullable', 'numeric'];
        $this->rules['country']       = ['required', 'string'];
        $this->rules['image']         = ['nullable', 'image', 'mimes:jpg,png,jpeg'];

        if(request()->update_id){
            $this->rules['phone'][2] = 'unique:employees,phone,'.request()->update_id;
        }
        return $this->rules;
    }

    /**
     * custom message
     */
    public function message(){
        return [
            'department_id:required' => 'The department field is required!',
            'department_id:integer' => 'The department field must be an integer!',
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
