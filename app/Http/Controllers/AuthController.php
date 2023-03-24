<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function google_redirect(): JsonResponse
    {
        return response()->json([
            'url' => Socialite::driver('google')
                         ->stateless()
                         ->redirect()
                         ->getTargetUrl(),
        ]);
    }

    public function google_callback(): JsonResponse
    {
        try {

            /** @var SocialiteUser $socialiteUser */
            $google_user = Socialite::driver('google')->stateless()->user();

        } catch (ClientException $e) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        /** @var User $user */

        $user = User::query()
            ->firstOrCreate(
                [
                    'email' => $google_user->email,
                ],
                [
                    'email_verified_at' => now(),
                    'name' => $google_user->name,
                    'google_id' => $google_user->id,
                    'avatar' => $google->avatar,
                ]
            );

        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken('google-token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }
}
