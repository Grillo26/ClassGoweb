<div>
    @php use Illuminate\Support\Str; @endphp
    @if($currentCourse)
    <div class="am-card">
        <div class="am-card-header">
            <h4>{{ $currentCourse->name }}</h4>
            <small>Instructor: {{ $currentCourse->instructor_name }}</small>
        </div>
        <div class="am-card-body">
            @if($currentCourse->video_url)
            @php
            $videoUrl = $currentCourse->video_url;
            if (Str::contains($videoUrl, 'youtube.com/watch?v=')) {
            $videoUrl = str_replace('watch?v=', 'embed/', $videoUrl);
            }
            @endphp
            <link rel="stylesheet" href="https://cdn.plyr.io/3.6.8/plyr.css" />
            <div class="am-video-container">
                <div class="plyr__video-embed" id="player">
                    <iframe src="{{ $videoUrl }}" allowfullscreen allowtransparency allow="autoplay"></iframe>
                </div>
            </div>
            <script src="https://cdn.plyr.io/3.6.8/plyr.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const player = new Plyr('#player');
                });
            </script>
            @else
            <p>No hay video disponible para este curso.</p>
            @endif
            {{-- <p>{{ $currentCourse->description }}</p> --}}
            @if($exam)
            <button class="am-btn am-btn-primary am-open-exam-modal">
                Ir al examen
            </button>
            @endif
        </div>
    </div>

    <!-- Modal Examen -->
    <div class="am-modal am-fade" id="examModal" tabindex="-1" aria-labelledby="examModalLabel" aria-hidden="true">
        <div class="am-modal-dialog am-modal-lg">
            <div class="am-modal-content">
                <div class="am-modal-header">
                    <h5 class="am-modal-title" id="examModalLabel">Examen: {{ $exam?->title }}</h5>
                    <button type="button" class="am-close" aria-label="Cerrar">&times;</button>
                </div>
                <div class="am-modal-body">
                    @if($exam && $exam->questions->count())
                    <form wire:submit.prevent="submitExam">
                        @foreach($exam->questions as $q)
                            @if($q->type === 'opcion_unica' && $q->options)
                                <div class="am-form-group">
                                    <label class="am-form-label">{{ $q->question }}</label>
                                    @php
                                        $options = $q->options;
                                        if (is_string($options)) {
                                            $options = json_decode($options, true);
                                        }
                                        if (!is_array($options)) {
                                            $options = [];
                                        }
                                    @endphp
                                    @foreach($options as $i => $option)
                                    <div class="am-form-check">
                                        <input class="am-form-check-input" type="radio" name="question_{{ $q->id }}" id="q{{ $q->id }}_{{ $i }}"
                                            value="{{ $i+1}}" wire:model="answers.{{ $q->id }}">
                                        <label class="am-form-check-label" for="q{{ $q->id }}_{{ $i }}">{{ $option }}</label>
                                    </div>
                                    @endforeach
                                    @error('answers.' . $q->id)
                                        <div class="am-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        @endforeach
                        <button type="submit" class="am-btn am-btn-success" wire:loading.attr="disabled">Enviar respuestas</button>
                        <div wire:loading class="am-form-loading">Enviando...</div>
                        @if (session()->has('exam_success'))
                            <div class="am-alert am-alert-success" x-data="{show: true}" x-init="setTimeout(() => show = false, 2000)" x-show="show">
                                {{ session('exam_success') }}
                            </div>
                        @endif
                        @if (session()->has('exam_error'))
                            <div class="am-alert am-alert-danger">{{ session('exam_error') }}</div>
                        @endif
                    </form>
                    @else
                    <p>No hay preguntas para este examen.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="am-alert am-alert-info">No tienes cursos pendientes o en progreso.</div>
    @endif

    <script>
        // Modal JS puro para abrir/cerrar
        document.addEventListener('DOMContentLoaded', function() {
            const modalBtn = document.querySelector('.am-open-exam-modal');
            const modal = document.getElementById('examModal');
            const closeBtn = modal.querySelector('.am-close');
            if(modalBtn && modal && closeBtn) {
                modalBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    modal.classList.add('am-show');
                });
                closeBtn.addEventListener('click', function() {
                    modal.classList.remove('am-show');
                });
            }
        });
        // Cerrar modal desde Livewire
        window.addEventListener('close-exam-modal', function() {
            const modal = document.getElementById('examModal');
            if(modal) {
                modal.classList.remove('am-show');
            }
        });
    </script>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/livewire/pages/tutor/company-courses/courses.css') }}">
@endpush