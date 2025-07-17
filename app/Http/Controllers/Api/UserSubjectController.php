<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserSubject;
use App\Models\Subject;
use App\Models\SubjectGroup;
use App\Services\SubjectService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class UserSubjectController extends Controller
{
    use ApiResponser;

    protected $subjectService;

    public function __construct()
    {
        $this->subjectService = new SubjectService(Auth::user());
    }

    /**
     * Obtener todas las materias del tutor autenticado
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role !== 'tutor') {
            return $this->error(
                data: null,
                message: 'Solo los tutores pueden acceder a esta funcionalidad',
                code: Response::HTTP_FORBIDDEN
            );
        }

        $userSubjects = $this->subjectService->getUserSubjectsWithSubjects(Auth::id());

        return $this->success(
            data: $userSubjects,
            message: 'Materias del tutor obtenidas exitosamente'
        );
    }

    /**
     * Obtener una materia específica del tutor
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()->role !== 'tutor') {
            return $this->error(
                data: null,
                message: 'Solo los tutores pueden acceder a esta funcionalidad',
                code: Response::HTTP_FORBIDDEN
            );
        }

        $userSubject = UserSubject::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['subject' => function($query) {
                $query->select('id', 'name', 'subject_group_id');
            }])
            ->first();

        if (!$userSubject) {
            return $this->error(
                data: null,
                message: 'Materia no encontrada',
                code: Response::HTTP_NOT_FOUND
            );
        }

        return $this->success(
            data: $userSubject,
            message: 'Materia obtenida exitosamente'
        );
    }

    /**
     * Agregar una nueva materia al tutor
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'tutor') {
            return $this->error(
                data: null,
                message: 'Solo los tutores pueden agregar materias',
                code: Response::HTTP_FORBIDDEN
            );
        }

        $validated = $request->validate([
            'subject_id' => 'required|integer|exists:subjects,id',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072', // 3MB max
        ]);

        // Verificar que la materia no esté ya asignada al tutor
        $existingSubject = UserSubject::where('user_id', Auth::id())
            ->where('subject_id', $validated['subject_id'])
            ->first();

        if ($existingSubject) {
            return $this->error(
                data: null,
                message: 'Ya tienes esta materia asignada',
                code: Response::HTTP_CONFLICT
            );
        }

        // Procesar imagen si se proporciona
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('subjects', 'public');
        }

        $userSubjectData = [
            'user_id' => Auth::id(),
            'subject_id' => $validated['subject_id'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'status' => 'active'
        ];

        $userSubject = $this->subjectService->saveUserSubject($userSubjectData);

        // Cargar la relación con la materia para la respuesta
        $userSubject->load(['subject' => function($query) {
            $query->select('id', 'name', 'subject_group_id');
        }]);

        return $this->success(
            data: $userSubject,
            message: 'Materia agregada exitosamente',
            code: Response::HTTP_CREATED
        );
    }

    /**
     * Actualizar una materia del tutor
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'tutor') {
            return $this->error(
                data: null,
                message: 'Solo los tutores pueden actualizar materias',
                code: Response::HTTP_FORBIDDEN
            );
        }

        $userSubject = UserSubject::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$userSubject) {
            return $this->error(
                data: null,
                message: 'Materia no encontrada',
                code: Response::HTTP_NOT_FOUND
            );
        }

        $validated = $request->validate([
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072', // 3MB max
            'status' => 'nullable|in:active,inactive'
        ]);

        // Procesar nueva imagen si se proporciona
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($userSubject->image && Storage::disk('public')->exists($userSubject->image)) {
                Storage::disk('public')->delete($userSubject->image);
            }
            
            $imagePath = $request->file('image')->store('subjects', 'public');
            $validated['image'] = $imagePath;
        }

        $userSubject->update($validated);

        // Cargar la relación con la materia para la respuesta
        $userSubject->load(['subject' => function($query) {
            $query->select('id', 'name', 'subject_group_id');
        }]);

        return $this->success(
            data: $userSubject,
            message: 'Materia actualizada exitosamente'
        );
    }

    /**
     * Eliminar una materia del tutor
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'tutor') {
            return $this->error(
                data: null,
                message: 'Solo los tutores pueden eliminar materias',
                code: Response::HTTP_FORBIDDEN
            );
        }

        $userSubject = UserSubject::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$userSubject) {
            return $this->error(
                data: null,
                message: 'Materia no encontrada',
                code: Response::HTTP_NOT_FOUND
            );
        }

        // Eliminar imagen si existe
        if ($userSubject->image && Storage::disk('public')->exists($userSubject->image)) {
            Storage::disk('public')->delete($userSubject->image);
        }

        $userSubject->delete();

        return $this->success(
            data: null,
            message: 'Materia eliminada exitosamente'
        );
    }

    /**
     * Obtener grupos de materias disponibles
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubjectGroups()
    {
        $subjectGroups = SubjectGroup::select('id', 'name')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return $this->success(
            data: $subjectGroups,
            message: 'Grupos de materias obtenidos exitosamente'
        );
    }

    /**
     * Obtener materias por grupo
     *
     * @param int $groupId
     * @return \Illuminate\Http\Response
     */
    public function getSubjectsByGroup($groupId)
    {
        $subjects = Subject::where('subject_group_id', $groupId)
            ->where('status', 'active')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return $this->success(
            data: $subjects,
            message: 'Materias del grupo obtenidas exitosamente'
        );
    }

    /**
     * Obtener materias disponibles para el tutor (excluyendo las que ya tiene)
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getAvailableSubjects(Request $request)
    {
        if (Auth::user()->role !== 'tutor') {
            return $this->error(
                data: null,
                message: 'Solo los tutores pueden acceder a esta funcionalidad',
                code: Response::HTTP_FORBIDDEN
            );
        }

        $groupId = $request->get('group_id');
        $keyword = $request->get('keyword');

        $query = Subject::where('status', 'active');

        // Filtrar por grupo si se especifica
        if ($groupId) {
            $query->where('subject_group_id', $groupId);
        }

        // Filtrar por palabra clave si se especifica
        if ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        // Excluir materias que ya tiene el tutor
        $userSubjectIds = UserSubject::where('user_id', Auth::id())
            ->pluck('subject_id')
            ->toArray();

        if (!empty($userSubjectIds)) {
            $query->whereNotIn('id', $userSubjectIds);
        }

        $subjects = $query->select('id', 'name', 'subject_group_id')
            ->with(['group' => function($query) {
                $query->select('id', 'name');
            }])
            ->orderBy('name')
            ->paginate($request->get('per_page', 20));

        return $this->success(
            data: $subjects,
            message: 'Materias disponibles obtenidas exitosamente'
        );
    }
} 