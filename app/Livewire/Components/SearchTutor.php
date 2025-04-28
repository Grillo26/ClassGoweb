<?php

namespace App\Livewire\Components;

use App\Http\Requests\Student\Booking\SendMessageRequest;
use App\Models\Country;
use App\Services\SiteService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Component;
use Livewire\WithPagination;

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

    public function render()
    {
        $favouriteTutors = array();
        $tutors = [];

        try {
            $tutors = $this->siteService->getTutors($this->filters);
            \Log::info('Tutors loaded:', ['count' => $tutors->count(), 'filters' => $this->filters]);
            
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
        return view('livewire.components.search-tutor', compact('tutors', 'favouriteTutors'));
    }

    public function loadPage()
    {
        try {
            $this->isLoadPage = true;
            \Log::info('Page loading initiated');
        } catch (\Exception $e) {
            \Log::error('Error in loadPage:', ['error' => $e->getMessage()]);
        }
    }

    public function mount($filters = [])
    {
        try {
            $this->repeatItems = !empty($this->filters['per_page']) 
                ? $this->filters['per_page'] 
                : (setting('_general.per_page_opt') ?? 10);
            
            $this->filters = $filters;
            $this->isLoadPage = true;
            \Log::info('Component mounted with filters:', ['filters' => $filters]);

            if(Auth::user()?->role == 'student'){
                $this->allowFavAction = true;
            }
        } catch (\Exception $e) {
            \Log::error('Error in mount:', ['error' => $e->getMessage()]);
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
}
