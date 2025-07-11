<?php

namespace App\Services\interfaces;
use App\Models\Code;
use App\Models\User;

interface ICuponesService{



    function codeFriendly($code, $user);

    /**
     *  funcion que agrega un codigo al usuario que se registra
     *  y 5 cupones desabilitados mientras no se verifique
     */
    function codeCoupons($user);

    /**
     * funcion que agrega un cupon al usuario que ingresa el codigo promocional
     * 
     * 
     * @param  Code $code
     * @param  User $user
     */
    function cupomcodigorandom($code, $user,);






}