<section class="am-alianzas">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Título de la sección -->
                <div class="am-section_title am-section_title_center">
                    <h2>Alianzas</h2>
                </div>

                <!-- Carrusel de imágenes -->
                @if(!empty(pagesetting('alianzas_imagenes')))
                    <div id="alianzasCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach(pagesetting('alianzas_imagenes') as $index => $alianza)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <a href="{{ $alianza['enlace'] ?? '#' }}" target="_blank">
                                        <img src="{{ asset('storage/' . $alianza['imagen']) }}"
                                             class="d-block w-100"
                                             alt="{{ $alianza['titulo'] ?? 'Imagen de alianza' }}">
                                    </a>
                                    @if(!empty($alianza['titulo']))
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5>{{ $alianza['titulo'] }}</h5>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Controles del carrusel -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#alianzasCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#alianzasCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    </div>
                @else
                    <p>No hay imágenes de alianzas disponibles.</p>
                @endif
            </div>
        </div>
    </div>
</section>
