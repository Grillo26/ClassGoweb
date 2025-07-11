<?php

namespace App\Services;

use App\Jobs\SendNotificationJob;
use App\Models\Code;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
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
            'last_name' => $request['last_name'],
            'phone_number' => $request['phone_number']
        ]);
        $user->assignRole($request['user_role']);
      
        $this->codigos($request, $user); // Generar 5 cupones para el usuario
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
            'last_name' => $request['last_name'],
            'phone_number' => $request['phone_number']
        ]);
        $user->assignRole($request['user_role']);
        // Generar cupones solo si el usuario es estudiante
        $this->codigos($request, $user); // Generar 5 cupones para el usuario
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

    function codigos($request,$user ){
         if ($request['user_role'] == 'student') {
            $cuponservice=new CuponesService(); 
            $cuponservice->codeCoupons($user); // Generar 5 cupones para el usuario
             if (!empty($request['codigo'])) {
                
                
                $cuponservice->codeFriendly(Code::where('codigo', $request['codigo'])->first(), $user); // Buscar el código en la base de datos
                
                $cuponservice->cupomcodigorandom(Code::where('codigo', $request['codigo'])->first(), $user); // Agregar el cupón al usuario 
            }
        }
    } 
}
