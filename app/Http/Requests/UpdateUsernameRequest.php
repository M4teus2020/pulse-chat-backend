<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsernameRequest extends FormRequest
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
            'username' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'alpha_dash',
                Rule::unique('users')->ignore(auth()->id())
            ],
            'password' => ['required', 'current_password']
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'username.min' => 'Username must be at least 3 characters long.',
            'username.max' => 'Username cannot be longer than 255 characters.',
            'username.alpha_dash' => 'Username can only contain letters, numbers, dashes, and underscores.',
            'password.required' => 'Password is required to confirm the action.',
            'password.current_password' => 'The provided password is incorrect.'
        ];
    }
}
