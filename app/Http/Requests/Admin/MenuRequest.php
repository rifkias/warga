<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MenuRequest extends FormRequest
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
        $rules = [
            'name' => ['required','max:255',Rule::unique('menu')],
            'description' => ['string','nullable'],
            'sort' => ['required','numeric'],
            'link' => ['nullable','max:255'],
            'status' => ['required',Rule::in(['active','deactive'])],
            'menu_group' => ['string','nullable'],
        ];

        if($this->id){
            $rules['name'] = ['required','max:255',Rule::unique('menu')->ignore($this->id)];
        }
        return $rules;
    }
}
