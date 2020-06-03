<?php


namespace App\Http\Controllers;


use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialAuthController extends Controller
{
    public function socialLogin($provider)
    {
        return Socialite::with($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $social = Socialite::driver($provider)->stateless()->user();

        if ($user = $this->checkUserExists($social->getEmail())) {
            return $this->redirectWithToken($user);
        }

        return $this->userFromSocialProvider($social);
    }

    private function redirectWithToken($user)
    {
        $userToken = JWTAuth::fromUser($user);
        $token['token'] = $userToken;
        $token['token_type'] = 'bearer';
        $token['expires_in'] = Auth::factory()->getTTL() * 600;
        $token = urlencode(json_encode($token));

        return redirect(config('services.siteUrl') . '/account/social-login?token=' . $token);
    }

    private function userFromSocialProvider($social)
    {
        $user = new User;
        $fullName = explode(' ', $social->getName());
        $user->first_name = $fullName[0] ?? $social->getNickname();
        $user->last_name = $fullName[1] ?? '';
        $user->userName = $social->getEmail();
        $user->email = $social->getEmail();
        $user->account_type = 3;
        $user->is_verified = 1;
        $user->save();
        return $this->redirectWithToken($user);
    }

    private function checkUserExists($emailOrPhoneNumber)
    {
        return User::where('email', $emailOrPhoneNumber)->orWhere('phone', $emailOrPhoneNumber)->first();
    }

}
