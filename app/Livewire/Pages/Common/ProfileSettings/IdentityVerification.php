<?php

namespace App\Livewire\Pages\Common\ProfileSettings;

use App\Jobs\SendNotificationJob;
use App\Livewire\Forms\Common\ProfileSettings\IdentityVerificationForm;
use App\Models\Country;
use App\Models\User;
use App\Services\IdentityService;
use App\Services\ProfileService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Componente Livewire para la verificación de identidad del usuario.
 * Permite a los usuarios cargar información y documentos para verificar su identidad.
 * Gestiona la lógica de selección de país, estados, subida de archivos y notificaciones.
 */
class IdentityVerification extends Component {

    use WithFileUploads;
    /**
     * Formulario de verificación de identidad (Livewire Form Object)
     * @var IdentityVerificationForm
     */
    public IdentityVerificationForm $form;
    /**
     * Servicio de identidad del usuario
     * @var IdentityService|null
     */
    private $identityService;
    /**
     * Información de identidad del usuario
     * @var mixed
     */
    public $identityInfo;
    /**
     * Identidad actual del usuario
     * @var mixed
     */
    public $identity;
    /**
     * Indica si la vista está cargando
     * @var bool
     */
    public $isLoading = true;
    /**
     * Foto personal subida
     * @var mixed
     */
    public $personalPhoto;
    /**
     * Documentos subidos y existentes
     * @var mixed
     */
    public $docs, $existingDocs = [];
    /**
     * Indica si se está subiendo un archivo
     * @var bool
     */
    public $isUploading = false;
    /**
     * Indica si el usuario está verificado
     * @var bool
     */
    public $verified = false;
    /**
     * Usuario autenticado
     * @var mixed
     */
    public $user = '';
    /**
     * Lista de países
     * @var array|null
     */
    public $countries = null;
    /**
     * Lista de estados del país seleccionado
     * @var mixed
     */
    public $states;
    /**
     * Extensiones de imagen permitidas
     * @var array
     */
    public $allowImgFileExt = [];
    /**
     * Texto de extensiones de archivo permitidas
     * @var string
     */
    public $fileExt = '';
    /**
     * Tamaño máximo de imagen permitido (MB)
     * @var string
     */
    public $allowImageSize  = '';
    /**
     * Datos procesados del formulario
     * @var mixed
     */
    public $data;
    /**
     * Perfil del usuario
     * @var mixed
     */
    public $profile;
    /**
     * Plantilla de email para notificaciones
     * @var mixed
     */
    public $emailTemplate;
    /**
     * Indica si el país tiene estados
     * @var bool
     */
    public $hasStates = false;
    /**
     * Ruta activa
     * @var mixed
     */
    public $activeRoute;

    /**
     * Servicio de identidad del usuario (privado)
     * @var IdentityService|null
     */
    private ?IdentityService $userIdentity = null;
    /**
     * Servicio de perfil del usuario (privado)
     * @var ProfileService|null
     */
    private ?ProfileService $profileService = null;

    /**
     * Inicializa los servicios de identidad y perfil, y asigna el usuario autenticado.
     * Se ejecuta al bootear el componente.
     */
    public function boot()
    {
        $this->userIdentity     = new IdentityService(Auth::user());
        $this->profileService   = new ProfileService(Auth::user()->id);
        $this->user             = Auth::user();
    }

    /**
     * Marca la vista como cargada y dispara el evento para cargar JS de la página.
     */
    public function loadData()
    {
        $this->isLoading            = false;
        $this->dispatch('loadPageJs');
    }

    /**
     * Inicializa variables al montar el componente.
     * Carga países, perfil, configuraciones y verifica si el perfil está completo.
     */
    public function mount()
    {
        $this->activeRoute       = Route::currentRouteName();
        $this->profile           = $this->profileService->getUserProfile();
        $this->countries         = Country::get(['id','name']) ?? [];;
        $this->emailTemplate      = setting('_lernen.for_role') ?? (object)['status' => 'both'];
        $image_file_ext          = setting('_general.allowed_image_extensions') ?? 'jpg,png';
        $image_file_size         = (int) (setting('_general.max_image_size') ?? '5');
        $this->allowImageSize    = !empty( $image_file_size ) ? $image_file_size : '5';
        $this->allowImgFileExt   = !empty( $image_file_ext ) ?  explode(',', $image_file_ext) : [];
        $this->fileExt           = fileValidationText($this->allowImgFileExt);

        // Si el perfil no está completo, redirige y muestra error
        if(Auth::user()->profile?->created_at == Auth::user()->profile?->updated_at){
            Session::flash('error', __('general.incomplete_profile_error'));
            return $this->redirect(route(Auth::user()->role.'.profile.personal-details'), navigate:true);
        }

        $this->dispatch('initSelect2', target: '.am-select2' );
    }

    /**
     * Se ejecuta cuando se actualiza algún campo del formulario.
     * Si el campo es countryName, busca el ID del país por su short_code.
     * Si es un archivo, valida que sea imagen.
     * @param mixed $value
     * @param string $key
     */
    public function updatedForm($value, $key)
    {

        
        if( $key == 'countryName' ) {
            $country = Country::where('short_code',$value)->select('id')->first();
            $this->form->country =  $country?->id;
            
        } elseif( in_array($key, ['image', 'identificationCard','transcript']) ) {
            $entro= 'asdhvasjdasdas';
            $mimeType = $value->getMimeType();
            $type = explode('/', $mimeType);
          
            if($type[0] != 'image') {
                $this->dispatch('showAlertMessage', type: `error`, message: __('validation.invalid_file_type', ['file_types' => fileValidationText($this->allowImgFileExt)]));
                $this->form->{$key} = null;
                return;
            }
        }
    }

    /**
     * Renderiza la vista del componente.
     * Carga los estados si hay país seleccionado y dispara eventos para select2.
     * También obtiene la clave de Google Places si aplica.
     * @return \Illuminate\View\View
     */
    #[Layout('layouts.app')]
    public function render()
    {
        $this->states = null;
        $this->identity         = $this->userIdentity->getUserIdentityVerification();
        if(!empty($this->form->country)){
/*             dd( $this->states =$this->userIdentity->countryStates($this->form->country)
           ,"se seleccionó un país"); */
            $this->states =$this->userIdentity->countryStates($this->form->country);
            if($this->states->isNotEmpty()){
                //dd($this->states, "hay estados");
                $this->hasStates = true;
                $this->dispatch('initSelect2', target: '#country_state' );
            } else {
                $this->hasStates = false;
            }
        }
        $enableGooglePlaces = '0';
        \Log::info('Valor de enableGooglePlaces:', ['value' => $enableGooglePlaces]);
        $googleApiKey           = setting('_api.google_places_api_key');
        return view('livewire.pages.common.profile-settings.identity-verification',compact('enableGooglePlaces','googleApiKey'));
    }

    /**
     * Elimina un archivo multimedia del formulario según el tipo.
     * @param string $type
     */
    public function removeMedia($type)
    {
        match ($type) {
            'personal_photo'        => $this->form->removePhoto(),
            'identificationCard'    => $this->form->removeIdentificationCard(),
            'transcript'            => $this->form->removeTranscript()
        };
    }

    /**
     * Evento para eliminar la identidad y dirección del usuario.
     * Resetea el formulario y reinicializa select2.
     */
    #[On('cancel-identity')]
    public function removeIdentity()
    {
        $this->userIdentity->deleteUserAddress($this->identity->id);
        $this->userIdentity->deleteUserIdentityVerification();
        $this->form->reset();
        $this->dispatch('initSelect2', target: '.am-select2' );
    }

    /**
     * Actualiza la información de identidad del usuario.
     * Valida, guarda en la base de datos y envía notificaciones.
     * Maneja transacciones y errores de validación.
     */
    public function updateInfo()
    {
        $this->data = $this->form->updateInfo($this->hasStates);
        $response = isDemoSite();
        if( $response ){
            $this->dispatch('showAlertMessage', type: 'error', title:  __('general.demosite_res_title') , message: __('general.demosite_res_txt'));
            return;
        }
        try {
            $this->data['address']['lat'] = 0.0;
            $this->data['address']['long'] = 0.0;
            DB::beginTransaction();
            //dd($this->data, "data");
            $userIdentity = $this->userIdentity->setUserIdentityVerification($this->data['identityInfo']);
            $this->userIdentity->setUserAddress($userIdentity?->id, $this->data['address']);
            DB::commit();
        } catch (\Illuminate\Validation\ValidationException $e) {
          
            dd($e->errors());
            DB::rollBack();
        }
        $this->data['identityInfo']['gender'] = $this->profile?->gender;
        $this->data['identityInfo']['email'] = Auth::user()->email;
        $this->data['identityInfo']['role'] = Auth::user()->role;
        dispatch(new SendNotificationJob('identityVerificationRequest',User::admin(), $this->data)); 
        if (Auth::user()->hasRole('student') && $this->emailTemplate?->status !== 'both') {
            return;
        }
        dispatch(new SendNotificationJob('identityVerificationRequest', Auth::user(), $this->data));
    }
}
