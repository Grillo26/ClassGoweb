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
use Livewire\WithPagination;
use App\Models\UserSubject;


class ManageSubjects extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $selected_groups = [];
    public $subjectGroups = [];
    public $groups = [];
    public $isLoading = true;
    public $subjects = [];
    public $userSubjects = [];
    public $allowImgFileExt = [];
    public $allowImageSize = '3';
    public SubjectForm $form;
    public $MAX_PROFILE_CHAR = 1000;
    protected $subjectService;
    public $activeRoute;
    public $selectedGroup = 'Ciencias Exactas'; // Valor por defecto
    public $perPage = 10; // Número de elementos por página
    public $currentPage = 1;
    public $searchQuery = '';
    public $showOnlyWithSubjects = false;


    #[Layout('layouts.app')]
    public function render()
    {
        $this->subjectGroups = $this->subjectService->getUserSubjectGroups($this->selectedGroup);
        
        // Aplicar filtro de búsqueda
        if (!empty($this->searchQuery)) {
            $this->subjectGroups = collect($this->subjectGroups)->filter(function ($group) {
                return str_contains(strtolower($group->name), strtolower($this->searchQuery));
            })->values();
        }

        // Aplicar filtro de grupos con materias
        if ($this->showOnlyWithSubjects) {
            $this->subjectGroups = collect($this->subjectGroups)->filter(function ($group) {
                return collect($this->userSubjects)->contains(function ($userSubject) use ($group) {
                    return $userSubject['subject']['subject_group_id'] == $group->id;
                });
            })->values();
        }
        
        // Implementar paginación manual
        $paginatedGroups = collect($this->subjectGroups)->forPage($this->currentPage, $this->perPage);
        $totalGroups = count($this->subjectGroups);
        $totalPages = ceil($totalGroups / $this->perPage);
        
        return view('livewire.pages.tutor.manage-sessions.manage-subjects', [
            'filteredGroups' => $paginatedGroups,
            'totalPages' => $totalPages,
            'currentPage' => $this->currentPage
        ]);
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
        $this->isLoading = false;
        $this->userSubjects = $this->subjectService->getUserSubjectsWithSubjects(Auth::id());
        $this->currentPage = 1; // Resetear a la primera página cuando se cargan nuevos datos
    }

    public function obtenerUserSubjects()
    {
        //$this->userSubjects = $this->subjectService->getUserSubjectsWithSubjects(Auth::id());
    }


    public function saveNewSubject()
    {
        $validate = $this->form->validateData();
        $response = isDemoSite();
        if ($response) {
            $this->dispatch('showAlertMessage', type: 'error', title: __('general.demosite_res_title'), message: __('general.demosite_res_txt'));
            $this->dispatch('toggleModel', id: 'subject_modal', action: 'hide');
            return;
        }
        // Procesar la imagen si existe
        if ($this->form->image && method_exists($this->form->image, 'temporaryUrl')) {
            $imagePath = $this->form->image->store('subjects', 'public');
            $validate['image'] = $imagePath;
        }
        $userId = Auth::id();
        // Crear el array de datos para la materia
        $subject = [
            'subject_id' => $this->form->subject_id,
            'description' => $validate['description'],
            'image' => $validate['image'] ?? null,
            'user_id' => $userId,
        ];

        $result = $this->subjectService->saveUserSubject($subject);
        //no tocar
        $this->form->reset();
        $this->dispatch(
            'showAlertMessage',
            type: !empty($result) ? 'success' : 'error',
            title: !empty($result) ? __('general.success_title') : __('general.error_title'),
            message: !empty($result) ? __('general.success_message') : __('general.error_message')
        );
        if (!empty($result)) {
            $this->dispatch('toggleModel', id: 'subject_modal', action: 'hide');
        }
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
        $this->dispatch(
            'initSelect2',
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

    


    #[On('delete-user-subject')]
    public function deleteSubject($params)
    {
        $response = isDemoSite();
        if ($response) {
            $this->dispatch('showAlertMessage', type: 'error', title: __('general.demosite_res_title'), message: __('general.demosite_res_txt'));
            return;
        }
        $result = $this->subjectService->deteletSubject($params['groupId'], $params['subjectId']);
        if ($result) {
            $this->dispatch(
                'showAlertMessage',
                type: 'success',
                title: __('general.success_title'),
                message: __('general.delete_record')
            );
        } else {
            $this->dispatch('showAlertMessage', type: 'error', message: __('general.unable_to_delete_subject'));
        }
    }

    public function removeImage()
    {
        $this->form->image = null;
        $this->form->preview_image = null;
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



    /**
     * Editar un UserSubject
     * 
     * @param int $userSubjectId
     * @return void
     */
    public function editUserSubject($userSubjectId)
    {
        $userSubject = $this->subjectService->getUserSubjects(Auth::id())
            ->where('id', $userSubjectId)
            ->first();

        if ($userSubject) {
            $this->form->reset();
            $this->form->edit_id = $userSubject->id;
            $this->form->subject_id = $userSubject->subject_id;
            $this->form->description = $userSubject->description;
            $this->form->image = $userSubject->image;

            $subject = $this->subjectService->getSubjectbyId($userSubject->subject_id);

            $result = [
                [
                    'id' => '',
                    'text' => __('Select a subject')
                ],
                [
                    'id' => $subject->id,
                    'text' => htmlspecialchars_decode($subject->name),
                    'selected' => true
                ]
            ];
            $this->dispatch('initSelect2', target: '.am-select2', data: $result, value: $subject->id, reset: true);
            $this->dispatch('toggleModel', id: 'subject_modal', action: 'show');
        }
    }

    /**
     * Eliminar un UserSubject
     * 
     * @param array $params
     * @return void
     */
    #[On('delete-user-subject')]
    public function deleteUserSubject($params)
    {
        $response = isDemoSite();
        if ($response) {
            $this->dispatch('showAlertMessage', type: 'error', title: __('general.demosite_res_title'), message: __('general.demosite_res_txt'));
            return;
        }

        $userSubject = UserSubject::where('id', $params['subjectId'])
            ->where('user_id', Auth::id())
            ->first();

        if ($userSubject) {
            $userSubject->delete();
            $this->dispatch(
                'showAlertMessage',
                type: 'success',
                title: __('general.success_title'),
                message: __('general.delete_record')
            );
            $this->loadData(); // Recargar los datos
        } else {
            $this->dispatch('showAlertMessage', type: 'error', message: __('general.unable_to_delete_subject'));
        }
    }

    public function updatedSelectedGroup($value)
    {
        $this->resetPage();
    }

    public function nextPage()
    {
        $totalPages = ceil(count($this->subjectGroups) / $this->perPage);
        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
            $this->dispatch('$refresh');
        }
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->dispatch('$refresh');
        }
    }

    public function goToPage($page)
    {
        $totalPages = ceil(count($this->subjectGroups) / $this->perPage);
        if ($page >= 1 && $page <= $totalPages) {
            $this->currentPage = $page;
            $this->dispatch('$refresh');
        }
    }

    public function updatedSearchQuery()
    {
        $this->currentPage = 1;
    }

    public function updatedShowOnlyWithSubjects()
    {
        $this->currentPage = 1;
    }

    public function resetFilters()
    {
        $this->searchQuery = '';
        $this->showOnlyWithSubjects = false;
        $this->currentPage = 1;
    }
}






/* public function addNewSubjectGroup()
{
    $this->groups = $this->subjectService->getSubjectGroups()?->toArray();
    $this->dispatch('toggleModel', id: 'subject_group', action: 'show');
} */



/* public function editSubject($groupId, $subjectData)
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
} */



/* public function updateSubjectOrder($evt)
{
    $this->subjectService->updateSubjectSortOrder($evt);
    $this->dispatch(
        'showAlertMessage',
        type:  'success',
        title: __('general.success_title'),
        message: __('general.success_message')
    );
} */




/* public function getUserGroupSubject($groupId)
    {
        return $this->subjectService->getUserGroupSubjects($groupId);
} */



/* public function updateSubjectGroupOrder($evt)
    {
        $this->subjectService->updateSubjectGroupSortOrder($evt);
        $this->dispatch(
            'showAlertMessage',
            type: 'success',
            title: __('general.success_title'),
            message: __('general.success_message')
        );
    } */
