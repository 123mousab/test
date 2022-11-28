<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PeriodRequest extends FormRequest
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
    public function periodRequest()
    {
        return [
            'name.ar' =>['required'],
        ];
    }

    public function periodMessage()
    {
        return [
            'name.ar.required' => __('common.required'),
        ];
    }

}
