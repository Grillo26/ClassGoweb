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
        $this->selected_groups = $this->subjectService->getUserSubjectGrouaps()?->toArray() ?? [];
        $this->subjectGroups = $this->subjectService->getUserSubjectGroups();

        return view('livewire.pages.tutor.manage-sessions.manage-subjects');
    }

    public function boot()
    {
        $this->subjectService = new SubjectService(Auth::user());
    }

    public function mount()
    {
        $this->activeRoute = Route::currentRouteName();
        $image_file_ext          = setting('_general.allowed_image_extensions');
        $image_file_size          = setting('_general.max_image_size');
        $this->allowImgFileExt   = !empty( $image_file_ext ) ?  explode(',', $image_file_ext) : ['jpg', 'png'];
        $this->allowImageSize    = (int) (!empty( $image_file_size ) ? $image_file_size : '3');

        $this->subjects = $this->subjectService->getSubjects()?->pluck('name','id')?->toArray() ?? [];

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

    public function addNewSubject($subjectGroupId = '')
    {
        $this->form->reset();
        $this->form->group_id = $subjectGroupId;
        $this->form->hour_rate = 15;

        // Obtener todas las materias del grupo seleccionado
        $groupSubjects = $this->subjectService->getSubjectsByGroup($subjectGroupId);
        
        // Obtener las materias que ya tiene asignadas el tutor en este grupo
        $assignedSubjects = $this->getUserGroupSubject($subjectGroupId);
        
        // Debug para verificar las materias
        \Log::info('Materias para el grupo ' . $subjectGroupId, [
            'todas' => $groupSubjects->toArray(),
            'asignadas' => $assignedSubjects
        ]);

        $result = [
            [
                'id' => '',
                'text' => __('Select a subject')
            ]
        ];

        // Agregar solo las materias que NO están asignadas
        foreach ($groupSubjects as $subject) {
            if (!isset($assignedSubjects[$subject->id])) {
                $result[] = [
                    'id' => $subject->id,
                    'text' => htmlspecialchars_decode($subject->name)
                ];
            }
        }

        // Primero destruimos y limpiamos el select2 existente
        $this->dispatch('destroySelect2');
        
        // Esperamos un momento antes de inicializar el nuevo select2
        $this->dispatch('initSelect2', 
            target: '.am-select2', 
            data: $result, 
            value: null,
            reset: true,
            timeOut: 300
        );

        // Finalmente mostramos el modal
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

    #[On('delete-user-subject-group')]
    public function deleteSubjectGroup($params)
    {
        $response = isDemoSite();
        if( $response ){
            $this->dispatch('showAlertMessage', type: 'error', title:  __('general.demosite_res_title') , message: __('general.demosite_res_txt'));
            return;
        }
        $result = $this->subjectService->deleteUserSubjectGroup($params['groupId']);
        if($result){
            $this->dispatch(
                'showAlertMessage',
                type: 'success',
                title: __('general.success_title') ,
                message: __('general.delete_record')
            );
        } else {
            $this->dispatch('showAlertMessage', type: 'error', message: __('general.unable_to_delete_subject_group'));
        }
    }

    public function saveNewSubject()
    {
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

    public function seveSubjectGroups()
    {
        $this->validate([
            'selected_groups' => 'required|min:1|array'
        ]);

        $response = isDemoSite();
        if( $response ){
            $this->dispatch('showAlertMessage', type: 'error', title:  __('general.demosite_res_title') , message: __('general.demosite_res_txt'));
            $this->dispatch('toggleModel', id: 'subject_group', action: 'hide');
            return;
        }

        $updateGroups = $this->subjectService->setSubjectGroups($this->selected_groups);
        $this->dispatch('toggleModel', id: 'subject_group', action: 'hide');
        $this->dispatch(
            'showAlertMessage',
            type: $updateGroups ? 'success': 'error',
            title:$updateGroups ? __('general.success_title') : __('general.error_title'),
            message: $updateGroups ?__('general.success_message') : __('general.error_msg')
        );
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
