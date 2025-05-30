<?php

namespace App\Http\Resources\TutorDetail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Log para depuración de la ruta del video de introducción
        \Log::info('IntroVideo accessor', [
            'profile_id' => $this->id,
            'intro_video_db' => $this->intro_video,
            'url_generada' => !empty($this->intro_video) ? url(Storage::url($this->intro_video)) : null,
        ]);
        return [
            'id'                    => $this->whenHas('id'),
            'verified_at'           => $this->whenHas('verified_at'),
            'full_name'             => $this?->full_name,
            'slug'                  => $this->whenHas('slug'),
            'image'                 => $this->profile_image,
            'intro_video'           => !empty($this->intro_video) ? url(Storage::url($this->intro_video)) : null,
            'description'           => $this->whenHas('description'),
            'tagline'               => $this->whenHas('tagline'),
        ];
    }
}
