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
    public $subjectGroups = [];
    public $isLoading = true;
    public $userSubjects = [];
    public $allowImgFileExt = [];
    public $allowImageSize = '3';
    public SubjectForm $form;
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
        $this->subjectGroups = $this->subjectService->getUserSubjectGroups();

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
    
    public function saveNewSubject()
    {
            // Validar directamente los campos requeridos para la materia
            $validated = validator([
                'subject_id'   => $this->form->subject_id,
                'description'  => $this->form->description,
                'image'        => $this->form->image,
            ], [
                'subject_id'   => 'required|integer',
                'description'  => 'required|string|min:20|max:255',
                'image'        => 'nullable|image|max:' . ($this->allowImageSize * 1024),
            ])->validate();
            //modo demo
            $response = isDemoSite();
            if ($response) {
                $this->dispatch('showAlertMessage', type: 'error', title: __('general.demosite_res_title'), message: __('general.demosite_res_txt'));
                $this->dispatch('toggleModel', id: 'subject_modal', action: 'hide');
                return;
            }
            // Procesar la imagen si existe
            if ($this->form->image && method_exists($this->form->image, 'temporaryUrl')) {
                $imagePath = $this->form->image->store('subjects', 'public');
                $validated['image'] = $imagePath;
            }
            $userId = Auth::id();
            $subject = [
                'subject_id' => $validated['subject_id'],
                'description' => $validated['description'],
                'image' => $validated['image'] ?? null,
                'user_id' => $userId,
            ];
            $result = $this->subjectService->saveUserSubject($subject);
            //resetear el formulario
            $this->form->reset();
            
            $this->dispatch(
                'showAlertMessage',
                type: !empty($result) ? 'success' : 'error',
                title: !empty($result) ? __('algo aver ') : __('general.error_title'),
                message: !empty($result) ? __('general.success_message') : __('general.error_message')
            );
            if (!empty($result)) {
                // Cerrar el modal
                $this->dispatch('toggleModel', id: 'subject_modal', action: 'hide');
                // Recargar los datos para actualizar la lista
                $this->loadData();
            }        
    }

    public function updateSubjectGroupOrder($order)
    {
        // Aquí puedes guardar el nuevo orden si lo necesitas
        // Ejemplo: Log::info($order);
    }


    public function loadSubjectsByGroup($groupId)
    {
        // Obtener los subjects ya asociados al usuario
        $userSubjectIds = collect($this->userSubjects)->pluck('subject_id')->toArray();
        // Filtrar los subjects del grupo excluyendo los ya asociados
        $groupSubjects = $this->subjectService->getSubjectsByGroup($groupId)
            ->filter(function ($subject) use ($userSubjectIds) {
                return !in_array($subject->id, $userSubjectIds);
            });
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
        if ($subjectGroupId)
            $this->loadSubjectsByGroup($subjectGroupId);
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
            //dd($userSubject,"aver");
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
