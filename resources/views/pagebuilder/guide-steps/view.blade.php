
    @if(!empty(pagesetting('alianzas')))
        <div class="am-alianzas">
            <div class="alianzas-wrapper">
                <h2 class="alianzas-title">Alianzas</h2>
                <div class="alianzas-carousel">
                    @foreach(pagesetting('alianzas') as $index => $alianza)
                        @php
                            $imgPath = '';
                            if (!empty($alianza['imagen'])) {
                                if (is_array($alianza['imagen']) && isset($alianza['imagen'][0]['path'])) {
                                    $imgPath = $alianza['imagen'][0]['path'];
                                } elseif (is_array($alianza['imagen']) && isset($alianza['imagen']['path'])) {
                                    $imgPath = $alianza['imagen']['path'];
                                } elseif (is_string($alianza['imagen'])) {
                                    $imgPath = $alianza['imagen'];
                                }
                            }
                        @endphp

                        @if($imgPath)
                            <div class="alianzas-slide {{ $index == 0 ? 'active' : '' }}">
                                <a href="{{ $alianza['enlace'] ?? '#' }}" target="_blank">
                                    <img src="{{ asset('storage/' . $imgPath) }}" alt="{{ $alianza['titulo'] ?? 'Imagen de alianza' }}">
                                </a>
                                @if(!empty($alianza['titulo']))
                                    <h5>{{ $alianza['titulo'] }}</h5>
                                @endif
                            </div>
                        @endif
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


