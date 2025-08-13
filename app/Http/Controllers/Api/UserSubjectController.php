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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class UserSubjectController extends Controller
{
    use ApiResponser;

    protected $subjectService;

    public function __construct()
    {
        // No inicializar SubjectService aquí para evitar errores con usuarios no autenticados
    }

    /**
     * Obtener todas las materias del tutor autenticado
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Consulta simple sin relaciones para depurar
        $query = UserSubject::query();

        // Filtrar por user_id si se especifica
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Agregar logs para depuración
        Log::info('UserSubject Query:', [
            'user_id' => $request->get('user_id'),
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        $userSubjects = $query->get();

        // Log del resultado
        Log::info('UserSubjects Result:', [
            'count' => $userSubjects->count(),
            'data' => $userSubjects->toArray()
        ]);

        // Si no hay resultados, devolver información de depuración
        if ($userSubjects->isEmpty()) {
            return $this->success(
                data: [],
                message: 'No se encontraron materias. Debug info: user_id=' . $request->get('user_id') . ', total records=' . UserSubject::count()
            );
        }

        // Cargar la relación después de obtener los datos
        $userSubjects->load(['subject' => function($query) {
            $query->select('id', 'name', 'subject_group_id');
        }]);

        $userSubjects = $userSubjects->map(function($userSubject) {
            return [
                'id' => $userSubject->id,
                'user_id' => $userSubject->user_id,
                'subject_id' => $userSubject->subject_id,
                'description' => $userSubject->description,
                'image' => $userSubject->image,
                'status' => $userSubject->status,
                'subject' => $userSubject->subject ? [
                    'id' => $userSubject->subject->id,
                    'name' => $userSubject->subject->name,
                    'subject_group_id' => $userSubject->subject->subject_group_id
                ] : null
            ];
        });

        return $this->success(
            data: $userSubjects,
            message: 'Materias obtenidas exitosamente'
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
        $userSubject = UserSubject::where('id', $id)
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
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072', // 3MB max
        ]);

        // Verificar que la materia no esté ya asignada al usuario
        $existingSubject = UserSubject::where('user_id', $validated['user_id'])
            ->where('subject_id', $validated['subject_id'])
            ->first();

        if ($existingSubject) {
            return $this->error(
                data: null,
                message: 'El usuario ya tiene esta materia asignada',
                code: Response::HTTP_CONFLICT
            );
        }

        // Procesar imagen si se proporciona
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('subjects', 'public');
        }

        $userSubjectData = [
            'user_id' => $validated['user_id'],
            'subject_id' => $validated['subject_id'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'status' => 'active'
        ];

        $userSubject = UserSubject::create($userSubjectData);

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
        $userSubject = UserSubject::where('id', $id)->first();

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
        $userSubject = UserSubject::where('id', $id)->first();

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
        $groupId = $request->get('group_id');
        $keyword = $request->get('keyword');
        $userId = $request->get('user_id'); // Nuevo parámetro para especificar usuario

        $query = Subject::where('status', 'active');

        // Filtrar por grupo si se especifica
        if ($groupId) {
            $query->where('subject_group_id', $groupId);
        }

        // Filtrar por palabra clave si se especifica
        if ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        // Excluir materias que ya tiene el usuario (si se especifica user_id)
        if ($userId) {
            $userSubjectIds = UserSubject::where('user_id', $userId)
                ->pluck('subject_id')
                ->toArray();

            if (!empty($userSubjectIds)) {
                $query->whereNotIn('id', $userSubjectIds);
            }
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

    /**
     * Eliminar materia del tutor (eliminar relación user_subject)
     *
     * @param int $tutor_id
     * @param int $subject_id
     * @return \Illuminate\Http\Response
     */
    public function removeTutorSubject($tutor_id, $subject_id)
    {
        // Buscar la relación específica entre el tutor y la materia
        $userSubject = UserSubject::where('user_id', $tutor_id)
            ->where('subject_id', $subject_id)
            ->first();

        if (!$userSubject) {
            return $this->error(
                data: null,
                message: 'La materia no está asignada a este tutor',
                code: Response::HTTP_NOT_FOUND
            );
        }

        // Eliminar imagen si existe
        if ($userSubject->image && Storage::disk('public')->exists($userSubject->image)) {
            Storage::disk('public')->delete($userSubject->image);
        }

        // Eliminar la relación
        $userSubject->delete();

        return $this->success(
            data: null,
            message: 'Materia eliminada del tutor exitosamente'
        );
    }
} 