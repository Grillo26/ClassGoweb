<?php

namespace App\Livewire\Pages\Common\ProfileSettings;

use App\Models\Country;
use App\Models\Language;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Componente Livewire para la gestión de detalles personales
 * Maneja la carga y actualización de información del perfil de usuario
 */
class PersonalDetails extends Component
{
    use WithFileUploads;

    // Propiedades del formulario
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone_number = '';
    public $gender = 'male';
    public $tagline = '';
    public $description = '';
    public $country = '';
    public $state = '';
    public $city = '';
    public $address = '';
    public $lat = '';
    public $long = '';
    public $native_language = '';
    public $user_languages = [];
    public $selected_languages = [];

    // Add this property to your PersonalDetails class
public $selected_language = '';

    // Archivos
    public $image;
    public $intro_video;
    public $isUploadingImage = false;
    public $isUploadingVideo = false;
    public $imageName = '';
    public $videoName = '';

    // Estados
    public $isLoading = true;
    public $hasStates = false;
    public $activeRoute = false;

    // Configuración
    public $allowImgFileExt = ['jpg', 'png'];
    public $allowVideoFileExt = ['mp4'];
    public $maxImageSize = 3; // MB
    public $maxVideoSize = 20; // MB
    public $enableGooglePlaces = false;

    private ProfileService $profileService;

    /**
     * Inicializa el componente
     */
    public function mount(): void
    {
        try {
            $this->isLoading = true;
            $this->profileService = new ProfileService(Auth::id());
            $this->loadConfiguration();
            $this->loadUserData();
            $this->activeRoute = Route::currentRouteName();
        } catch (\Exception $e) {
            Log::error('Error al cargar datos del perfil: ' . $e->getMessage());
            $this->dispatch('showAlertMessage', type: 'error', message: __('general.error_loading_profile'));
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Carga la configuración del sistema
     */
    private function loadConfiguration(): void
    {
        $this->enableGooglePlaces = setting('_api.enable_google_places') == '1';
        $this->allowImgFileExt = explode(',', setting('_general.allowed_image_extensions') ?? 'jpg,png');
        $this->allowVideoFileExt = explode(',', setting('_general.allowed_video_extensions') ?? 'mp4');
        $this->maxImageSize = (int)(setting('_general.max_image_size') ?? 3);
        $this->maxVideoSize = (int)(setting('_general.max_video_size') ?? 20);
    }

    /**
     * Carga los datos del usuario
     */
    private function loadUserData(): void
    {
        $profile = $this->profileService->getUserProfile();
        $address = $this->profileService->getUserAddress();
        $languages = $this->profileService->getUserLanguages();

        // Carga datos básicos
        $this->first_name = $profile?->first_name ?? '';
        $this->last_name = $profile?->last_name ?? '';
        $this->email = Auth::user()?->email;
        $this->phone_number = $profile?->phone_number ?? '';
        $this->gender = $profile?->gender ?? 'male';
        $this->tagline = $profile?->tagline ?? '';
        $this->description = $profile?->description ?? '';
        $this->image = $profile?->image ?? '';
        $this->intro_video = $profile?->intro_video ?? '';
        $this->native_language = $profile?->native_language ?? '';

        // Carga datos de ubicación
        $this->country = $address?->country_id ?? '';
        $this->state = $address?->state_id ?? '';
        $this->city = $address?->city ?? '';
        $this->address = $address?->address ?? '';
        $this->lat = $address?->lat ?? '';
        $this->long = $address?->long ?? '';

        // Carga idiomas
        if ($languages instanceof \Illuminate\Support\Collection) {
            $this->user_languages = $languages->pluck('id')->toArray();
        } else {
            $this->user_languages = [];
        }
    }

    /**
     * Renderiza la vista
     */
    #[Layout('layouts.app')]
    public function render(Request $request)
    {
        try {
            $states = null;
            $countries = Country::orderBy('name')->get();
            $languages = Language::get(['id', 'name'])->pluck('name', 'id');

            if (!empty($this->country)) {
                $states = $this->profileService->countryStates($this->country);
                $this->hasStates = $states->isNotEmpty();
            }

            return view('livewire.pages.common.profile-settings.personal-details', [
                'countries' => $countries,
                'states' => $states,
                'languages' => $languages
            ]);
        } catch (\Exception $e) {
            Log::error('Error al renderizar vista de perfil: ' . $e->getMessage());
            $this->dispatch('showAlertMessage', type: 'error', message: __('general.error_loading_view'));
            return view('livewire.pages.common.profile-settings.personal-details', [
                'countries' => collect(),
                'states' => collect(),
                'languages' => collect()
            ]);
        }
    }

    /**
     * Actualiza la información del perfil
     */
    public function updateInfo()
    {

         dd($this->first_name,"nombre ",
            $this->last_name,"apellido",
            $this->phone_number,"numero",
            $this->gender,"genero",
            $this->tagline,"tagline",
            $this->description,"descripcion"
            ,$this->native_language,"idioma",
            $this->user_languages,"idiomas"
        );


        try {
            // Validación temporal sin los campos de ubicación
             $this->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone_number' => 'nullable|string|max:20',
                'gender' => 'required|in:male,female,not_specified',
                'native_language' => 'required|string|max:255',
                'user_languages' => 'required|array',
                'description' => 'required|string|max:500',
            ]); 

            // Actualiza datos del perfil
            $profileData = [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'phone_number' => $this->phone_number,
                'gender' => $this->gender,
                'tagline' => $this->tagline,
                'description' => $this->description,
                'native_language' => $this->native_language,
            ];

            // Maneja la imagen si se ha subido una nueva
            if ($this->image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                $profileData['image'] = $this->image->store('profile_images', 'public');
            }

            // Maneja el video si se ha subido uno nuevo
            if ($this->intro_video instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                $profileData['intro_video'] = $this->intro_video->store('profile_videos', 'public');
            }

            // Guarda los datos
            $this->profileService->setUserProfile($profileData);
            
            // Comentamos temporalmente la actualización de la dirección
            /*
            $this->profileService->setUserAddress([
                'country_id' => $this->country,
                'state_id' => $this->state,
                'city' => $this->city,
                'address' => $this->address,
                'lat' => $this->lat,
                'long' => $this->long,
            ]);
            */
            
            $this->profileService->storeUserLanguages($this->user_languages);

            $this->dispatch('showAlertMessage', type: 'success', message: __('general.success_message'));
        } catch (\Exception $e) {
            Log::error('Error al actualizar perfil: ' . $e->getMessage());
            $this->dispatch('showAlertMessage', type: 'error', message: __('general.error_message'));
        }
    }

    /**
     * Maneja la carga de archivos
     */
    public function upload($field, $file)
    {
        try {
            if ($field === 'image') {
                $this->isUploadingImage = true;
                $this->validate([
                    'image' => 'image|max:' . ($this->maxImageSize * 1024) . '|mimes:' . implode(',', $this->allowImgFileExt)
                ]);
                $this->imageName = $file->getClientOriginalName();
            } else if ($field === 'intro_video') {
                $this->isUploadingVideo = true;
                $this->validate([
                    'intro_video' => 'file|max:' . ($this->maxVideoSize * 1024) . '|mimes:' . implode(',', $this->allowVideoFileExt)
                ]);
                $this->videoName = $file->getClientOriginalName();
            }
        } catch (\Exception $e) {
            Log::error('Error al cargar archivo: ' . $e->getMessage());
            $this->dispatch('showAlertMessage', type: 'error', message: __('general.error_uploading_file'));
        } finally {
            $this->isUploadingImage = false;
            $this->isUploadingVideo = false;
        }
    }

    /**
     * Elimina archivos multimedia
     */
    public function removeMedia($type)
    {
        try {
            if ($type === 'image') {
                if ($this->image && !$this->image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                    Storage::disk('public')->delete($this->image);
                }
                $this->image = null;
                $this->imageName = '';
            } else if ($type === 'video') {
                if ($this->intro_video && !$this->intro_video instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                    Storage::disk('public')->delete($this->intro_video);
                }
                $this->intro_video = null;
                $this->videoName = '';
            }
        } catch (\Exception $e) {
            Log::error('Error al eliminar archivo: ' . $e->getMessage());
            $this->dispatch('showAlertMessage', type: 'error', message: __('general.error_removing_file'));
        }
    }

    /**
     * Elimina un idioma de la lista de idiomas seleccionados
     */
    public function removeLanguage($languageName)
    {
        if (($key = array_search($languageName, $this->user_languages)) !== false) {
            unset($this->user_languages[$key]);
            $this->user_languages = array_values($this->user_languages); // Reindexar el array
        }
    }

    /**
     * Maneja la actualización del país
     */
    public function updatedCountry($value)
    {
        $this->state = null;
        $this->hasStates = false;
    }

    /**
     * Busca países por término
     */
    public function searchCountries($term = '')
    {
        return Country::where('name', 'like', '%' . $term . '%')
            ->select('id', 'name as text')
            ->take(20)
            ->get()
            ->toArray();
    }

    public function updatedSelectedLanguages($value)
    {
        if (is_array($value)) {
            foreach ($value as $langId) {
                if (!in_array($langId, $this->user_languages) && $langId != $this->native_language) {
                    $this->user_languages[] = $langId;
                }
            }
        } else {
            if (!in_array($value, $this->user_languages) && $value != $this->native_language) {
                $this->user_languages[] = $value;
            }
        }
        $this->selected_languages = [];
    }
}
