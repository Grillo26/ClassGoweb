<?php

namespace App\Livewire\Pages\Admin\Insights;

use App\Models\UserIdentityVerification;
use App\Models\Profile;
use App\Models\SlotBooking;
use App\Services\InsightsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\User;

class Insights extends Component
{

    public $user;

    public $platformEarnings;
    public $tutorEarnings;
    public $platformCommission;
    public $tutorPendingEarnings;
    public $totalSessions;
    public $completedSessions;
    public $cancelledSessions;
    public $users;
    public $usersCount;
    public $tutors;
    public $tutorsCount;
    public $students;
    public $studentsCount;
    public $currentMonthUsers;
    public $lastMonthUsers;
    public $difference;

    public $revenueStartDate = null;
    public $revenueEndDate = null;

    public $sessionStartDate = null;
    public $sessionEndDate = null;
    public $tutor_name = '';
    public $student_name = '';

    public $userforGenerous = '';

    public $totalsessionAcepted = 0;
    public $genderStats = [];

    public $reservaspico = '';

    public $ageStats = [];

    public $reservasHorasPicos = [];


    public $horariosStats = [];

    private ?InsightsService $insightsService = null;


    public function boot()
    {
        $this->user = Auth::user();
        $this->insightsService = new InsightsService();
    }

    public function mount()
    {
        $this->tutor_name = Str::plural(!empty(setting('_lernen.tutor_display_name')) ? setting('_lernen.tutor_display_name') : __('general.tutor'));
        $this->student_name = Str::plural(!empty(setting('_lernen.student_display_name')) ? setting('_lernen.student_display_name') : __('general.student'));
        $this->revenueStartDate = now()->startOfMonth()->format('Y-m-d');
        $this->revenueEndDate = now()->endOfMonth()->format('Y-m-d');

        $this->sessionStartDate = now()->startOfMonth()->format('Y-m-d');
        $this->sessionEndDate = now()->endOfMonth()->format('Y-m-d');

        $users = $this->insightsService->getUsers(roles: ['tutor', 'student']);
        $usuarios = UserIdentityVerification::get();
        $this->ageStats = $this->processAgeData($usuarios);
        //dd($usuarios);

        $this->usersCount = $users->count();
        $this->users = $users->take(3);
        $this->userforGenerous = Profile::get();
        //dd($this->userforGenerous);
        $this->genderStats = $this->processGenderData($this->userforGenerous);

        $this->reservasHorasPicos = SlotBooking::get();
         $this->horariosStats = $this->processHorariosData($this->reservasHorasPicos);
   
        //dd($this->reservasHorasPicos);


        $tutors = $this->insightsService->getUsers(roles: ['tutor']);
        $this->tutorsCount = $tutors->count();

        $this->tutors = $tutors->take(6);


        $students = $this->insightsService->getUsers(roles: ['student']);
        $this->studentsCount = $students->count();
        $this->students = $students->take(6);

        $this->currentMonthUsers = $this->insightsService->getUsers(roles: ['tutor', 'student'], dateRange: 'current_month')->count();
        $this->lastMonthUsers = $this->insightsService->getUsers(roles: ['tutor', 'student'], dateRange: 'last_month')->count();

        if ($this->currentMonthUsers == 0 && $this->lastMonthUsers == 0) {
            $percentageChange = 0;
        } elseif ($this->lastMonthUsers == 0) {
            $percentageChange = 100;
        } else {
            $percentageChange = (($this->currentMonthUsers - $this->lastMonthUsers) / abs($this->lastMonthUsers)) * 100;
        }

        $this->difference = number_format($percentageChange);
    }

    #[Layout('layouts.admin-app')]
    public function render()
    {
        $this->platformEarnings = $this->insightsService->getPlatformEarnings(revenueStartDate: $this->revenueStartDate, revenueEndDate: $this->revenueEndDate);
        $this->tutorEarnings = $this->insightsService->getTutorEarnings(type: 'add', revenueStartDate: $this->revenueStartDate, revenueEndDate: $this->revenueEndDate);
        $this->tutorPendingEarnings = $this->insightsService->getTutorEarnings(type: 'pending_available', revenueStartDate: $this->revenueStartDate, revenueEndDate: $this->revenueEndDate);
        $this->platformCommission = $this->insightsService->getPlatformCommission(revenueStartDate: $this->revenueStartDate, revenueEndDate: $this->revenueEndDate);

        //$this->totalSessions                = $this->insightsService->getSessions(statuses: ['active', 'completed'], sessionStartDate: $this->sessionStartDate, sessionEndDate: $this->sessionEndDate);
        $this->totalSessions = SlotBooking::where('status', 5)->count();
        $this->totalsessionAcepted = SlotBooking::where('status', 1)->count();

        $this->completedSessions = SlotBooking::where('status', 5)->count();
        $this->cancelledSessions = SlotBooking::where('status', 4)->count();

        return view('livewire.pages.admin.insights.insights');
    }

    public function clearRevenue()
    {
        $this->revenueStartDate = null;
        $this->revenueEndDate = null;
    }

    public function clearSession()
    {
        $this->sessionStartDate = null;
        $this->sessionEndDate = null;
    }



    private function processGenderData($profiles)
    {
        $genderCounts = [
            'masculino' => 0,
            'femenino' => 0,
            'no_especificado' => 0
        ];

        foreach ($profiles as $profile) {
            switch ($profile->gender) {
                case 1:
                    $genderCounts['masculino']++;
                    break;
                case 2:
                    $genderCounts['femenino']++;
                    break;
                case 3:
                    $genderCounts['no_especificado']++;
                    break;
            }
        }

        // Calcular porcentajes
        $total = array_sum($genderCounts);
        $genderPercentages = [];

        if ($total > 0) {
            foreach ($genderCounts as $key => $count) {
                $genderPercentages[$key] = [
                    'count' => $count,
                    'percentage' => round(($count / $total) * 100, 1)
                ];
            }
        }

        return $genderPercentages;
    }





    private function processAgeData($usuarios)
    {
        $ageRanges = [
            '15-20' => 0,
            '20-25' => 0,
            '25-30' => 0,
            '30-35' => 0,
            '35-40' => 0,
            '40-45' => 0,
            '45-50' => 0,
            '50+' => 0
        ];

        foreach ($usuarios as $usuario) {
            if (!empty($usuario->dob)) {
                $age = \Carbon\Carbon::parse($usuario->dob)->age;

                if ($age >= 15 && $age < 20) {
                    $ageRanges['15-20']++;
                } elseif ($age >= 20 && $age < 25) {
                    $ageRanges['20-25']++;
                } elseif ($age >= 25 && $age < 30) {
                    $ageRanges['25-30']++;
                } elseif ($age >= 30 && $age < 35) {
                    $ageRanges['30-35']++;
                } elseif ($age >= 35 && $age < 40) {
                    $ageRanges['35-40']++;
                } elseif ($age >= 40 && $age < 45) {
                    $ageRanges['40-45']++;
                } elseif ($age >= 45 && $age < 50) {
                    $ageRanges['45-50']++;
                } elseif ($age >= 50) {
                    $ageRanges['50+']++;
                }
            }
        }

        return $ageRanges;
    }



    private function processHorariosData($reservas)
    {
        $horarios = [];

        // Inicializar todos los horarios de 6 AM a 11 PM
        for ($i = 6; $i <= 23; $i++) {
            $hora = sprintf('%02d:00', $i);
            $horarios[$hora] = 0;
        }

        foreach ($reservas as $reserva) {
            if (!empty($reserva->start_time)) {
                $hora = \Carbon\Carbon::parse($reserva->start_time)->format('H:00');

                // Solo contar si está en el rango de 6 AM a 11 PM
                if (isset($horarios[$hora])) {
                    $horarios[$hora]++;
                }
            }
        }

        return $horarios;
    }


}
