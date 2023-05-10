<?php

namespace App\Http\Requests\MasterWilayah;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VillageRequest extends FormRequest
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
            'district_id'=>['required'],
            'village_name' => ['required','max:255',Rule::unique('villages')],
        ];
    }
}
