<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateNameRequest;
use App\Http\Requests\UpdateUsernameRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\AccountActionRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function updateName(UpdateNameRequest $request): JsonResponse
    {
        $user = Auth::user();
        $user->name = $request->input('name');
        $user->save();

        return (new UserResource($user))
            ->response()
            ->setStatusCode(200);
    }

    public function updateUsername(UpdateUsernameRequest $request): JsonResponse
    {
        $user = Auth::user();
        $user->username = $request->input('username');
        $user->save();

        return (new UserResource($user))
            ->response()
            ->setStatusCode(200);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = Auth::user();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully'
        ], 200);
    }

    public function disableAccount(AccountActionRequest $request): JsonResponse
    {
        $user = Auth::user();
        $user->is_active = false;
        $user->save();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Account deactivated successfully'
        ], 200);
    }

    public function deleteAccount(AccountActionRequest $request): JsonResponse
    {
        $user = Auth::user();
        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'message' => 'Account deleted successfully'
        ], 200);
    }
}
