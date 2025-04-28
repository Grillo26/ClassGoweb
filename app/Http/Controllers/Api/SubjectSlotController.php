<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSubjectSlot;

class SubjectSlotController extends Controller
{
    public function getUserSubjectSlots(Request $request)
    {
        // Obtener `user_id` y fechas de la solicitud
        $userId = $request->input('user_id');
        $date = $request->only(['start_date', 'end_date']);

        // Validar que se haya enviado el user_id
        if (!$userId) {
            return response()->json(['error' => 'User ID is required'], 400);
        }

        // Obtener los horarios filtrados por usuario
        $slotsData = $this->fetchUserSubjectSlots($userId, $date);

        return response()->json($slotsData);
    }

    protected function fetchUserSubjectSlots($userId, $date = null) {
        $slotsData = [];

        $slots = UserSubjectSlot::select('id','user_subject_group_subject_id','start_time','end_time','spaces','session_fee','total_booked','description','meta_data')
            ->withCount('bookings')
            ->with('students', fn($query) => $query->select('profiles.id','profiles.user_id', 'profiles.image')->limit(5))
            ->withWhereHas('subjectGroupSubjects', function ($query) use ($userId) {
                $query->select('id', 'user_subject_group_id', 'subject_id', 'hour_rate', 'image');
                $query->withWhereHas('userSubjectGroup', function ($subjectGroup) use ($userId) {
                    $subjectGroup->with('group:id,name');
                    $subjectGroup->select('id','user_id','subject_group_id')->where('user_id', $userId);
                });
                $query->with('subject:id,name');
            })
            ->when($date, function ($slots) use ($date) {
                $slots->where('start_time', '>=', $date['start_date']);
                $slots->where('end_time', '<=', $date['end_date']);
            })
            ->orderBy('start_time')
            ->get();

        if ($slots->isNotEmpty()) {
            foreach ($slots as $item) {
                $group = $item->subjectGroupSubjects?->userSubjectGroup?->group?->name;
                $subject  = $item?->subjectGroupSubjects?->subject;

                $slotsData[$group][$subject?->name]['slots'][] = $item;
                $slotsData[$group][$subject?->name]['info'] = [
                    'user_subject_id'       => $item?->subjectGroupSubjects?->id,
                    'user_subject_group_id' => $item?->subjectGroupSubjects?->user_subject_group_id,
                    'subject_id'            => $item?->subjectGroupSubjects?->subject_id,
                    'subject'               => $subject?->name,
                    'hour_rate'             => $item?->subjectGroupSubjects?->hour_rate,
                    'image'                 => $item?->subjectGroupSubjects?->image,
                ];
            }
        }

        return $slotsData;
    }
}
