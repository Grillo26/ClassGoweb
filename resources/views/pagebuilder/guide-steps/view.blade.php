@php
use App\Models\Alianza;
$alianzas = Alianza::where('activo', true)->orderBy('orden')->get();
@endphp

@if($alianzas->count() > 0)
    <div class="am-alianzas">
        <div class="alianzas-wrapper">
            <h2 class="alianzas-title">Alianzas</h2>
            <div class="alianzas-carousel">
                @foreach($alianzas as $index => $alianza)
                    <div class="alianzas-slide {{ $index == 0 ? 'active' : '' }}">
                        <a href="{{ $alianza->enlace ?? '#' }}" target="_blank">
                            <img src="{{ $alianza->imagen_url }}" alt="{{ $alianza->titulo }}">
                        </a>
                        @if($alianza->titulo)
                            <h5>{{ $alianza->titulo }}</h5>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

@pushOnce('styles')
<style>
.am-alianzas {
    background-color: #023047;
    padding: 60px 0;
    width: 100%;
    display: flex;
    justify-content: center;
}

.alianzas-wrapper {
    width: 100%;
    max-width: 1200px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.alianzas-title {
    color: white;
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 30px;
    text-align: center;
}

.alianzas-carousel {
    width: 100%;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 30px;
}

.alianzas-slide {
    background-color: #023047CC;
    padding: 25px 20px;
    border-radius: 16px;
    max-width: 280px;
    text-align: center;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    transition: transform 0.3s, box-shadow 0.3s;
}

.alianzas-slide:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
}

.alianzas-slide img {
    max-width: 100%;
    max-height: 400px;
    object-fit: contain;
    display: block;
    margin: 0 auto 15px;
}

.alianzas-slide h5 {
    font-size: 1.05rem;
    color: white;
    margin: 0;
}
</style>

@vite(['public/css/venobox.min.css'])
@endpushOnce


