<?php

namespace App\Livewire\Pages\Common\ProfileSettings;

use App\Livewire\Forms\Common\ProfileSettings\PersonalDetailsForm;
use App\Models\Country;
use App\Models\Language;
use App\Models\Subject;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Componente Livewire para la gestión de detalles personales del perfil
 * Maneja la carga y actualización de información personal, incluyendo imágenes y videos
 */
class PersonalDetails extends Component
{
    // Trait para manejar la carga de archivos en Livewire
    use WithFileUploads;

    // Propiedades públicas del componente
    public PersonalDetailsForm $form; // Instancia del formulario de detalles personales
    public $search = ''; // Término de búsqueda para países
    public $image; // Archivo de imagen temporal
    public $isUploading = false; // Estado general de carga
    public $isUploadingImage = false; // Estado de carga de imagen
    public $isUploadingVideo = false; // Estado de carga de video

    // Configuración de archivos permitidos
    public $allowImgFileExt = []; // Extensiones de imagen permitidas
    public $allowVideoFileExt = []; // Extensiones de video permitidas
    public $allowImageSize = ''; // Tamaño máximo de imagen en MB
    public $allowVideoSize = ''; // Tamaño máximo de video en MB
    public $googleApiKey = ''; // Clave API de Google para Places
    public $fileExt = ''; // Extensiones de archivo permitidas
    public $isLoading = true; // Estado de carga inicial
    public $imageFileSize = ''; // Tamaño de archivo de imagen
    public $videoFileSize = ''; // Tamaño de archivo de video
    public $vedioExt = ''; // Extensiones de video permitidas

    // Datos del perfil
    public $languages = []; // Lista de idiomas disponibles
    public $tutorSubjects = []; // Materias del tutor
    public $countries = null; // Lista de países
    public $hasStates = false; // Indica si el país seleccionado tiene estados
    public $introVideo; // Archivo de video de introducción
    public $isProfilePhoneMendatory = true; // Si el teléfono es obligatorio
    public $isProfileVideoMendatory = true; // Si el video es obligatorio
    public $isProfileKeywordsMendatory = true; // Si las palabras clave son obligatorias
    private ?ProfileService $profileService = null; // Servicio de perfil
    public $MAX_PROFILE_CHAR = 500; // Máximo de caracteres para el perfil
    public $activeRoute = false; // Ruta activa actual

    /**
     * Renderiza la vista del componente
     * @param Request $request
     * @return \Illuminate\View\View
     */
    #[Layout('layouts.app')]
    public function render(Request $request)
    {
        // Configuración de Google Places
        $enableGooglePlaces = setting('_api.enable_google_places') ?? '0';
        $states = null;

        // Manejo de búsqueda AJAX para países
        if ($request->ajax() && $request->has('search')) {
            $countries = Country::where('name', 'like', '%' . $request->search . '%')
                ->get()
                ->map(function($country) {
                    return [
                        'id' => $country->id,
                        'text' => $country->name
                    ];
                });
            return response()->json($countries);
        }

        // Carga de estados si hay país seleccionado
        if (!empty($this->form->country)) {
            $states = $this->profileService->countryStates($this->form->country);
            if ($states->isNotEmpty()) {
                $this->hasStates = true;
                $this->dispatch('initSelect2', target: '#country_state', timeOut: 0);
            } else {
                $this->hasStates = false;
            }
        }

        // Carga inicial de países si no hay búsqueda
        if (!$this->countries) {
            $this->countries = Country::orderBy('name')->get();
        }

        return view('livewire.pages.common.profile-settings.personal-details', compact('enableGooglePlaces', 'states'));
    }

    /**
     * Busca países por término
     * @param string $term
     * @return array
     */
    public function searchCountries($term = '')
    {
        return Country::where('name', 'like', '%' . $term . '%')
            ->select('id', 'name as text')
            ->take(20)
            ->get()
            ->toArray();
    }

    /**
     * Inicializa el servicio de perfil
     */
    public function boot()
    {
        $this->profileService = new ProfileService(Auth::user()->id);
    }

    /**
     * Carga los datos iniciales y dispara el evento loadPageJs
     */
    public function loadData()
    {
        $this->isLoading = false;
        $this->dispatch('loadPageJs');
    }

    /**
     * Inicializa el componente con los datos necesarios
     */
    public function mount(): void
    {
        // Carga de configuraciones
        $this->isProfilePhoneMendatory = setting('_lernen.profile_phone_number') == 'yes';
        $this->isProfileVideoMendatory = setting('_lernen.profile_video') == 'yes';
        $this->isProfileKeywordsMendatory = setting('_lernen.profile_keywords') == 'yes';

        // Carga de datos básicos
        $this->languages = Language::get(['id', 'name'])?->pluck('name', 'id')?->toArray();
        $this->tutorSubjects = Subject::get(['id', 'name'])?->pluck('name', 'id')?->toArray();
        $this->countries = Country::get(['id', 'name']);

        // Carga de datos del perfil
        $profile = $this->profileService->getUserProfile();
        $address = $this->profileService->getUserAddress();
        $socialProfiles = $this->profileService->getSocialProfiles();
        $languages = $this->profileService->getUserLanguages();
        $this->activeRoute = Route::currentRouteName();

        // Carga de datos en el formulario
        $this->form->getInfo($profile);
        $this->form->setUserAddress($address);
        $this->form->setSocialProfiles($socialProfiles);
        $this->form->setUserLanguages($languages);
        
        // Configuración de archivos
        $this->configureFileSettings();
        
        // Manejo de errores de sesión
        if (Session::get('error')) {
            $this->dispatch('showAlertMessage', type: 'error', message: Session::get('error'));
        }
    }

    /**
     * Actualiza la información del perfil
     */
    public function updateInfo()
    {
        $form = $this->form;
    
        // Manejo del video de introducción
        if (!empty($this->introVideo)) {
            $this->form->setVideo($this->introVideo);
        }
    
        // Validación del formulario
        $form->validateForm($this->hasStates);

        // Verificación de sitio demo
        if (isDemoSite()) {
            $this->dispatch('showAlertMessage', type: 'error', title: __('general.demosite_res_title'), message: __('general.demosite_res_txt'));
            return;
        }
        
        // Actualización de datos
        $this->introVideo = null;
        $data = $form->updateProfileInfo();
        $address = $form->userAddress();
          
        // Guardado de datos
        $this->profileService->setUserProfile($data);
        $this->profileService->storeUserLanguages($form->user_languages);
        $this->profileService->setUserAddress($address);
        
        // Manejo de perfiles sociales
        $socialsProfiles = $form->socialProfiles();
        if (!empty($socialsProfiles)) {
            $this->profileService->setSocialProfiles($socialsProfiles);
        }
    
        // Notificaciones de éxito
        $this->dispatch('profile-img-updated', image: resizedImage($form->image, 36, 36));
        $this->dispatch('showAlertMessage', type: 'success', title: __('general.success_title'), message: __('general.success_message'));
    }

    /**
     * Maneja la actualización del país en el formulario
     */
    public function updatingForm($value, $key)
    {
        if ($key == 'country') {
            $this->form->state = null;
        }
    }

    /**
     * Maneja la actualización del video de introducción
     */
    public function updatedIntroVideo()
    {
        try {
            Log::info('Iniciando validación de video');
            $this->validate([
                'introVideo' => [
                    'required',
                    'file',
                    'mimes:' . (!empty($this->allowVideoFileExt) ? implode(',', $this->allowVideoFileExt) : 'mp4'),
                    'max:' . (!empty($this->allowVideoSize) ? $this->allowVideoSize : 20) * 1024
                ]
            ]);
            
            $this->form->setVideo($this->introVideo);
            $this->dispatch('video-uploaded');
        } catch (\Exception $e) {
            Log::error('Error al cargar video: ' . $e->getMessage());
            $this->dispatch('showAlertMessage', type: 'error', message: __('general.error_uploading_video'));
            $this->introVideo = null;
        }
    }

    /**
     * Elimina archivos multimedia (imagen o video)
     */
    public function removeMedia($type)
    {
        try {
            if ($type == 'video') {
                $this->introVideo = null;
                $this->form->removeVideo();
                $this->dispatch('video-removed');
            } else {
                $this->form->removePhoto();
                $this->dispatch('photo-removed');
            }
        } catch (\Exception $e) {
            Log::error('Error al eliminar archivo: ' . $e->getMessage());
            $this->dispatch('showAlertMessage', type: 'error', message: __('general.error_removing_file'));
        }
    }

    /**
     * Maneja la actualización del país
     */
    public function updatedFormCountry($value)
    {
        $this->form->state = null;
        Log::info('Country changed, state reset. Country ID: ' . $value);
    }

    /**
     * Maneja la actualización de la imagen
     */
    public function updatedImage()
    {

        //dd("imagen  aver ", $this->image);;
        try {
            Log::info('Iniciando validación de imagen');
            $this->validate([
                'image' => 'image|max:' . ($this->allowImageSize * 1024) . '|mimes:' . implode(',', $this->allowImgFileExt)
            ]);
            Log::info('Imagen validada correctamente');
            $this->form->setImage($this->image);
            $this->dispatch('image-uploaded');
        } catch (\Exception $e) {

            Log::error('Error al cargar imagen: ' . $e->getMessage());
            $this->dispatch('showAlertMessage', type: 'error', message: __('general.error_uploading_image'));
        }
    }

    /**
     * Maneja la carga de archivos
     */
    public function upload($field, $file)
    {

        //dd($field, $file, "llega aca la imagen");
        try {
            dd("entrando al try");

            if ($field === 'introVideo') {
                $this->isUploadingVideo = true;
                $this->introVideo = $file;
                $this->updatedIntroVideo();
            } else {
                $this->isUploadingImage = true;
                $this->image = $file;
               
                $this->updatedImage();
            }
            
            //Log::info('Archivo cargado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error en upload: ' . $e->getMessage());
            $this->dispatch('showAlertMessage', type: 'error', message: __('general.error_uploading_file'));
        } finally {
            $this->isUploadingImage = false;
            $this->isUploadingVideo = false;
        }
    }

    /**
     * Configura los ajustes de archivos permitidos
     */
    private function configureFileSettings()
    {
        $image_file_ext = setting('_general.allowed_image_extensions');
        $image_file_size = setting('_general.max_image_size');
        $video_file_size = setting('_general.max_video_size');
        $video_file_ext = setting('_general.allowed_video_extensions');
        
        Log::info('Configuración de archivos:', [
            'image_ext' => $image_file_ext,
            'image_size' => $image_file_size,
            'video_ext' => $video_file_ext,
            'video_size' => $video_file_size
        ]);

        $this->fileExt = $image_file_ext;
        $this->vedioExt = $video_file_ext;
        $this->imageFileSize = $image_file_size;
        $this->videoFileSize = $video_file_size;
        $this->googleApiKey = setting('_api.google_places_api_key');
        $this->allowImageSize = (int) (!empty($image_file_size) ? $image_file_size : '3');
        $this->allowImgFileExt = !empty($image_file_ext) ? explode(',', $image_file_ext) : ['jpg', 'png'];
        $this->allowVideoSize = (int) (!empty($video_file_size) ? $video_file_size : '20');
        $this->allowVideoFileExt = !empty($video_file_ext) ? explode(',', $video_file_ext) : ['mp4'];
    }
}
