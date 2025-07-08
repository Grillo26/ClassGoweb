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
use App\Models\Coupon;
use App\Models\UserCoupon;

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

        if($request['user_role'] == 'student') {
            if (!empty($request['codigo'])) {  
            $code = Code::where('codigo', $request['codigo'])->first(); // Buscar el código en la base de datos
             $this->codeFriendly($code);
        }
        }
        



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
        if (!empty($request['codigo'])) {
            $code = Code::where('codigo', $request['codigo'])->first(); // Buscar el código en la base de datos
            $this->codeFriendly($code);
        }
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


    function codeFriendly($code)
    {
         if ($code) {
                // Asignar un cupón al usuario basado en el código
                if($code->user_id) { 
                UserCoupon::create([
                    'coupon_id' => Coupon::create([
                        'code_id' => $code->id,
                        'descuento' => 100, // Descuento del 100%
                        'fecha_caducidad' => now()->addMonth()->endOfMonth(), // Vence al final del siguiente mes
                        'estado' => 'activo',
                    ])->id,
                    'user_id' => $code->user_id,
                    'cantidad' => 1,
                ]);
                }
            }
    }

}
