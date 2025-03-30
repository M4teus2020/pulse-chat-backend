<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Services\EmailChangeService;

class EmailVerificationRequest extends \Illuminate\Foundation\Auth\EmailVerificationRequest
{
    public function __construct(
        private readonly EmailChangeService $emailChangeService
    ) {
    }

    protected function prepareForValidation(): void
    {
        $user = User::findOrFail($this->route('id'))->first();

        $this->emailChangeService->completeEmailChange($user);

        $this->setUserResolver(function () use ($user) {
            return $user;
        });
    }
}
