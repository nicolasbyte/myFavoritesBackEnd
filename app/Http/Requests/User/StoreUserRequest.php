<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'firstName' => 'required|string|max:255',
            'secondName' => 'nullable|string|max:255',
            'firstLastName' => 'required|string|max:255',
            'secondLastName' => 'nullable|string|max:255',
            'identification' => 'required|string|max:255|unique:users,identification',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'recaptcha_token' => ['required', 'string', new \App\Rules\Recaptcha],
        ];
    }
}
