<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                   => 'required|min:3|max:50|unique:users',
            'email'                  => 'required|email|unique:users',
            'password'               => 'required|min:8',
            'password_confirmation'  => 'required|same:password',
            "register_as" => "required|in:user,company",

            "company" => "array",

            "code" => "nullable"
        ];
    }
}
