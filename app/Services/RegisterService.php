<?php

namespace App\Services;

use App\Jobs\SendNotificationJob;
use App\Models\Code;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class RegisterService
{
   
    public function registerUser($request): User
    {
        $user = User::create([
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        $user->profile()->create([
            'first_name' => $request['first_name'],
            'last_name'  => $request['last_name'],
            'phone_number' => $request['phone_number']
        ]);


        $user->assignRole($request['user_role']);
         $prefijo = Str::lower(Str::ascii(substr($request['first_name'], 0, 3))); // ej: 'luc'

        // contar cuántos códigos ya existen con ese prefijo
        $count = Code::where('code', 'like', $prefijo . '%')->count();

        // sumar 1 para que sea correlativo
        $numero = str_pad($count + 1, 3, '0', STR_PAD_LEFT); // ej: 001

        

        $codigo = $prefijo . $numero;
        Code::create([
            'code' => $codigo,
            'state' => true,
            'expiration_date' => now()->addDays(30),
            'user_id' => $user->id,
        ]);

      
        $emailData = ['userName' => $user->profile->full_name, 'userEmail' => $user->email, 'key' => $user->getKey()];

        dispatch(new SendNotificationJob('registration', $user, $emailData));
        dispatch(new SendNotificationJob('registration', User::admin(), $emailData));

        $user->token = $user->createToken('learnen')->plainTextToken;

        return $user;
    }

    public function completeSocialProfile($user, $request): User
    {
        $user->profile()->create([
            'first_name' => $request['first_name'],
            'last_name'  => $request['last_name'],
            'phone_number' => $request['phone_number']
        ]);


        $user->assignRole($request['user_role']);
        $emailData = ['userName' => $user->profile->full_name, 'userEmail' => $user->email, 'key' => $user->getKey()];

        dispatch(new SendNotificationJob('welcome', $user, $emailData));
        dispatch(new SendNotificationJob('welcome', User::admin(), $emailData));

        return $user;
    }

    public function sendEmailVerificationNotification($user)
    {
        $emailData = ['userName' => $user->profile->full_name, 'userEmail' => $user->email, 'key' => $user->getKey()];
        dispatch(new SendNotificationJob('emailVerification', $user, $emailData));
        return true;
    }

    public function sendPasswordResetLink($request): array
    {

        $status = Password::sendResetLink(
            $request->only('email')
        );
        if ($status != Password::RESET_LINK_SENT) {
            return [
                'success' => false,
                'message' => $status
            ];
        }
        return [
            'success' => true,
            'message' => $status
        ];
    }

    /**
     * Reset the password for the given user.
     */

    public function resetPassword($request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );
        if ($status != Password::PASSWORD_RESET) {
            return [
                'success' => false,
                'message' => $status
            ];
        }
        return [
            'success' => true,
            'message' => $status
        ];
    }
}
