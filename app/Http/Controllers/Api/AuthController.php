<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Services\RegisterService;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
class AuthController extends Controller
{
    use ApiResponser;

     /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

     public function register(RegisterUserRequest $request)
     {

        $response = isDemoSite();
        if( $response ){
            return $this->error(data: null,message: __('general.demosite_res_txt'),code: Response::HTTP_FORBIDDEN);
        }

         $registerService                = new RegisterService();
         $user                           = $registerService->registerUser($request);
         $success['token']               = $user->token;
         $success['user']                = new UserResource($user);
         $success['email_verfied']       = $user->email_verified_at;
         return $this->success($success, __('api.user_register_successfully'));
     }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */

    public function login(LoginRequest $request){
        \Log::info('Entró a login', $request->all());
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

            $user = Auth::user();
            
            try {
                $user->load([
                    'profile:id,user_id,first_name,last_name,gender,recommend_tutor,intro_video,native_language,verified_at,slug,image,tagline,description,created_at,updated_at',
                    'address:country_id,state_id,city,address',
                    'roles',
                    'userWallet:id,user_id,amount'
                ]);
                
                // Verificar que roles sea una colección válida
                if (!($user->roles instanceof \Illuminate\Database\Eloquent\Collection)) {
                    $user->setRelation('roles', collect($user->roles ? [$user->roles] : []));
                }
                
                // Asegurar que el campo available_for_tutoring esté disponible
                $user->available_for_tutoring = $user->available_for_tutoring ?? true;
                
                \Log::info('Relaciones cargadas exitosamente para usuario: ' . $user->id);
                
            } catch (\Exception $e) {
                \Log::error('Error cargando relaciones para usuario ' . $user->id . ': ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                
                // Intentar cargar sin address si hay problema
                try {
                    $user->load([
                        'profile:id,user_id,first_name,last_name,gender,recommend_tutor,intro_video,native_language,verified_at,slug,image,tagline,description,created_at,updated_at',
                        'roles',
                        'userWallet:id,user_id,amount'
                    ]);
                    \Log::info('Relaciones cargadas sin address para usuario: ' . $user->id);
                } catch (\Exception $e2) {
                    \Log::error('Error crítico cargando relaciones: ' . $e2->getMessage());
                    return $this->error('Error interno del servidor', null, 500);
                }
            }


            $user->tokens()->where('name', 'lernen')->delete();
            $success['token']   =  $user->createToken('lernen', ['*'], now()->addDays(7))->plainTextToken;
            
            $success['user']    =  new UserResource($user);
            if (!empty($user->email_verified_at)) {
                return $this->success($success, __('api.user_login_successfully'));
            } else {
                return $this->error(__('api.user_not_verified'), $success);
            }

        } else {
            return $this->error(__('api.credentials_not_matched'));
        }
    }

     /**
     * Resend Email
     *
     * @return \Illuminate\Http\Response
     */

    public function resendEmail() {

        $registerService  = new RegisterService();
        $user             = Auth::user();
        $response         = $registerService->sendEmailVerificationNotification($user);
        if ($response) {
            return $this->success(message: __('api.email_send_successfully'));
        }
    }

    /**
     *  resetEmailPassword
     *  @return \Illuminate\Http\Response
     */

    public function resetEmailPassword(Request $request) {
        $response = isDemoSite();
        if( $response ){
            return $this->error(data: null,message: __('general.demosite_res_txt'),code: Response::HTTP_FORBIDDEN);
        }
        $request->validate([
            'email' => 'required|email',
        ]);
        $registerService  = new RegisterService();
        $response         = $registerService->sendPasswordResetLink($request);
        if(empty($response['success'])){
            return $this->error(message: __($response['message']));
        }
        return $this->success(message: __($response['message']));
    }


    /**
     * Reset Password
     *
     * @return \Illuminate\Http\Response
     */

    public function resetPassword(ResetPasswordRequest $request) {

        $response = isDemoSite();
        if( $response ){
            return $this->error(data: null,message: __('general.demosite_res_txt'),code: Response::HTTP_FORBIDDEN);
        }
        
        $registerService  = new RegisterService();
        $response = $registerService->resetPassword($request);

        if (empty($response['success'])) {
            return $this->error(message: __($response['message']));
        } else {
            return $this->success(message: __($response['message']));
        }
    }

    /**
     *  Logout
     *  @return \Illuminate\Http\Response
     */

    public function logout(Request $request) {
       $response = $request->user()->currentAccessToken()->delete();
        if($response){
            return $this->success(message: __('api.user_logout_successfully'));
        }
    }

    public function updateFcmToken(Request $request)
    {
        \Log::info('Entró a updateFcmToken', $request->all());
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'fcm_token' => 'required|string',
        ]);
        $user = \App\Models\User::find($request->user_id);
        $user->fcm_token = $request->fcm_token;
        $user->save();
        return response()->json(['message' => 'FCM token actualizado correctamente']);
    }

    /**
     * Verifica el correo electrónico vía API (para app y web)
     * @return \Illuminate\Http\Response
     */
    public function verifyEmail(Request $request)
    {
        $id = $request->query('id');
        $hash = $request->query('hash');
        if (!$id || !$hash) {
            return $this->error(message: 'Parámetros inválidos.');
        }
        $user = \App\Models\User::find($id);
        if (!$user) {
            return $this->error(message: 'Usuario no encontrado.');
        }
        if (!hash_equals($hash, sha1($user->email))) {
            return $this->error(message: 'Hash inválido.');
        }
        $alreadyVerified = (bool) $user->email_verified_at;
        if (!$alreadyVerified) {
            $user->email_verified_at = now();
            $user->save();
        }
        // Generar token de acceso
        $token = $user->createToken('classgoapp', ['*'], now()->addDays(7))->plainTextToken;
        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => new \App\Http\Resources\UserResource($user),
            'message' => $alreadyVerified ? 'El correo ya estaba verificado.' : 'Correo verificado correctamente.'
        ]);
    }

    /**
     * Actualiza la disponibilidad del tutor para tutorías
     * @return \Illuminate\Http\Response
     */
    public function updateTutoringAvailability(Request $request)
    {
        $request->validate([
            'available_for_tutoring' => 'required|boolean',
        ]);

        $user = Auth::user();
        
        // Verificar que el usuario sea tutor
        if ($user->role !== 'tutor') {
            return $this->error(message: 'Solo los tutores pueden cambiar su disponibilidad.', code: Response::HTTP_FORBIDDEN);
        }

        $user->available_for_tutoring = $request->available_for_tutoring;
        $user->save();

        return $this->success(
            data: new UserResource($user),
            message: $request->available_for_tutoring 
                ? 'Disponibilidad activada correctamente.' 
                : 'Disponibilidad desactivada correctamente.'
        );
    }
}
