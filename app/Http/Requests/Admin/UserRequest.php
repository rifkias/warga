<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
class UserRequest extends FormRequest
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
            'name'=>['required'],
            'email'=>['required','email',Rule::unique('users','email')->ignore($this->id)],
            'password'=>[Rule::requiredIf($this->id ? false : true),'min:6','nullable'],
            'status' => ['required',Rule::in(['active','deactive'])],
            'wilayah_id'=>['required',Rule::exists('wilayah','id')],
            'role_id'=>['required',Rule::exists('roles','id')],
            'picture'=>['max:1024','mimes:png,jpg,jpeg'],
        ];
        return $rules;

    }
}
