<?php

namespace App\Http\Requests\Tutor\ManageSessions;

use Illuminate\Foundation\Http\FormRequest;

class SessionStoreRequest extends FormRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() {
        return [
            'date_range'  => 'required',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
        ];
    }

    public function messages() {
        return [
            'required'                      => __('validation.required_field'),
            'end_time'                      => __('validation.time_range_error')
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void {
        $this->merge([
            'description'                => sanitizeTextField($this->description, keep_linebreak: true),
        ]);
    }
}
