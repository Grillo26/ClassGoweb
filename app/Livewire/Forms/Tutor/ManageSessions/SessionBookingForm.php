<?php

namespace App\Livewire\Forms\Tutor\ManageSessions;

use App\Http\Requests\Tutor\ManageSessions\SessionStoreRequest;
use App\Traits\PrepareForValidation;
use Livewire\Form;

class SessionBookingForm extends Form
{
    use PrepareForValidation;
    public $date_range = '';
    public $start_time='';
    public $end_time='';
    public $duration;
    public $break;
    public $spaces = 1;
    public $recurring_days = [];
    public $session_fee;
    public $description;
    public $subject_group_id;
    public $meeting_link = '';
    public $action = '';
    public $form_date = '';

    public function rules(){
        return [
            'date_range' => $this->action === 'edit' ? 'nullable|string' : 'required|string',
            'form_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'session_fee' => 'nullable|numeric',
            'description' => 'nullable|string',
            'spaces' => 'nullable|integer|min:1',
        ];
    }

    public function messages() {
        $request = new SessionStoreRequest();
        return $request->messages();
    }

    public function setFee($fee) {
        $this->session_fee = $fee;
    }

    public function validateData() {
        $this->beforeValidation();
        return $this->validate();
    }

    public function beforeValidation()
    {
        if (empty($this->form_date) && !empty($this->date_range)) {
            $dates = explode(' to ', $this->date_range);
            $this->form_date = $dates[0]; // Toma la primera fecha del rango para la validación
        }
    }
}
