<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WilayahRequest extends FormRequest
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
            // 'provinsi'=>['required'],
            // 'kabupaten'=>['required'],
            // 'kecamatan'=>['required'],
            // 'kelurahan'=>['required'],
            'kode_pos'=>['required','numeric',"digits:5"],
            'rw'=>['required','numeric',"digits:3"],
            'rt'=>['nullable','numeric',"digits:3"],
        ];
    }
}
