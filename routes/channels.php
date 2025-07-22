<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Permitir acceso a cualquier usuario al canal privado user.{id}
Broadcast::channel('user.{id}', function () {
    return true;
});
