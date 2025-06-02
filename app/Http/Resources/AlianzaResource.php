<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlianzaResource extends JsonResource
{
    /**
     * Transformar el recurso en un array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'titulo'     => $this->titulo,
            'imagen'     => $this->imagen ? asset('storage/' . $this->imagen) : null,
            'enlace'     => $this->enlace,
            'activo'     => $this->activo,
            'orden'      => $this->orden,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
