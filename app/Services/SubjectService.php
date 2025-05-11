<?php

namespace App\Services;

use App\Models\Subject;
use App\Models\SubjectGroup;
use App\Models\UserSubject;
use App\Models\UserSubjectGroup;

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
        $query = UserSubject::select('id', 'user_id', 'subject_id');

        if ($this->user->role === 'tutor') {
            $query->where('user_id', $this->user->id);
        } elseif ($this->user->role === 'student') {
            // $query->whereHas('slots', function ($query) {
            //     $query->select('id');
            //     $query->whereHas('bookings', function ($query) {
            //         $query->select('id');
            //         $query->whereStudentId($this->user->id);
            //     });
            // });
        }

        $subjects = $query->with(['subject' => function($q) {
            $q->select('id', 'name', 'subject_group_id');
        }])->get()
            ->groupBy('subject.subject_group_id')
            ->map(function ($group) {
                return [
                    'group_name' => $group->first()->subject->group ? $group->first()->subject->group->name : null,
                    'subjects' => $group->map(function ($item) {
                        return [
                            'id'           => $item->id,
                            'subject_name' => $item->subject ? $item->subject->name : null,
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
    public function getSubjects($userId, $groupId = null)
    {
        $query = UserSubject::select('id', 'user_id', 'subject_id');
        if ($groupId) {
            $query->whereHas('subject', function($q) use ($groupId) {
                $q->where('subject_group_id', $groupId);
            });
        }
        return $query->get();
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
        foreach ($groupIds as $group) {
            $groupModel = $groups->firstWhere('id', $group['id']);
            if ($groupModel) {
                $groupModel->update($group);
                $data[] = $groupModel;
            } else {
                $data[] = $this->user->groups()->create($group);
            }
        }

        $groupIds = $data;
        return $data;
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
            foreach ($subjectGroup['subject_id'] as $subj) {
                UserSubject::updateOrCreate(['user_id' => $this->user->id, 'subject_id' => $subj]);
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
        $groupSubjects = $this->user->groups()->whereSubjectGroupId($subjectGroupId)->first();
        if ($groupSubjects) {
            $returnData['subject_group_id'] = $subjectGroupId;
            $returnData['group'] = $groupSubjects->group->name;
            $subjects = UserSubject::where('user_id', $this->user->id)
                ->whereHas('subject', function($q) use ($subjectGroupId) {
                    $q->where('subject_group_id', $subjectGroupId);
                })->get();
            $returnData['subject_id'] = $subjects->pluck('subject_id')->toArray();
        }
        return $returnData;
    }

    /**
     * obtener la materia del grupo
     * 
     * @param int $pivotId
     * @return UserSubject
     */
    public function getUserGroupSubject($pivotId): UserSubject
    {
        return UserSubject::whereId($pivotId)->first();
    }


    /**
     * establecer la materia del grupo
     * 
     * @param int $id
     * @param array $subject
     * @return UserSubjectGroupSubject
     */
    public function setUserSubject($id, $subject)
    {
        // Buscar el registro de UserSubject por id y user_id
        $userSubject = UserSubject::where('id', $id)
            ->where('user_id', $this->user->id)
            ->first();

        if ($userSubject) {
            $userSubject->update($subject);
            return $userSubject;
        } else {
            return UserSubject::create(array_merge($subject, ['user_id' => $this->user->id]));
        }
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
        // Eliminar el UserSubject por id y user_id
        $userSubject = UserSubject::where('id', $userSubjectId)
            ->where('user_id', $this->user->id)
            ->first();

        if ($userSubject) {
            return $userSubject->delete();
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
                UserSubject::find($subject['value'])->update(['sort_order' => $subject['order']]);
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
        UserSubject::whereId($pivotId)->delete();
    }

    /**
     * eliminar el grupo de materias
     * 
     * @param int $groupId
     * @return bool
     */
    public function deleteUserSubjectGroup($groupId): bool
    {
        // Eliminar todos los UserSubject del grupo para este usuario
        $userSubjects = UserSubject::where('user_id', $this->user->id)
            ->whereHas('subject', function($q) use ($groupId) {
                $q->where('subject_group_id', $groupId);
            })->get();

        foreach ($userSubjects as $userSubject) {
            $userSubject->delete();
        }

        // Eliminar el grupo de usuario
        $group = $this->user->groups()->whereId($groupId)->first();
        if ($group) {
            $group->delete();
            return true;
        }
        return false;
    }

    public function setSubjectGroupSubjects($groupId, $subjects)
    {
        $group = $this->user->groups()->whereId($groupId)->first();
        if ($group) {
            foreach ($subjects as $subj) {
                UserSubject::updateOrCreate(['user_id' => $this->user->id, 'subject_id' => $subj]);
            }
        }
    }

    public function setSubjectGroupSubjectsOrder($subjects)
    {
        foreach ($subjects as $subject) {
            UserSubject::find($subject['value'])->update(['sort_order' => $subject['order']]);
        }
    }

    public function deleteSubjectGroupSubject($pivotId)
    {
        UserSubject::whereId($pivotId)->delete();
    }
}
