<?php

namespace App\Services;

use App\Models\Subject;
use App\Models\SubjectGroup;
use App\Models\UserSubject;
use App\Models\UserSubjectGroup;
use App\Models\UserSubjectGroupSubject;

use Illuminate\Database\Eloquent\Collection;

class SubjectService
{

    public $user;

    public function __construct($user = null)
    {
        if ($user) {
            $this->user = $user;
        }
    }





    /**
     * obtener los grupos de materias 
     * 
     * @return Collection
     */
    public function getUserSubjectGroups()
    {
        return SubjectGroup::select('id', 'name')->get();
    }







    /**
     * obtener las materias por el rol del usuario
     * @return Collection
     */
    public function getSubjectsByUserRole()
    {
        $query = UserSubjectGroupSubject::select('id', 'user_subject_group_id', 'subject_id', 'hour_rate');

        if ($this->user->role === 'tutor') {
            $query->whereHas('userSubjectGroup', function ($query) {
                $query->select('id');
                $query->whereUserId($this->user->id);
            });
        } elseif ($this->user->role === 'student') {
            $query->whereHas('slots', function ($query) {
                $query->select('id', 'user_subject_group_subject_id');
                $query->whereHas('bookings', function ($query) {
                    $query->select('id');
                    $query->whereStudentId($this->user->id);
                });
            });
        }

        $subjects = $query->with(['group:subject_groups.id,name', 'subject:id,name'])->get()
            ->groupBy('user_subject_group_id')
            ->map(function ($group) {
                return [
                    'group_name' => $group->first()->group ? $group->first()->group->name : null,
                    'subjects' => $group->map(function ($item) {
                        return [
                            'id'           => $item->id,
                            'subject_name' => $item->subject ? $item->subject->name : null,
                            'hour_rate'    => $item->hour_rate,
                        ];
                    })
                ];
            });

        return $subjects;
    }




    /**
     * obtener los grupos de materias
     * 
     * @return Collection
     */
    public function getSubjectGroups()
    {
        return SubjectGroup::get(['id', 'name']);
    }




    /**
     * obtener las materias
     * 
     * @return Collection
     */
    public function getSubjects()
    {
        return Subject::select('id', 'name', 'subject_group_id')->get();
    }



    public function saveSubject($subject)
    {
        return Subject::create($subject);
    }

    public function saveUserSubject($userSubject)
    {
        return UserSubject::create($userSubject);
    }

    public function getUserSubjects($userId,)
    {
    return UserSubject::where('user_id', $userId)->get();
    }

    /**
     * Obtener los UserSubjects con sus Subjects asociados
     * 
     * @param int $userId
     * @return Collection
     */
    public function getUserSubjectsWithSubjects($userId)
    {
        return UserSubject::where('user_id', $userId)
            ->with(['subject' => function($query) {
                $query->select('id', 'name', 'subject_group_id');
            }])
            ->get()
            ->map(function($userSubject) {
                return [
                    'id' => $userSubject->id,
                    'subject_id' => $userSubject->subject_id,
                    'description' => $userSubject->description,
                    'image' => $userSubject->image,
                    'status' => $userSubject->status,
                    'subject' => [
                        'id' => $userSubject->subject->id,
                        'name' => $userSubject->subject->name,
                        'subject_group_id' => $userSubject->subject->subject_group_id
                    ]
                ];
            });
    }

    public function getSubjectbyId($subjectId)
    {
        return Subject::where('id', $subjectId)->first();
    }


    //public function 

    /**
     * obtener las materias por el grupo
     * 
     * @param int $groupId
     * @return Collection
     */
    public function getSubjectsByGroup($groupId)
    {
        return Subject::where('subject_group_id', $groupId)
            ->select('id', 'name')
            ->get();
    }


    /**
     * establecer los grupos de materias
     * 
     * @param array $groupIds
     * @return Collection
     */
    public function setSubjectGroups(&$groupIds)
    {
        $groups = $this->user->groups()->get();

        $data = [];
        foreach ($groups as $group) {
            if (in_array($group->subject_group_id, $groupIds)) {
                unset($groupIds[array_search($group->subject_group_id, $groupIds)]);
            } else {
                $isDeleted = $this->deleteUserSubjectGroup($group->id);
                if (!$isDeleted) {
                    unset($groupIds[array_search($group->subject_group_id, $groupIds)]);
                }
            }
        }
        foreach ($groupIds as $id) {
            $data[] = [
                'user_id' => $this->user->id,
                'subject_group_id' => $id
            ];
        }
        return $this->user->groups()->createMany($data);
    }

    /**
     * obtener los grupos de materias del usuario
     * 
     * @return Collection
     */
    public function getUserSubjectGrouaps()
    {
        return $this->user->groups()->get()?->pluck('subject_group_id');
    }


    /**
     * establecer el grupo de materias del usuario
     * 
     * @param array $subjectGroup
     * @return void
     */
    public function setUserSubjectGroup($subjectGroup): void
    {
        if ($subjectGroup) {
            $group = $this->user->groups()->firstOrCreate(['subject_group_id' => $subjectGroup['subject_group_id']]);
            foreach ($subjectGroup['subject_id'] as $subj) {
                UserSubjectGroupSubject::updateOrCreate(['user_subject_group_id' => $group->id, 'subject_id' => $subj]);
            }
        }
    }

    /**
     * obtener el grupo de materias del usuario
     * 
     * @param int $subjectGroupId
     * @return array
     */

    public function getUserSubjectGroup($subjectGroupId)
    {
        $returnData = array();
        $groupSubjects =  $this->user->groups()->whereSubjectGroupId($subjectGroupId)->get()->first();
        $returnData['subject_group_id'] = $subjectGroupId;
        $returnData['group'] = $groupSubjects->group->name;
        foreach ($groupSubjects->subjects as $subject) {
            $returnData['subject_id'][] = $subject->id;
        }
        return $returnData;
    }

    /**
     * obtener las materias del grupo
     * 
     * @param int $groupId
     * @return array
     */

    public function getUserGroupSubjects($groupId)
    {
        $group = $this->user->groups()->whereId($groupId)->first();
        if ($group) {
            return $group?->subjects()?->get()?->pluck('name', 'id')?->toArray() ?? [];
        }
        return [];
    }

    /**
     * obtener la materia del grupo
     * 
     * @param int $pivotId
     * @return UserSubjectGroupSubject
     */
    public function getUserGroupSubject($pivotId): UserSubjectGroupSubject
    {
        return UserSubjectGroupSubject::whereId($pivotId)->get()->first();
    }


    /**
     * establecer la materia del grupo
     * 
     * @param int $id
     * @param array $subject
     * @return UserSubjectGroupSubject
     */
    public function setUserSubject($id, $subject): UserSubjectGroupSubject
    {
        $group = $this->user?->groups()?->whereId($subject['user_subject_group_id'])->first();
        if ($group) {
            return $group->userSubjects()?->updateOrCreate(['id' => $id], $subject);
        }
        return null;
    }

    /**
     * eliminar la materia del grupo
     * 
     * @param int $userGroupId
     * @param int $userSubjectId
     * @return bool
     */
    public function deteletSubject($userGroupId, $userSubjectId)
    {
        $group = $this->user->groups()?->whereId($userGroupId)->first();
        if ($group) {
            $groupSubject = $group->userSubjects()
                ->whereId($userSubjectId)
                ->whereDoesntHave('slots', fn($slot) => $slot->select('id', 'user_subject_group_subject_id'));
            if ($groupSubject) {
                return $groupSubject->delete();
            }
        }
        return null;
    }

    /**
     * actualizar el orden de las materias
     * 
     * @param array $subjectList
     * @return void
     */
    public function updateSubjectSortOrder($subjectList)
    {
        foreach ($subjectList as $group) {
            foreach ($group['items'] as $subject) {
                UserSubjectGroupSubject::find($subject['value'])->update(['sort_order' => $subject['order']]);
            }
        }
    }

    /**
     * actualizar el orden de los grupos de materias
     * 
     * @param array $groupList
     * @return void
     */
    public function updateSubjectGroupSortOrder($groupList)
    {
        foreach ($groupList as $group) {
            UserSubjectGroup::find($group['value'])->update(['sort_order' => $group['order']]);
        }
    }

    /**
     * eliminar la materia
     * @param int $pivotId
     * @return void
     */
    public function deleteSubject($pivotId): void
    {
        UserSubjectGroupSubject::whereId($pivotId)->delete();
    }

    /**
     * eliminar el grupo de materias
     * 
     * @param int $groupId
     * @return bool
     */
    public function deleteUserSubjectGroup($groupId): bool
    {
        $group = $this->user->groups()->whereId($groupId)
            ->whereDoesntHave('userSubjects.slots')
            ->first();
        if ($group) {
            $group->userSubjects()->delete();
            $group->delete();
            return true;
        }
        return false;
    }
}
