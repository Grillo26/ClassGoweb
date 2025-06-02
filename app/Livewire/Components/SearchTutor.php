<?php

namespace App\Livewire\Components;

use App\Http\Requests\Student\Booking\SendMessageRequest;
use App\Services\SiteService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subject;

// #[Lazy]
class SearchTutor extends Component
{
    use WithPagination;

    public $filters;
    public $isLoadPage = false;
    protected $paginationTheme = 'bootstrap';
    public $allowFavAction = false;
    public $repeatItems = 10;
    public $message;
    public $recepientId, $threadId;
    public $subjectGroups = [];
    public $subjects = [];
    public $group_id;
    public $subject_id = null;
    private $siteService;
    private $userService;

    public function boot(SiteService $siteService) {
        $this->siteService = $siteService;
        $user = Auth::user();
        $this->userService = new UserService($user);
    }

    public function placeholder()
    {
        $repeatItems = !empty($this->filters['per_page']) ? $this->filters['per_page'] : (setting('_general.per_page_opt') ?? 10);
        return view('skeletons.tutor-fullpage-list', compact('repeatItems'));
   
    }

    public function mount($filters = [])
    {
        $this->subjectGroups = \App\Models\SubjectGroup::all();
        $this->group_id = $filters['group_id'] ?? null;
        $this->subject_id = $filters['subject_id'] ?? null;
        $this->filters = $filters;
        $this->isLoadPage = true;
        $this->updateSubjects();
        $this->repeatItems = !empty($this->filters['per_page']) 
            ? $this->filters['per_page'] 
            : (setting('_general.per_page_opt') ?? 10);
        if(Auth::user()?->role == 'student'){
            $this->allowFavAction = true;
        }
    }

    public function updatedGroupId()
    {
        $this->filters['group_id'] = $this->group_id;
        $this->filters['subject_id'] = null;
        $this->subject_id = null;
        $this->updateSubjects();
        $this->resetPage();
    }

    public function updatedSubjectId()
    {
        $this->filters['subject_id'] = $this->subject_id;
        $this->resetPage();
    }

    public function updateSubjects()
    {
        if ($this->group_id) {
            $this->subjects = Subject::where('subject_group_id', $this->group_id)->get();
        } else {
            $this->subjects = [];
        }
    }

    public function render()
    {
        $favouriteTutors = array();
        $tutors = [];
        try {
            //dd($this->filters, "aver cuales son los filters");
            $this->filters['group_id'] = $this->group_id;
            $this->filters['subject_id'] = $this->subject_id;
            $tutors = $this->siteService->getTutors($this->filters);
            if ($this->allowFavAction){
                $favouriteTutors = $this->userService->getFavouriteUsers()
                    ->get(['favourite_user_id'])
                    ?->pluck('favourite_user_id')
                    ->toArray();
            }
        } catch (\Exception $e) {
            \Log::error('Error loading tutors:', ['error' => $e->getMessage()]);
        } 
        $this->dispatch('initVideoJs');
        //dd($this->filters);
        return view('livewire.components.search-tutor', [
            'tutors' => $tutors,
            'favouriteTutors' => $favouriteTutors,
            'subjects' => $this->subjects,
            'subjectGroups' => $this->subjectGroups,
            'filters' => $this->filters
        ]);
    }

    public function loadPage()
    {
        try {
            $this->isLoadPage = true;
            //Log::info('Page loading initiated');
        } catch (\Exception $e) {
            //Log::error('Error in loadPage:', ['error' => $e->getMessage()]);
        }
    }

    #[On('tutorFilters')]
    public function applyFilter($filters)
    {
 
        $this->resetPage();
        $this->filters = $filters;
    }

    public function updatingPage()
    {
        $this->dispatch('initVideoJs', timeout:1000);
    }

    #[Renderless]
    public function toggleFavourite($userId)
    {
        $response = isDemoSite();
        if( $response ){
            $this->dispatch('showAlertMessage', type: 'error', title:  __('general.demosite_res_title') , message: __('general.demosite_res_txt'));
            return;
        }
        if ( $this->allowFavAction){
            $isFavourite = $this->userService->isFavouriteUser($userId);
            if($isFavourite){
                $this->userService->removeFromFavourite($userId);
            } else {
                $this->userService->addToFavourite($userId);
            }
            $this->dispatch('toggleFavIcon', userId: $userId);
        } else {
            $this->dispatch('showAlertMessage', type: `error`, message: __('general.not_allowed'));
        }
    }

    public function sendMessage()
    {
        $messageReq = new SendMessageRequest();
        $this->validate($messageReq->rules(), $messageReq->messages());
        $threadInfo = sendMessage($this->recepientId, Auth::user()->id, $this->message);
        $this->threadId = $threadInfo->getData(true)['data']['message']['threadId'] ?? null;
        if($threadInfo){
            $this->reset('message');
        }
    }


    #[On('clearAllFilters')]
    public function clearAllFilters()
{
    $this->filters = [];
    $this->group_id = null;
    $this->subjects = [];
    $this->resetPage();
}
}
