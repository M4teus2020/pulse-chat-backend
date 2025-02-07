<?php

namespace App\Http\Requests;

use App\Models\User;

class EmailVerificationRequest extends \Illuminate\Foundation\Auth\EmailVerificationRequest
{
    protected function prepareForValidation(): void
    {
        $user = User::findOrFail($this->route('id'));

        $this->setUserResolver(function () use ($user) {
            return $user;
        });
    }
}
