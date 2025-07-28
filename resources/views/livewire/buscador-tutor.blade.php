
            <div class="search-box" style="position: relative;">
                <input type="text" class="form-control" placeholder="Buscar Tutor..." wire:model.live.debounce.300ms="search">

                @if(!empty($search))
                    <ul style="background: white; width: 100%; border: 1px solid #ccc; z-index: 10;">
                        @forelse($results as $tutor)
                            <li style="padding: 8px;">
                                <strong>{{ $tutor['full_name'] }}</strong><br>
                                <small>{{ implode(', ', $tutor['materias']) }}</small>
                            </li>
                        @empty
                            <li style="padding: 8px;">No se encontraron tutores</li>
                        @endforelse
                    </ul>
                @endif
                <i class="fa-solid fa-magnifying-glass icon-search"></i>
            </div>


            
