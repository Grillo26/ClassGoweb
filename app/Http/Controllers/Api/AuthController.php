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
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

            $user = Auth::user();
            $user->load([
                'profile:id,user_id,first_name,last_name,gender,recommend_tutor,intro_video,native_language,verified_at,slug,image,tagline,description,created_at,updated_at',
                'address:country_id,state_id,city,address,zipcode',
                'roles',
                'userWallet:id,user_id,amount'
            ]);


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
}
