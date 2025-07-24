<div class="am-filtros-card">
            <div class="filtros">
                <!-- Búsqueda -->
                <div class="am-search">
                    <div class="am-search-input">
                        <input type="text" wire:model.live.debounce.300ms="searchQuery"
                            placeholder="{{ __('general.search') }}...">
                        <i class="am-icon-search"></i>
                    </div>
                </div>

                <!-- Filtro de grupos con materias -->
                <div class="am-filter-checkbox">
                    <label>
                        <input type="checkbox" wire:model.live="showOnlyWithSubjects">
                        <span>{{ __('subject.show_only_with_subjects') }}</span>
                    </label>
                </div>

                <!-- Botón reset -->
                <button wire:click="resetFilters" class="am-btn am-btn-reset">
                    <i class="am-icon-refresh"></i>
                    {{ __('general.reset_filters') }}
                </button>
            </div>
        </div>