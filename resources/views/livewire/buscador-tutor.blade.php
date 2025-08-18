<div class="search-box">
    <i class="fa-solid fa-magnifying-glass icon-search"></i>

    <input type="text" class="form-control" placeholder="Buscar Tutor..." wire:model.live="search">

    @if(!empty($search))
        <ul>
            @forelse($results as $tutor)
                <li>
                    <a href=" {{ route('tutor', $tutor['slug']) }}"><strong>{{ $tutor['full_name'] }}</strong><br></a>
                    <small>{{ implode(', ', $tutor['materias']) }}</small>
                </li>
            @empty
                <li>No se encontraron tutores</li>
            @endforelse
        </ul>
    @endif
</div>

            
