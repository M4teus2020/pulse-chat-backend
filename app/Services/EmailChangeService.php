<?php

namespace App\Services;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailChangeService
{
    private const CACHE_PREFIX = 'email_change';
    private const CODE_EXPIRY_MINUTES = 15;
    private const PENDING_EMAIL_EXPIRY_MINUTES = 60;

    public function requestVerificationCode(User $user): void
    {
        try {
            $verificationCode = Str::random(6);

            Cache::put(
                $this->getVerificationCodeKey($user->id),
                $verificationCode,
                now()->addMinutes(self::CODE_EXPIRY_MINUTES)
            );

            Mail::send(
                'emails.email-change-verification',
                ['code' => $verificationCode],
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Email Change Verification Code');
                }
            );

            Log::info('Email change verification code sent', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send verification code', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function initiateEmailChange(User $user, string $newEmail): void
    {
        try {
            $this->storePendingEmail($user, $newEmail);
            $user->email = $newEmail;
            $user->sendEmailVerificationNotification();

            Log::info('Email change initiated', [
                'user_id' => $user->id,
                'new_email' => $newEmail
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function completeEmailChange(User $user): void
    {
        try {
            $pendingEmail = $this->getPendingEmail($user);

            if (! $pendingEmail) {
                return;
            }

            $user->email = $pendingEmail;
            $user->save();

            $this->clearPendingEmail($user);

            Log::info('Email change completed successfully', [
                'user_id' => $user->id,
                'new_email' => $pendingEmail
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to complete email change', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function storePendingEmail(User $user, string $newEmail): void
    {
        Cache::put(
            $this->getPendingEmailKey($user->id),
            $newEmail,
            now()->addMinutes(self::PENDING_EMAIL_EXPIRY_MINUTES)
        );
    }

    public function getPendingEmail(User $user): ?string
    {
        return Cache::get($this->getPendingEmailKey($user->id));
    }

    public function clearPendingEmail(User $user): void
    {
        Cache::forget($this->getPendingEmailKey($user->id));
    }

    public function getVerificationCode(User $user): ?string
    {
        return Cache::get($this->getVerificationCodeKey($user->id));
    }

    private function getVerificationCodeKey(int $userId): string
    {
        return self::CACHE_PREFIX."_code_{$userId}";
    }

    private function getPendingEmailKey(int $userId): string
    {
        return self::CACHE_PREFIX."_pending_{$userId}";
    }
}
