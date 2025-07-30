<?php

namespace App\Http\Requests\Common\Identity;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class IdentityStoreRequest extends BaseFormRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */

    public function rules() {
        $enableGooglePlaces             = setting('_api.enable_google_places') ?? '0';
        $imageFileExt                   = setting('_general.allowed_image_extensions') ?? 'jpg,png';
        $imageFileSize                  = (int) (setting('_general.max_image_size') ?? '5');
        $imageValidation                = 'required|mimes:'.$imageFileExt.'|max:'.$imageFileSize*1024;

       $rules = [
            'image'        => $imageValidation,
            'dateOfBirth'  => 'required',
        

        
            // Si `enableGooglePlaces` siempre es "0", estas reglas no dependen de la condición
            'lat'         => 'nullable|numeric|regex:/^-?\d{1,9}(\.\d{1,6})?$/',
            'lng'         => 'nullable|numeric|regex:/^-?\d{1,9}(\.\d{1,6})?$/',
            'country'     => 'required|numeric', // No depende de enableGooglePlaces
            /* 'zipcode'     => 'nullable|alpha_num|regex:/^[a-zA-Z0-9]{5,10}$/', */
            'city'        => 'nullable|string',
        ];
        
        $key = auth()->user()->role == 'tutor' ? 'identificationCard' : 'transcript';
        $rules[$key] = $imageValidation;
        return $rules;
    }


    public function messages() {
        $imageFileSize              = setting('_general.max_image_size') ?? '5';
        return [
            'required'              => __('general.required_field'),
            'digits'                => __('validation.invalid_phone'),
            'max'                   => __('validation.max_file_size_err', ['file_size'    => $imageFileSize.'MB']),
            'zipcode.regex'         => __('validation.min_length_field', ['field' => 'zipcode', 'length' => 5]),
        ];
    }


}
