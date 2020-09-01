<?php

namespace App\Http\Requests;

use App\Utilities\Validation\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    use FailedValidation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user('api')->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:categories',
            'description' => 'required'
        ];
    }
}
