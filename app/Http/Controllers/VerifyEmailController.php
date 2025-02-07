<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->intended(config('app.frontend_url'));
    }
}
