<?php

namespace App\Livewire\Forms\Common\ProfileSettings;

use App\Traits\PrepareForValidation;
use App\Http\Requests\Common\Identity\IdentityStoreRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Form;

class IdentityVerificationForm extends Form
{
    use PrepareForValidation;

    public $lng;
    public $lat;
    public $user;
    public $image;
    public $identity;
    public $transcript;
    public string $city = '';
    public string $state = '';
    public $dateOfBirth;
    public string $country = '';
    public string $zipcode = '';
    public $identificationCard;
    public string $address = '';
    public $enableGooglePlaces;
    public $countryName = '';


    private ?IdentityStoreRequest $instructorRequest = null;

    public function boot()
    {
        $this->user = Auth::user();
        $this->instructorRequest = new IdentityStoreRequest();
        $this->enableGooglePlaces = setting('_api.enable_google_places') ?? '0';
    }

    public function rules(): array
    {

        return $this->instructorRequest->rules();
    }

    public function messages(): array
    {

        return $this->instructorRequest->messages();
    }

    public function updateInfo($hasState)
    {
        $rules = $this->rules();
        if ($hasState) {
            $rules['state'] = 'required|string';
        }

        $this->beforeValidation(['image', 'transcript', 'identificationCard']);
        $this->validate($rules);






        if ($this->image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            $filename = time() . '_' . $this->image->getClientOriginalName();
            $tempPath = $this->image->storeAs('temp', $filename);

            $destinationPath = public_path('storage/identity_photo');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }
            rename(storage_path('app/' . $tempPath), $destinationPath . '/' . $filename);

            $personalPhoto = 'identity_photo/' . $filename; // <--- SOLO identity_photo/
        }

        if ($this->identificationCard instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            $filename = time() . '_' . $this->identificationCard->getClientOriginalName();
            $tempPath = $this->identificationCard->storeAs('temp', $filename);

            $destinationPath = public_path('storage/identity_photo');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }
            rename(storage_path('app/' . $tempPath), $destinationPath . '/' . $filename);

            $identificationCard = 'identity_photo/' . $filename; // <--- SOLO identity_photo/
        }

        if ($this->transcript instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            $filename = time() . '_' . $this->transcript->getClientOriginalName();
            $tempPath = $this->transcript->storeAs('temp', $filename);

            $destinationPath = public_path('storage/identity_photo');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }
            rename(storage_path('app/' . $tempPath), $destinationPath . '/' . $filename);

            $transcript = 'identity_photo/' . $filename; // <--- SOLO identity_photo/
        }
        try {
            $dob = \Carbon\Carbon::createFromFormat('F-d-Y', $this->dateOfBirth)->format('Y-m-d');
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            $dob = null;
        }

        $identityInfo = [
            'personal_photo' => !empty($this->image) ? $personalPhoto : null,
            'user_id' => Auth::user()->id,
            'dob' => $dob,
            'attachments' => $this->user->hasRole('tutor') && !empty($this->identificationCard) ? $identificationCard : null,
            'transcript' => $this->user->hasRole('student') && !empty($this->transcript) ? $transcript : null,

        ];

        $address = [
            'country_id' => $this->country,
            'state_id' => !empty($this->state) ? $this->state : null,
            'city' => $this->city ?? null,
            'address' => $this->address,
            'zipcode' => $this->enableGooglePlaces != '1' ? $this->zipcode : null,
            'lat' => $this->enableGooglePlaces == '1' ? $this->lat : 0,
            'long' => $this->enableGooglePlaces == '1' ? $this->lng : 0,
        ];



        return [
            'identityInfo' => $identityInfo,
            'address' => $address,
        ];
    }

    public function removePhoto()
    {
        $this->image = null;
    }

    public function removeIdentificationCard()
    {
        $this->identificationCard = null;
    }

    public function removeTranscript()
    {
        $this->transcript = null;
    }
}
