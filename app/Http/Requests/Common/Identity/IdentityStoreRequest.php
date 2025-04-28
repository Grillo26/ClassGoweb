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
            'name'         => 'required|min:3|max:100',
            'image'        => $imageValidation,
            'dateOfBirth'  => 'required',
        
            // Validaciones condicionales basadas en el rol del usuario
            'schoolName'   => auth()->user()->hasRole('student') ? 'required|max:255' : 'nullable',
            'schoolId'     => auth()->user()->hasRole('student') ? 'required|max:100' : 'nullable',
            'parentName'   => auth()->user()->hasRole('student') ? 'required|min:5|max:100' : 'nullable',
            'parentEmail'  => auth()->user()->hasRole('student') ? 'required|email|max:100' : 'nullable',
            'parentPhone'  => auth()->user()->hasRole('student') ? 'required|numeric|digits:11' : 'nullable',
        
            // Si `enableGooglePlaces` siempre es "0", estas reglas no dependen de la condición
            'lat'         => 'nullable|numeric|regex:/^-?\d{1,9}(\.\d{1,6})?$/',
            'lng'         => 'nullable|numeric|regex:/^-?\d{1,9}(\.\d{1,6})?$/',
            'country'     => 'required|numeric', // No depende de enableGooglePlaces
            'zipcode'     => 'nullable|alpha_num|regex:/^[a-zA-Z0-9]{5,10}$/',
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

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void {
        $this->merge([
            'name'                  => sanitizeTextField($this->name),
            'schoolName'            => sanitizeTextField($this->schoolName),
            'schoolId'              => sanitizeTextField($this->schoolId),
            'parentName'            => sanitizeTextField($this->parentName),
            'city'                  => sanitizeTextField($this->city),
        ]);
    }
}
