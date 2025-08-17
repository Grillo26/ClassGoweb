<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Common\PersonalDetail\PersonalDetailRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use ApiResponser;

    public function getProfile($id)
    {

        $user = User::find($id);
        
        if(empty($user)){
            return $this->error(data: null,message: __('api.not_found'),code: Response::HTTP_NOT_FOUND);
        }

        $user->load(['profile', 'address', 'languages:id,name']);
        return $this->success(data: new UserResource($user),message: __('api.profile_data_retrieved_successfully'));
    }

    public function updateProfile(PersonalDetailRequest $request,$id)
    {
        $response = isDemoSite();
        if( $response ){
            return $this->error(data: null,message: __('general.demosite_res_txt'),code: Response::HTTP_FORBIDDEN);
        }
        
        if($id != Auth::user()?->id){
            return $this->error(data: null,message: __('api.unauthorized_access'),code: Response::HTTP_FORBIDDEN);
        }

        $profileService     = new ProfileService(Auth::user()?->id);
        $user               = User::find(Auth::user()?->id);

        if (!$user) {
            return $this->error(data: null,message: __('api.not_found'),code: Response::HTTP_NOT_FOUND);
        }

        $profileData = [
            'user_id'            => $user?->id,
            'first_name'         => $request?->first_name,
            'last_name'          => $request?->last_name,
            'phone_number'       => $request?->phone_number,
            'gender'             => $request?->gender,
            'native_language'    => $request?->native_language,
            'description'        => $request?->description,
            'tagline'            => $request?->tagline,
            'recommend_tutor'    => $request?->recommend_tutor ?? 'no',
        ];
        
        if ($request->hasFile('image')) {
            $fileName    = uniqueFileName('public/profile', $request->image->getClientOriginalName());
            $profileData['image'] = $request->image->storeAs('profile', $fileName, 'public');
        } else {
            $profileData['image'] = $request?->image;
        }
         
        if ($request->hasFile('intro_video')) {
            $fileName = uniqueFileName('public/profile_videos', $request->intro_video->getClientOriginalName());
            $profileData['intro_video'] = $request->intro_video->storeAs('profile_videos', $fileName, 'public');
        } else {
            $profileData['intro_video'] = $request?->intro_video;
        }
        
        $languagesData = $request?->user_languages;
        $languagesData = array_unique($languagesData);
        $addressData = [
            'country_id'        => $request?->country,
            'state_id'          => $request?->state,
            'city'              => $request?->city,
            'address'           => $request?->address,
            'zipcode'           => $request?->zipcode,
            'lat'               => $request?->lat ?? 0,
            'long'              => $request?->long ?? 0 ,
        ];

        $profileService->setUserProfile($profileData);
        $profileService->storeUserLanguages($languagesData);
        $profileService->setUserAddress($addressData);

        $userProfile = $user->load(['profile', 'Languages', 'address']);
        return $this->success(message: __('api.profile_data_updated_successfully') ,data: new UserResource($userProfile),code: Response::HTTP_OK);
    }

    public function getProfileImage($id)
    {
        $user = \App\Models\User::with('profile')->find($id);
        if (!$user || !$user->profile) {
            return response()->json(['message' => 'Usuario o perfil no encontrado'], 404);
        }
        $rutaBD = $user->profile->image ?? null;
        $url = $rutaBD ? url('public/storage/' . $rutaBD) : null;
        return response()->json([
            'id' => $user->id,
            'profile_image' => $url,
            'profile_image_db_path' => $rutaBD,
            'name' => $user->name ?? $user->profile->full_name ?? null,
            'email' => $user->email,
        ]);
    }

    public function updateProfileImage(Request $request, $id)
    {
        $user = \App\Models\User::with('profile')->find($id);
        if (!$user || !$user->profile) {
            return response()->json(['message' => 'Usuario o perfil no encontrado'], 404);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        // Guardar la imagen directamente en public/storage/profile_images
        $fileName = uniqid() . '_' . $request->file('image')->getClientOriginalName();
        $destinationPath = public_path('storage/profile_images');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $request->file('image')->move($destinationPath, $fileName);
        $relativePath = 'profile_images/' . $fileName;

        // Actualizar el campo image en el perfil
        $user->profile->image = $relativePath;
        $user->profile->save();

        $url = url('public/storage/' . $relativePath);

        return response()->json([
            'id' => $user->id,
            'profile_image' => $url,
            'profile_image_db_path' => $relativePath,
            'message' => 'Imagen de perfil actualizada correctamente'
        ]);
    }

    /**
     * Actualizar solo los datos del perfil del usuario
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserProfile(Request $request, $id)
    {
        // Verificar que el usuario esté autenticado y sea el propietario del perfil
        if ($id != Auth::user()?->id) {
            return $this->error(
                data: null,
                message: __('api.unauthorized_access'),
                code: Response::HTTP_FORBIDDEN
            );
        }

        // Validar los datos de entrada
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'first_name' => 'nullable|string|max:150',
            'last_name' => 'nullable|string|max:150',
            'gender' => 'nullable|integer|in:0,1,2', // 0: No especificado, 1: Masculino, 2: Femenino
            'recommend_tutor' => 'nullable|integer|in:0,1',
            'slug' => 'nullable|string|max:255|unique:profiles,slug,' . $id . ',user_id',
            'native_language' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'keywords' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::find($id);
            if (!$user) {
                return $this->error(
                    data: null,
                    message: __('api.not_found'),
                    code: Response::HTTP_NOT_FOUND
                );
            }

            // Obtener o crear el perfil
            $profile = $user->profile;
            if (!$profile) {
                $profile = new \App\Models\Profile();
                $profile->user_id = $user->id;
            }

            // Actualizar solo los campos que se envían en la request
            $profileFields = [
                'first_name', 'last_name', 'gender', 'recommend_tutor', 'slug',
                'native_language', 'tagline', 'description', 'phone_number', 'keywords'
            ];

            foreach ($profileFields as $field) {
                if ($request->has($field)) {
                    $profile->$field = $request->$field;
                }
            }

            // Manejar la imagen si se envía
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096'
                ]);

                // Generar nombre único para la imagen
                $fileName = uniqid() . '_' . $request->file('image')->getClientOriginalName();
                
                // Crear directorio si no existe
                $destinationPath = public_path('storage/profile_images');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                // Mover la imagen al directorio correcto
                $request->file('image')->move($destinationPath, $fileName);
                
                // Guardar solo la ruta relativa en la base de datos
                $profile->image = 'profile_images/' . $fileName;
            }

            // Manejar el video de introducción si se envía
            if ($request->hasFile('intro_video')) {
                $request->validate([
                    'intro_video' => 'mimes:mp4,avi,mov,wmv,flv|max:10240' // 10MB máximo
                ]);

                // Generar nombre único para el video
                $fileName = uniqid() . '_' . $request->file('intro_video')->getClientOriginalName();
                
                // Crear directorio si no existe
                $destinationPath = public_path('storage/profile_videos');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                // Mover el video al directorio correcto
                $request->file('intro_video')->move($destinationPath, $fileName);
                
                // Guardar solo la ruta relativa en la base de datos
                $profile->intro_video = 'profile_videos/' . $fileName;
            }

            $profile->save();

            // Recargar el usuario con el perfil actualizado
            $user->load('profile');

            return $this->success(
                message: __('api.profile_data_updated_successfully'),
                data: new UserResource($user),
                code: Response::HTTP_OK
            );

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el perfil',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
