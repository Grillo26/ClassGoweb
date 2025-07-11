<?php

namespace App\Services\interfaces;

use App\Models\Code;
use App\Models\User;

interface IRegisterService
{
    /**
     * Registra un nuevo usuario y devuelve el modelo User.
     * @param array $request
     * @return User
     */
    public function registerUser($request);

    /**
     * funcion que completa el perfil de un usuario social(cuenta Google)
     * @param User $user
     * @param array $request
     * @return User
     */
    public function completeSocialProfile($user, $request);
    /**
     * Envia una notificación de verificación de correo electrónico al usuario.
     * @param User $user
     * @return bool
     */
    public function sendEmailVerificationNotification($user);

    /**
     * funcion que agrega un cupon a los usuarios que se registran 
     * y al usuario que le paso el codigo 
     * @param Code $code
     * @param User $user
     * return  vacio 
     */
    


    /**
     * Envia un enlace de restablecimiento de contraseña al usuario.
     *
     * @param $request
     * @return array
     */
    public function sendPasswordResetLink($request): array;


    /** 
     * Resetea la contraseña del usuario.
     *
     * @param $request
     * @return array
     */
    public function resetPassword($request);

}