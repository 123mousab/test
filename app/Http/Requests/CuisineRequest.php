<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CuisineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function cuisineRequest()
    {
        return [
            'name.ar' =>['required'],
        ];
    }

    public function cuisineMessage()
    {
        return [
            'name.ar.required' => __('common.required'),
        ];
    }

}
