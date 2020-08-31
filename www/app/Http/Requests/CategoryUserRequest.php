<?php

namespace App\Http\Requests;

use App\Utilities\Validation\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class CategoryUserRequest extends FormRequest
{
    use FailedValidation;

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
            'categories' => 'array|required',
            'categories.*' => 'numeric'
        ];
    }
}
