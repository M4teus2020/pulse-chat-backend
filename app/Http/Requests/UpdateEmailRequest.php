<?php

namespace App\Http\Requests;

use App\Services\EmailChangeService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmailRequest extends FormRequest
{
    public function __construct(
        private readonly EmailChangeService $emailChangeService
    ) {
    }

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
        $user = auth()->user();
        $cachedCode = $this->emailChangeService->getVerificationCode($user);

        return [
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'current_password'
            ],
            'verification_code' => [
                Rule::excludeIf(! $user->hasVerifiedEmail() === false),
                Rule::in($cachedCode),
                'required',
                'string',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'New email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already in use.',
            'password.required' => 'Current password is required.',
            'password.current_password' => 'Current password is incorrect.',
            'verification_code.required' => 'Verification code is required for users with a verified email.',
            'verification_code.in' => 'The verification code is invalid or has expired.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => $this->input('email') ? strtolower(trim($this->input('email'))) : null
        ]);
    }
}
