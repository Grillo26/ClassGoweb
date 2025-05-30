<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Log para depuración de la ruta de la imagen
        \Log::info('Ruta generada para imagen de perfil', [
            'profile_id' => $this->id,
            'profile_image' => $this->profile_image,
            'url_generada' => !empty($this->profile_image) ? url('storage/thumbnails/' . str_replace(' ', '%20', $this->profile_image)) : null,
        ]);
        return [
            'id'                    => $this->whenHas('id'),
            'user_id'               => $this->whenHas('user_id'),
            'gender'                => $this->whenHas('gender'),
            'recommend_tutor'       => $this->whenHas('recommend_tutor'),
            'intro_video'           => !empty($this->intro_video) ? url(Storage::url($this->intro_video)) : null,
            'native_language'       => $this->whenHas('native_language'),
            'verified'              => !empty($this->verified_at) ? true : false,
            'phone_number'          => $this->whenHas('phone_number'),
            'feature_expired_at'    => $this->whenHas('feature_expired_at'),
            'first_name'            => $this->whenHas('first_name'),
            'last_name'             => $this->whenHas('last_name'),
            'full_name'             => $this?->full_name,
            'short_name'            => $this?->short_name,
            'slug'                  => $this->whenHas('slug'),
            'image'                 => !empty($this->profile_image) ? url('storage/thumbnails/' . str_replace(' ', '%20', $this->profile_image)) : null,
            'description'           => $this->whenHas('description'),
            'tagline'               => $this->whenHas('tagline'),
            'address'               => $this->whenLoaded('user', function () {
                return new AddressResource($this->user->address);
            }),
        ];
    }
}
