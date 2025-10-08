<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user')->id;

        return [
            'firstName' => 'sometimes|string|max:255',
            'secondName' => 'nullable|string|max:255',
            'firstLastName' => 'sometimes|string|max:255',
            'secondLastName' => 'nullable|string|max:255',
            'identification' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('users', 'identification')->ignore($userId),
            ],
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => 'sometimes|string|min:8',
        ];
    }
}
