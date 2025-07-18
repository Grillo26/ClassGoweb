<div class="am-modal am-fade" id="examModal" tabindex="-1" aria-labelledby="examModalLabel" aria-hidden="true">
        <div class="am-modal-dialog am-modal-lg">
            <div class="am-modal-content">
                <div class="am-modal-header">
                    <h5 class="am-modal-title" id="examModalLabel"> {{ $exam?->title }}</h5>
                    <button type="button" class="am-close" aria-label="Cerrar">&times;</button>
                </div>
                <div class="am-modal-body">
                    @if($exam && $exam->questions->count())
                    <form wire:submit.prevent="submitExam">
                        @foreach($exam->questions as $q)
                            @if($q->type === 'opcion_unica' && $q->options)
                                <div class="am-question-block">
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
                            </div>
                            @endif
                        @endforeach
                        <button type="submit" class="am-btnn am-btnn-success" wire:loading.attr="disabled">Enviar respuestas</button>
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