<div>
    <main class="tb-main tb-subject-wrap">
        <div class="container-fluid py-4">
            <div class="row">
                <!-- Columna izquierda - Formulario -->
                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            @if($editMode)
                            <h5>{{ __('taxonomy.update_subject') }}</h5>
                            @else
                            <h5>{{ __('taxonomy.add_subject') }}</h5>
                            @endif
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="save">
                                <div class="mb-3">
                                    <label class="form-label">Nombre del curso</label>
                                    <input type="text" class="form-control" wire:model.defer="name">
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Instructor</label>
                                    <input type="text" class="form-control" wire:model.defer="instructor_name">
                                    @error('instructor_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Descripcion</label>
                                    <input type="text" class="form-control" wire:model.defer="description">
                                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Video</label>
                                    <input type="file" class="form-control" wire:model="video_file" accept="video/*">
                                    @error('video_file') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    {{ $editMode ? 'Actualizar' : 'Crear' }}
                                </button>
                                @if($editMode)
                                <button type="button" class="btn btn-secondary ms-2"
                                    wire:click="resetForm">Cancelar</button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha - Tabla -->
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Lista de Cursos</h5>
                        </div>
                        <div class="card-body p-0">
                            <!-- Eliminé un div table-responsive duplicado -->
                            <div class="table-responsive" style="overflow-x: auto;">
                                <table class="table table-striped align-middle mb-0"
                                    style="table-layout: fixed; width: 100%;">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 5%" class="text-nowrap">ID</th>
                                            <th style="width: 15%" class="text-nowrap">Nombre</th>
                                            <th style="width: 15%" class="text-nowrap">Instructor</th>
                                            <th style="width: 30%" class="text-nowrap">Descripción</th>
                                            <th style="width: 15%" class="text-nowrap">Video</th>
                                            <th style="width: 20%" class="text-nowrap">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($courses as $course)
                                        <tr>
                                            <td class="text-truncate" style="max-width: 60px;">{{ $course->id }}</td>
                                            <td class="text-truncate" style="max-width: 150px;">{{ $course->name }}</td>
                                            <td class="text-truncate" style="max-width: 150px;">{{
                                                $course->instructor_name }}</td>
                                            <td class="text-truncate" style="max-width: 250px;">{{ $course->description
                                                }}</td>
                                            <td class="text-truncate" style="max-width: 120px;">
                                                @if($course->video_url)
                                                <a href="{{ Storage::url($course->video_url) }}" target="_blank"
                                                    class="btn btn-outline-primary btn-sm">Ver video</a>
                                                @else
                                                <span class="text-muted">Sin video</span>
                                                @endif
                                            </td>
                                            <td class="text-truncate" style="max-width: 180px;">
                                                <div class="d-flex gap-2 flex-wrap">
                                                    <button class="btn btn-warning btn-sm"
                                                        wire:click="edit({{ $course->id }})">Editar</button>
                                                    <button class="btn btn-danger btn-sm"
                                                        wire:click="delete({{ $course->id }})"
                                                        onclick="return confirm('¿Seguro que deseas eliminar este curso?')">Eliminar</button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">No hay cursos registrados.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                            </div>
                            <div class="p-3 border-top">
                                {{ $courses->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>