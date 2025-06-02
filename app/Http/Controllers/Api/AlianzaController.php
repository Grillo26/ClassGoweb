<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alianza;
use App\Http\Resources\AlianzaResource;

class AlianzaController extends Controller
{
    public function index()
    {
        return AlianzaResource::collection(Alianza::all());
    }
}
