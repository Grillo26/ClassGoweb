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
use App\Models\Code;
use App\Models\Coupon;
use Illuminate\Support\Str;
use App\Models\UserCoupon;


/**
 * Componente Livewire para la verificación de identidad del usuario.
 * Permite a los usuarios cargar información y documentos para verificar su identidad.
 * Gestiona la lógica de selección de país, estados, subida de archivos y notificaciones.
 */
class IdentityVerification extends Component
{

    use WithFileUploads;
    public IdentityVerificationForm $form;
    private $identityService;
    public $identityInfo;
    public $identity;
    public $isLoading = true;
    public $personalPhoto;
    public $docs, $existingDocs = [];
    public $verified = false;
    public $user = '';
    public $countries = null;
    public $states;
    public $allowImgFileExt = [];
    public $fileExt = '';
    public $allowImageSize = '';
    public $data;
    public $profile;
    public $emailTemplate;
    public $hasStates = false;
    public $activeRoute;
    private ?IdentityService $userIdentity = null;
    private ?ProfileService $profileService = null;
    public function boot()
    {
        $this->userIdentity = new IdentityService(Auth::user());
        $this->profileService = new ProfileService(Auth::user()->id);
        $this->user = Auth::user();
    }

    public function loadData()
    {
        $this->isLoading = false;
        $this->dispatch('loadPageJs');
    }

    public function mount()
    {
        $this->activeRoute = Route::currentRouteName();
        $this->profile = $this->profileService->getUserProfile();
        $this->countries = Country::get(['id', 'name']) ?? [];
        ;
        $this->emailTemplate = setting('_lernen.for_role') ?? (object) ['status' => 'both'];
        $image_file_ext = setting('_general.allowed_image_extensions') ?? 'jpg,png';
        $image_file_size = (int) (setting('_general.max_image_size') ?? '5');
        $this->allowImageSize = !empty($image_file_size) ? $image_file_size : '5';
        $this->allowImgFileExt = !empty($image_file_ext) ? explode(',', $image_file_ext) : [];
        $this->fileExt = fileValidationText($this->allowImgFileExt);

        // Si el perfil no está completo, redirige y muestra error
        /*  if (Auth::user()->profile?->created_at == Auth::user()->profile?->updated_at) {
             $redirectRoute = route(Auth::user()->role . '.profile.personal-details');
             $message = __('general.incomplete_profile_error');

             $this->dispatch('showConfirmAndRedirect', [
                 'message' => $message,
                 'url' => $redirectRoute,

             ]);
         } */



        $this->dispatch('initSelect2', target: '.am-select2');
    }


    public function updatedForm($value, $key)
    {
        if ($key == 'countryName') {
            $country = Country::where('short_code', $value)->select('id')->first();
            $this->form->country = $country?->id;

        } elseif (in_array($key, ['image', 'identificationCard', 'transcript'])) {
            $entro = 'asdhvasjdasdas';
            $mimeType = $value->getMimeType();
            $type = explode('/', $mimeType);

            if ($type[0] != 'image') {
                $this->dispatch('showAlertMessage', type: `error`, message: __('validation.invalid_file_type', ['file_types' => fileValidationText($this->allowImgFileExt)]));
                $this->form->{$key} = null;
                return;
            }
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $this->states = null;
        $this->identity = $this->userIdentity->getUserIdentityVerification();
        if (!empty($this->form->country)) {
            /*             dd( $this->states =$this->userIdentity->countryStates($this->form->country)
                       ,"se seleccionó un país"); */
            $this->states = $this->userIdentity->countryStates($this->form->country);
            if ($this->states->isNotEmpty()) {
                //dd($this->states, "hay estados");
                $this->hasStates = true;
                $this->dispatch('initSelect2', target: '#country_state');
            } else {
                $this->hasStates = false;
            }
        }
        $enableGooglePlaces = '0';
        \Log::info('Valor de enableGooglePlaces:', ['value' => $enableGooglePlaces]);
        $googleApiKey = setting('_api.google_places_api_key');
        return view('livewire.pages.common.profile-settings.identity-verification', compact('enableGooglePlaces', 'googleApiKey'));
    }

    /**
     * Elimina un archivo multimedia del formulario según el tipo.
     * @param string $type
     */
    public function removeMedia($type)
    {
        match ($type) {
            'personal_photo' => $this->form->removePhoto(),
            'identificationCard' => $this->form->removeIdentificationCard(),
            'transcript' => $this->form->removeTranscript()
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
        $this->dispatch('initSelect2', target: '.am-select2');
    }

    /**
     * Actualiza la información de identidad del usuario.
     * Valida, guarda en la base de datos y envía notificaciones.
     * Maneja transacciones y errores de validación.
     */
    public function updateInfo()
    {
        $this->data = $this->form->updateInfo($this->hasStates);


        if (Auth::user()->profile?->created_at == Auth::user()->profile?->updated_at) {
            session()->flash('error', __('general.incomplete_profile_error'));
            //return $this->redirect(route(Auth::user()->role . '.profile.personal-details'), navigate: true);
        }
        else {
        try {
            $this->data['address']['lat'] = 0.0;
            $this->data['address']['long'] = 0.0;
            DB::beginTransaction();
            $this->data['identityInfo']['name'] = $this->user->profile->first_name . ' ' . $this->user->profile->last_name;

            $userIdentity = $this->userIdentity->setUserIdentityVerification($this->data['identityInfo']);

            $this->userIdentity->setUserAddress($userIdentity?->id, $this->data['address']);
            DB::commit();
            $this->Coupons();
            try {
                $adminEmail = env('MAIL_FROM_ADDRESS');
                $user = Auth::user();
                $contenido = "El usuario {$user->profile->first_name} - {$user->profile->last_name}  ({$user->email}) ha hecho una solicitud de verificación de identidad.";
                \Mail::raw($contenido, function ($message) use ($adminEmail) {
                    $message->to($adminEmail)
                        ->subject('Nueva solicitud de verificación de identidad');
                });
            } catch (\Exception $e) {
                \Log::error('Error al enviar correo de solicitud de verificación: ' . $e->getMessage());
            }


        } catch (\Illuminate\Validation\ValidationException $e) {

            //dd($e->errors());
            DB::rollBack();
            //dd('errores');  
        }
        $this->data['identityInfo']['gender'] = $this->profile?->gender;
        $this->data['identityInfo']['email'] = Auth::user()->email;
        $this->data['identityInfo']['role'] = Auth::user()->role;
        dispatch(new SendNotificationJob('identityVerificationRequest', User::admin(), $this->data));
        if (Auth::user()->hasRole('student') && $this->emailTemplate?->status !== 'both') {
            return;
        }
        dispatch(new SendNotificationJob('identityVerificationRequest', Auth::user(), $this->data));
       }
    }




    /**
     * funccion que agrega 5 cupones al usuario estudiante
     * @return void
     */
    public function Coupons()
    {
        $user = Auth::user();
        // Buscar el UserCoupon del usuario que tenga cantidad 5 y esté asociado a un cupón creado en la fecha de registro
        $userCoupon = UserCoupon::where('user_id', $user->id)
            ->where('cantidad', 5)
            ->whereHas('coupon', function ($query) use ($user) {
                $query->whereDate('created_at', $user->created_at->toDateString());
            })
            ->first();
        if ($userCoupon && $userCoupon->coupon) {
            // Actualizar solo el cupón específico del usuario
            $userCoupon->coupon->update([
                'estado' => 'activo',
                'descuento' => 100,
                'fecha_caducidad' => now()->addDays(30)
            ]);
        }
    }
}
