<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|min:5|max:150",
            "email" => "required|email|unique:users",
            "password" => "required|min:5|max:25",
        ];
    }

    /**
     * Get the validation errors messages that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name',
            'name.min' => 'Name must be at least 5 chars long',
            'name.max' => 'Name must not be more than 150 chars',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'email.max' => 'Email must not be more than 150 chars',
            'email.unique' => 'Email already exists',
            'password.required' => 'Please enter your password',
            'password.min' => 'Password must be at least 5 chars long',
            'password.max' => 'Password must be at most 25 chars long'
        ];
    }
}
