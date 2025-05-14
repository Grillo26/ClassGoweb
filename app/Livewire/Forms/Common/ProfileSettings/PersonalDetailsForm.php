<?php

namespace App\Livewire\Forms\Common\ProfileSettings;

use App\Http\Requests\Common\PersonalDetail\PersonalDetailRequest;
use App\Traits\PrepareForValidation;
use Illuminate\Support\Facades\Auth;
use Livewire\Form;
use Illuminate\Support\Facades\Log;

/**
 * Formulario Livewire para la gestión de detalles personales
 * Maneja la validación y procesamiento de datos del perfil
 */
class PersonalDetailsForm extends Form
{
    use PrepareForValidation;

    // Propiedades del formulario
    public string $first_name = ''; // Nombre del usuario
    public string $last_name = ''; // Apellido del usuario
    public string $gender = 'male'; // Género del usuario
    public string $tagline = ''; // Eslogan o frase del perfil
    public string $keywords = ''; // Palabras clave del perfil
    public $lat = ''; // Latitud de ubicación
    public $long = ''; // Longitud de ubicación
    public string $country = ''; // País seleccionado
    public string $city = ''; // Ciudad
    public $state = null; // Estado/Provincia
    public string $address = ''; // Dirección
    public $user_languages = []; // Idiomas del usuario
    public string $description = ''; // Descripción del perfil
    public $image; // Imagen de perfil
    public $intro_video; // Video de introducción
    public string $email = ''; // Correo electrónico
    public string $thumbnail = ''; // Miniatura de imagen
    public $profile; // Datos del perfil
    public $cropImageUrl = ''; // URL de imagen recortada
    public $isBase64 = false; // Indica si la imagen está en base64
    public $imageName = false; // Nombre del archivo de imagen
    public $native_language = ''; // Idioma nativo
    public $countryName = ''; // Nombre del país
    public $phone_number = ''; // Número de teléfono
    public $social_profiles = []; // Perfiles sociales
    private ?PersonalDetailRequest $request = null; // Instancia de la solicitud
    public $isProfileVideoMendatory = true; // Si el video es obligatorio
    public $videoName = null; // Nombre del archivo de video

    /**
     * Inicializa el formulario
     */
    public function boot()
    {
        $this->request = new PersonalDetailRequest();
    }

    /**
     * Carga la información del perfil en el formulario
     * @param mixed $profile Datos del perfil
     */
    public function getInfo($profile)
    {
        $this->first_name = $profile?->first_name ?? '';
        $this->last_name = $profile?->last_name ?? '';
        $this->phone_number = $profile?->phone_number ?? null;
        $this->native_language = $profile?->native_language ?? '';
        $this->gender = $profile?->gender ?? 'male';
        $this->tagline = $profile?->tagline ?? '';
        $this->keywords = $profile?->keywords ?? '';
        $this->description = $profile?->description ?? '';
        $this->image = $profile?->image ?? '';
        $this->intro_video = $profile?->intro_video ?? '';
        $this->email = Auth::user()?->email;
    }

    /**
     * Obtiene las reglas de validación
     * @return array
     */
    public function rules(): array
    {
        return $this->request->rules();
    }

    /**
     * Obtiene los mensajes de validación
     * @return array
     */
    public function messages(): array
    {
        return $this->request->messages();
    }

    /**
     * Valida el formulario
     * @param bool $hasStates Indica si el país tiene estados
     */
    public function validateForm($hasStates)
    {
        $rules = $this->rules();
        $messages = $this->messages();
        if ($hasStates) {
            $rules['state'] = 'required';
        }
        $this->beforeValidation(['user_languages', 'intro_video', 'social_profiles']);
        $this->validate($rules, $messages);     
    }

    /**
     * Actualiza la información del perfil
     * @return array Datos actualizados del perfil
     */
    public function updateProfileInfo()
    {
        $isProfileVideoMendatory = setting('_lernen.profile_video') == 'yes' ? true : false;
        if (!empty($this->image) && !empty($this->isBase64)) {
            $bse64 = explode(',', $this->image);
            $bse64 = trim($bse64[1]);
            if (base64_encode(base64_decode($bse64, true)) === $bse64) {
                $this->image = uploadImage('profile_images', $this->image);
            }
            $this->isBase64 = false;
        }
        $intro_video = $this->intro_video;
        if ($this->intro_video && method_exists($this->intro_video, 'temporaryUrl')) {
            $fileName    = uniqueFileName('public/profile_videos', $this->intro_video->getClientOriginalName());
            $intro_video = $this->intro_video->storeAs('profile_videos', $fileName, 'public');
        } 
        $this->intro_video = $intro_video;

        $data = [
            'first_name'      => sanitizeTextField($this->first_name),
            'last_name'       => sanitizeTextField($this->last_name),
            'phone_number'    => !empty($this->phone_number) ? sanitizeTextField($this->phone_number) : null,
            'native_language' => sanitizeTextField($this->native_language),
            'gender'          => sanitizeTextField($this->gender),
            'image'           => $this->image,
            'intro_video'     => !empty($intro_video) ? str_replace('public/', '', $intro_video) : null,
            'tagline'         => sanitizeTextField($this->tagline),
            'keywords'        => sanitizeTextField($this->keywords),
            'description'     => sanitizeTextField(html_entity_decode($this->description), keep_linebreak: true),
        ];
        
        return $data;
    }

    /**
     * Valida archivos multimedia
     * @param string $type Tipo de archivo (image/video)
     * @return bool
     */
    public function validateMedia($type)
    {
       
        try {
            if ($type == 'image') {
                $this->validate([
                    'image' => 'image|mimes:' . (setting('_general.allowed_image_extensions') ?? 'jpg,png') . '|max:' . ((int) (setting('_general.max_image_size') ?? 5) * 1024)
                ]);
            } else {
                $this->validate([
                    'intro_video' => 'required|mimes:' . (setting('_general.allowed_video_extensions') ?? 'mp4') . '|max:' . ((int) (setting('_general.max_video_size') ?? 20) * 1024)
                ]);
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Error en validación de media: ' . $e->getMessage());
            return false;
        }
    }




    /**
     * Prepara los datos de dirección
     * @return array
     */
    public function userAddress()
    {
        $data = [
            'country_id'    => !empty($this->country) ? $this->country : null,
            'state_id'      => !empty($this->state) ? $this->state : null,
            'city'          => sanitizeTextField($this->city)  ?? null,
            'address'       => sanitizeTextField($this->address) ?? null,
        ];

        if (!empty($this->lat)) {

            $data['lat'] = sanitizeTextField($this->lat);
        }

        if (!empty($this->long)) {

            $data['long'] = sanitizeTextField($this->long);
        }

        return $data;
    }

    /**
     * Prepara los datos de perfiles sociales
     * @return array
     */
    public function socialProfiles()
    {
        $socialProfiles = [];
        if (!empty($this->social_profiles)) {
            foreach ($this->social_profiles as $type => $url) {
                $socialProfiles[] = [
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'url' => $url,
                ];
            }
        }

        return $socialProfiles;
    }

    /**
     * Establece los idiomas del usuario
     * @param array $languages
     */
    public function setUserLanguages($languages)
    {
        $this->user_languages = $languages ?? [];
    }

    /**
     * Establece los datos de dirección
     * @param mixed $address
     */
    public function setUserAddress($address)
    {
        $this->country  = $address?->country_id ?? '';
        $this->city     = $address?->city ?? '';
        $this->state    = $address?->state_id ?? '';
        $this->address  = $address?->address ?? '';
        $this->lat      = $address?->lat ?? '';
        $this->long     = $address?->long ?? '';
    }

    /**
     * Establece los perfiles sociales
     * @param array $socialProfiles
     */
    public function setSocialProfiles(array $socialProfiles)
    {

        $socialPlatforms = setting('_social.platforms');
        if (!empty($socialPlatforms) && is_array($socialPlatforms)) {
            foreach ($socialPlatforms as $platform) {
                $socialProfile = collect($socialProfiles)->firstWhere('type', $platform);
                if (!empty($socialProfile)) {
                    $this->social_profiles[$platform] = $socialProfile['url'];
                }
            }
        }
    }


    
    /**
     * Establece el video de introducción
     * @param mixed $video
     */
    public function setVideo($video)
    {
        if ($video) {
            if (method_exists($video, 'temporaryUrl')) {
                $this->intro_video = $video;
                $this->videoName = $video->getClientOriginalName();
            } else {
                $this->intro_video = $video;
                $this->videoName = basename($video);
            }
        }
    }

    /**
     * Establece la imagen de perfil
     * @param mixed $image
     */
    public function setImage($image)
    {
        if ($image) {
            $this->image = $image;
            $this->isBase64 = false;
            $this->imageName = $image->getClientOriginalName();
        }
    }



    /**
     * Elimina la foto de perfil
     */
    public function removePhoto()
    {
        $this->image = null;
        $this->isBase64 = false;
        $this->imageName = null;
    }

    /**
     * Elimina el video de introducción
     */
    public function removeVideo()
    {
        $this->intro_video = null;
        $this->videoName = null;
    }
}
