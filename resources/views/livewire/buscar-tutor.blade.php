<div>
    <!-- Buscador y filtro -->
    <section class="buscartutor-search-section">
        <div class="buscartutor-search-box">
            <div class="buscartutor-search-grid">
                <div class="buscartutor-search-keyword">
                    <label for="keyword-search" class="sr-only">Buscar por palabra clave</label>
                    <div class="buscartutor-search-input-wrap">
                        <input type="text" id="keyword-search" placeholder="Buscar por nombre, apellido o materia" class="buscartutor-search-input" wire:model.debounce.500ms="search">
                        <span class="buscartutor-search-icon">
                            <svg class="buscartutor-search-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                        </span>
                    </div>
                </div>
                <div class="buscartutor-search-group">
                    <label for="group-select" class="sr-only">Grupo de materias</label>
                    <select id="group-select" class="buscartutor-search-select" wire:model="materia">
                        <option value="">Elige materia</option>
                        @foreach($materias as $mat)
                            <option value="{{ $mat->id }}">{{ $mat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="buscartutor-search-btn" wire:click.prevent>Buscar</button>
            </div>
        </div>
    </section>

    <!-- Lista de tutores -->
    <section class="buscartutor-tutorlist-section">
        <div class="buscartutor-tutorlist-space">
            @forelse($tutores as $tutor)
                <div class="buscartutor-tutor-card">
                    <img src="{{ $tutor->profile->image ? asset('storage/' . $tutor->profile->image) : 'https://placehold.co/128x128/8ECAE6/023047?text=CG' }}" alt="Foto de {{ $tutor->profile->first_name }}" class="buscartutor-tutor-img">
                    <div class="buscartutor-tutor-info">
                        <h3 class="buscartutor-tutor-name">{{ $tutor->profile->first_name }} {{ $tutor->profile->last_name }}</h3>
                        <div class="buscartutor-tutor-meta">
                            <span>{{ number_format($tutor->avg_rating ?? 0, 1) }}/5.0 ({{ $tutor->total_reviews ?? 0 }} reseñas)</span>
                            <span>•</span>
                            <span>{{ $tutor->userSubjects->count() }} Materias</span>
                            <span>•</span>
                            <span>Idiomas: {{ $tutor->languages->pluck('name')->join(', ') }}</span>
                        </div>
                        <p class="buscartutor-tutor-desc">{{ $tutor->profile->description }}</p>
                    </div>
                    <div class="buscartutor-tutor-actions">
                        <a href="{{ route('tutor', $tutor->profile->slug) }}" class="buscartutor-tutor-btn buscartutor-tutor-btn-orange">Reservar una sesión</a>
                        <a href="mailto:{{ $tutor->email }}" class="buscartutor-tutor-btn buscartutor-tutor-btn-blue">Enviar mensaje</a>
                    </div>
                </div>
            @empty
                <div class="buscartutor-tutor-card" style="text-align:center;">No se encontraron tutores.</div>
            @endforelse
        </div>
        <div class="buscartutor-pagination">
            {{ $tutores->links() }}
        </div>
    </section>
</div> 