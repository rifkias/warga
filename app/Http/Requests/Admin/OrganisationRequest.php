<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OrganisationRequest extends FormRequest
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
            'organisation_type_id'=>['required',Rule::exists('organisation_types','id',$this->organisation_type_id)],
            'wilayah_id'=>['required',Rule::exists('wilayah','id',$this->wilayah_id)],
            'parent_id'=>['nullable',Rule::exists('organisations','id',$this->parent_id)],
        ];
    }
}
