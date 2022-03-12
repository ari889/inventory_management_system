<?php

namespace Modules\System\Http\Requests;

use App\Http\Requests\FormRequest;

class CustomerGroupFormRequest extends FormRequest
{
    protected $rules = [];
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->rules['group_name'] = ['required', 'string', 'unique:customer_groups,group_name'];
        $this->rules['percentage'] = ['nullable', 'numeric'];
        if(request()->update_id){
            $this->rules['group_name'][2] = 'unique:customer_groups,group_name,'.request()->update_id;
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
