<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateEmailRequest;
use App\Services\EmailChangeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailChangeController extends Controller
{
    public function __construct(
        private readonly EmailChangeService $emailChangeService
    ) {
    }

    public function requestVerificationCode(Request $request): JsonResponse
    {
        try {
            $this->emailChangeService->requestVerificationCode(auth()->user());

            return response()->json([
                'message' => 'Verification code sent to your current email.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send verification code. Please try again later.'
            ], 500);
        }
    }

    public function updateEmail(UpdateEmailRequest $request): JsonResponse
    {
        try {
            $this->emailChangeService->initiateEmailChange(
                auth()->user(),
                $request->validated('email')
            );

            return response()->json([
                'message' => 'Verification email sent to the new email address. Please verify to complete the change.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to process email change. Please try again later.'
            ], 500);
        }
    }
}
