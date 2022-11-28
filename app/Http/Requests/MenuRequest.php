<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
    public function menuRequest()
    {
        return [
            'cooking_date' =>['required'],
        ];
    }

    public function menuMessage()
    {
        return [
            'cooking_date.required' => __('common.required'),
        ];
    }

}
