<?php

namespace App\Livewire\Pages\Tutor\ManageSessions;

use App\Livewire\Forms\Tutor\ManageSessions\SubjectForm;
use App\Services\SubjectService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManageSubjects extends Component
{
    use WithFileUploads;

    public $selected_groups = [];
    public $subjectGroups = [];
    public $groups = [];
    public $isLoading = true;
    public $subjects = [];
    public $allowImgFileExt = [];
    public $allowImageSize = '3';
    public SubjectForm $form;
    public $MAX_PROFILE_CHAR = 1000;
    protected $subjectService;
    public $activeRoute;

    #[Layout('layouts.app')]
    public function render()
    {
        $this->subjectGroups = $this->subjectService->getUserSubjectGroups();
        //dd($this->subjectGroups, "grupos");
        return view('livewire.pages.tutor.manage-sessions.manage-subjects');
    }

    public function boot()
    {
        $this->subjectService = new SubjectService(Auth::user());
    }

    public function mount()
    {
        $this->activeRoute = Route::currentRouteName();
        $image_file_ext = setting('_general.allowed_image_extensions');
        $image_file_size = setting('_general.max_image_size');
        $this->allowImgFileExt = !empty($image_file_ext) ? explode(',', $image_file_ext) : ['jpg', 'png'];
        $this->allowImageSize = (int) (!empty($image_file_size) ? $image_file_size : '3');
    }

    public function loadData()
    {
        $this->isLoading            = false;
    }

    public function addNewSubjectGroup()
    {
        $this->groups = $this->subjectService->getSubjectGroups()?->toArray();
        $this->dispatch('toggleModel', id: 'subject_group', action: 'show');
    }

    public function loadSubjectsByGroup($groupId)
    {
        $this->form->group_id = $groupId;
        $groupSubjects = $this->subjectService->getSubjectsByGroup($groupId);
        
        $result = [
            [
                'id' => '',
                'text' => __('Select a subject')
            ]
        ];

        foreach ($groupSubjects as $subject) {
            $result[] = [
                'id' => $subject->id,
                'text' => htmlspecialchars_decode($subject->name)
            ];
        }

        $this->dispatch('initSelect2', 
            target: '.am-select2', 
            data: $result, 
            value: null,
            reset: true
        );
    }



    public function addNewSubject($subjectGroupId = '')
    {
        $this->form->reset();
        $this->form->group_id = $subjectGroupId;
        $this->form->hour_rate = 15;

        if ($subjectGroupId) {
            $this->loadSubjectsByGroup($subjectGroupId);
        }

        $this->dispatch('toggleModel', id: 'subject_modal', action: 'show');
    }

    public function getUserGroupSubject($groupId){
        return $this->subjectService->getUserGroupSubjects($groupId);
    }

    

    #[On('delete-user-subject')]
    public function deleteSubject($params)
    {
        $response = isDemoSite();
        if( $response ){
            $this->dispatch('showAlertMessage', type: 'error', title:  __('general.demosite_res_title') , message: __('general.demosite_res_txt'));
            return;
        }
        $result = $this->subjectService->deteletSubject($params['groupId'], $params['subjectId']);
        if($result){
            $this->dispatch(
                'showAlertMessage',
                type: 'success',
                title: __('general.success_title') ,
                message: __('general.delete_record')
            );
        } else {
            $this->dispatch('showAlertMessage', type: 'error', message: __('general.unable_to_delete_subject'));
        }
    }

    

    public function saveNewSubject()
    {
        dd($this->form, "form");
        $validate = $this->form->validateData();
        $response = isDemoSite();
        if( $response ){
            $this->dispatch('showAlertMessage', type: 'error', title:  __('general.demosite_res_title') , message: __('general.demosite_res_txt'));
            $this->dispatch('toggleModel', id: 'subject_modal', action: 'hide');
            return;
        }
        $subject = $this->form->addNewSubject($validate);
       
        $result = $this->subjectService->setUserSubject($this->form->edit_id, $subject);
       
        $this->form->reset();
        $this->dispatch('showAlertMessage',
            type: !empty($result) ? 'success': 'error',
            title: !empty($result) ? __('general.success_title'): __('general.error_title') ,
            message: !empty($result) ? __('general.success_message') : __('general.error_message')
        );
        if(!empty($result)){
            $this->dispatch('toggleModel', id: 'subject_modal', action: 'hide');
        }
    }


    public function removeImage()
    {
        $this->form->image = null;
        $this->form->preview_image = null;
    }




    public function updateSubjectGroupOrder($evt)
    {
        $this->subjectService->updateSubjectGroupSortOrder($evt);
        $this->dispatch(
            'showAlertMessage',
            type:  'success',
            title: __('general.success_title'),
            message: __('general.success_message')
        );
    }

    public function updateSubjectOrder($evt)
    {
        $this->subjectService->updateSubjectSortOrder($evt);
        $this->dispatch(
            'showAlertMessage',
            type:  'success',
            title: __('general.success_title'),
            message: __('general.success_message')
        );
    }

    public function resetForm()
    {
        $this->form->reset();
        $this->form->hour_rate = 15; // Fijar el precio de la sesión en 15
    }

    public function updatedForm($value, $key)
{
    if ($key == 'hour_rate') {
        $this->form->hour_rate = 15; // Mantiene el precio en 15
    }

    if ($key == 'image' && !is_string($value)) {
        $mimeType = $value->getMimeType();
        $type = explode('/', $mimeType);
        if ($type[0] != 'image') {
            $this->dispatch('showAlertMessage', type: 'error', message: __('validation.invalid_file_type', ['file_types' => fileValidationText($this->allowImgFileExt)]));
            $this->form->{$key} = null;
            return;
        }
    }
}

    public function editSubject($groupId, $subjectData)
    {
        $this->form->group_id = $groupId;
        $this->form->edit_id = $subjectData['edit_id'];
        $this->form->subject_id = $subjectData['subject_id'];
        $this->form->hour_rate = $subjectData['hour_rate'];
        $this->form->description = $subjectData['description'];
        $this->form->sort_order = $subjectData['sort_order'];
        $this->form->image = $subjectData['image'];

        // Obtener la materia que se está editando
        $editingSubject = $this->subjectService->getSubjects()->where('id', $this->form->subject_id)->first();
        
        $result = [
            [
                'id' => '',
                'text' => __('Select a subject')
            ],
            [
                'id' => $editingSubject->id,
                'text' => htmlspecialchars_decode($editingSubject->name),
                'selected' => true
            ]
        ];

        $this->dispatch('initSelect2', target: '.am-select2', data: $result, value: $editingSubject->id, reset: true);
        $this->dispatch('toggleModel', id: 'subject_modal', action: 'show');
    }

}
