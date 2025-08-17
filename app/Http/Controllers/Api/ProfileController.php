<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Common\PersonalDetail\PersonalDetailRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        // Validación de autorización temporalmente deshabilitada para pruebas

        // Validar los datos de entrada
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'first_name' => 'nullable|string|max:150',
            'last_name' => 'nullable|string|max:150',
            'gender' => 'nullable|string|in:0,1,2', // Cambiado a string para form-data
            'recommend_tutor' => 'nullable|string|in:0,1', // Cambiado a string para form-data
            'slug' => 'nullable|string|max:255|unique:profiles,slug,' . $id . ',user_id',
            'native_language' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'keywords' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            Log::error('Error de validación:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Log de los datos recibidos
            Log::info('Datos recibidos en updateUserProfile:', $request->all());
            Log::info('Content-Type:', ['content_type' => $request->header('Content-Type')]);
            Log::info('¿Tiene archivos?', [
                'has_image' => $request->hasFile('image'),
                'has_video' => $request->hasFile('intro_video'),
                'first_name_type' => gettype($request->input('first_name')),
                'last_name_type' => gettype($request->input('last_name'))
            ]);
            
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
            } else {
                // Log del perfil existente
                Log::info('Perfil existente encontrado:', [
                    'id' => $profile->id,
                    'first_name' => $profile->first_name,
                    'last_name' => $profile->last_name,
                    'user_id' => $profile->user_id
                ]);
            }

            // Actualizar solo los campos que se envían en la request
            $profileFields = [
                'first_name', 'last_name', 'gender', 'recommend_tutor', 'slug',
                'native_language', 'tagline', 'description', 'phone_number', 'keywords'
            ];

            foreach ($profileFields as $field) {
                if ($request->has($field)) {
                    $oldValue = $profile->$field;
                    $profile->$field = $request->$field;
                    Log::info("Campo {$field} actualizado: '{$oldValue}' -> '{$request->$field}'");
                    Log::info("Valor del request para {$field}: '{$request->$field}'");
                    Log::info("Valor asignado al perfil para {$field}: '{$profile->$field}'");
                } else {
                    Log::info("Campo {$field} NO está en la request");
                }
            }



            // Manejar la imagen si se envía
            if ($request->hasFile('image')) {
                try {
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
                    
                    Log::info('Imagen guardada: ' . $profile->image);
                } catch (\Exception $e) {
                    Log::error('Error al guardar imagen: ' . $e->getMessage());
                }
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

            // Log antes de guardar
            Log::info('Perfil antes de guardar:', [
                'first_name' => $profile->first_name,
                'last_name' => $profile->last_name,
                'user_id' => $profile->user_id
            ]);

            // Log antes de guardar
            Log::info('Perfil antes de guardar:', [
                'first_name' => $profile->first_name,
                'last_name' => $profile->last_name,
                'user_id' => $profile->user_id
            ]);

            $result = $profile->save();
            Log::info('Resultado del save(): ' . ($result ? 'true' : 'false'));

            // Log después de guardar
            Log::info('Perfil después de guardar:', [
                'first_name' => $profile->first_name,
                'last_name' => $profile->last_name,
                'user_id' => $profile->user_id
            ]);

            // Recargar el usuario con el perfil actualizado
            $user->load('profile');

            // Verificar si los cambios se guardaron en la base de datos
            $profileRefreshed = \App\Models\Profile::find($profile->id);
            Log::info('Perfil desde la base de datos después de guardar:', [
                'id' => $profileRefreshed->id,
                'first_name' => $profileRefreshed->first_name,
                'last_name' => $profileRefreshed->last_name,
                'user_id' => $profileRefreshed->user_id
            ]);

            // Devolver respuesta con datos más detallados
            return response()->json([
                'success' => true,
                'message' => 'Profile data updated successfully.',
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'status' => $user->status,
                    'profile_completed' => $user->profile_completed,
                    'verified' => $user->verified,
                    'profile' => [
                        'id' => $profile->id,
                        'user_id' => $profile->user_id,
                        'first_name' => $profile->first_name,
                        'last_name' => $profile->last_name,
                        'gender' => $profile->gender,
                        'recommend_tutor' => $profile->recommend_tutor,
                        'slug' => $profile->slug,
                        'native_language' => $profile->native_language,
                        'tagline' => $profile->tagline,
                        'description' => $profile->description,
                        'phone_number' => $profile->phone_number,
                        'keywords' => $profile->keywords,
                        'image' => $profile->image,
                        'intro_video' => $profile->intro_video,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el perfil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar solo la imagen y video del perfil del usuario
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserProfileFiles(Request $request, $id)
    {
        // Log simple para verificar que el método se ejecuta
        Log::info('Método updateUserProfileFiles ejecutado', ['id' => $id]);
        
        // Validación de autorización temporalmente deshabilitada para pruebas

        // Validar que al menos un archivo se envíe
        if (!$request->hasFile('image') && !$request->hasFile('intro_video')) {
            return response()->json([
                'success' => false,
                'message' => 'Debe enviar al menos una imagen o video'
            ], 422);
        }

        try {
            // Log de los archivos recibidos
            Log::info('Archivos recibidos en updateUserProfileFiles:', [
                'has_image' => $request->hasFile('image'),
                'has_video' => $request->hasFile('intro_video'),
                'content_type' => $request->header('Content-Type')
            ]);
            
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            // Obtener o crear el perfil
            $profile = $user->profile;
            if (!$profile) {
                $profile = new \App\Models\Profile();
                $profile->user_id = $user->id;
            }

            // Manejar la imagen si se envía
            if ($request->hasFile('image')) {
                try {
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
                    
                    Log::info('Imagen guardada: ' . $profile->image);
                } catch (\Exception $e) {
                    Log::error('Error al guardar imagen: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al guardar la imagen: ' . $e->getMessage()
                    ], 500);
                }
            }

            // Manejar el video de introducción si se envía
            if ($request->hasFile('intro_video')) {
                try {
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
                    
                    Log::info('Video guardado: ' . $profile->intro_video);
                } catch (\Exception $e) {
                    Log::error('Error al guardar video: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al guardar el video: ' . $e->getMessage()
                    ], 500);
                }
            }

            // Guardar el perfil
            $profile->save();

            // Recargar el usuario con el perfil actualizado
            $user->load('profile');

            // Devolver respuesta
            return response()->json([
                'success' => true,
                'message' => 'Archivos del perfil actualizados correctamente',
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'profile' => [
                        'id' => $profile->id,
                        'user_id' => $profile->user_id,
                        'image' => $profile->image,
                        'intro_video' => $profile->intro_video,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar los archivos del perfil',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
