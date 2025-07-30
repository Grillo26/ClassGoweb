<div class="container-buscartutor">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Hero Section -->
    <section class="buscartutor-hero-section">
        <div class="buscartutor-container">
             <div class="buscartutor-hero-grid">
                <div>
                    <p class="buscartutor-hero-label">Tutores / Encontrar tutor</p>
                    <h1 class="buscartutor-hero-title">Descubra un tutor en línea capacitado para sus estudios</h1>
                    <p class="buscartutor-hero-desc">Domina tus estudios con tutorías personalizadas en línea impartidas por educadores expertos. Nuestros tutores capacitados están aquí para ayudarlo a construir bases sólidas y alcanzar sus objetivos académicos.</p>
                    <p class="mobile-only">
                        Nuestros tutores capacitados están aquí para ayudarlo a construir bases sólidas y alcanzar sus objetivos académicos.
                    </p>
                </div>
                <div class="buscartutor-hero-img-col">
                     <img src="{{ asset('storage/optionbuilder/uploads/740102-17-2025_0859pmTugo-saludando.gif') }}" alt="Mascota de ClassGo" class="buscartutor-hero-img" onerror="this.onerror=null; this.src='https://placehold.co/300x300/ffffff/023047?text=ClassGo';">
                </div>
            </div>
        </div>
    </section>
    
    <!-- Componente de búsqueda y listado de tutores -->
    <section class="buscartutor-search-section">
    <div class="buscartutor-search-box">
        <div class="buscartutor-search-grid">
            <div class="buscartutor-search-keyword">
                <div class="buscartutor-search-input-wrap">
                    <!-- BUSCADOR-->
                    <!--desktop-->
                    <div class="buscador-desktop">
                        <input type="text"
                        id="keyword-search"
                        placeholder="¿Qué necesitas aprender? Busca por nombre del tutor o materia."
                        class="buscartutor-search-input"
                        wire:model.live.debounce.500ms="search">
                        <span class="buscartutor-search-icon">
                            <svg class="buscartutor-search-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                        </span>
                    </div>
                    <!---movile-->
                    <div class="buscador-mobile">
                        <input type="text"
                        id="keyword-search"
                        placeholder="¿Qué necesitas aprender?"
                        class="buscartutor-search-input"
                        wire:model.live.debounce.500ms="search">
                        <span class="buscartutor-search-icon">
                            <svg class="buscartutor-search-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                        </span>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    </section>
    <section class="buscartutor-tutorlist-section">
        <div class="buscartutor-tutorlist-space">
            @forelse ($profiles as $profile)
                <div class="buscartutor-tutor-card">
                    <img 
                        src="{{ $profile['image'] ? asset('storage/' . $profile['image']) : asset('images/tutors/profile.jpg') }}" 
                        alt="Foto de {{ $profile['full_name'] }}" 
                        class="buscartutor-tutor-img">
                    <div class="buscartutor-tutor-info">
                        <h3 class="buscartutor-tutor-name">{{ $profile['full_name'] }}</h3>
                        <div class="buscartutor-tutor-meta">
                            <span>⭐ {{ $profile['avg_rating'] }}/5.0 ({{ $profile['total_reviews'] }} reseñas)</span>
                            <div class="desktop-only">
                                <span>•</span>
                                <span>1 Tutorías
                                </span>
                                <span>•</span>
                            </div>
                            
                            <div class="mobile-only">
                                <span>•</span>
                                <span>1 Tutorías
                                </span>
                                <span>•</span>
                            </div>
                            <span>Idioma: {{ $profile['native_language'] ?? 'N/A' }}</span>
                        </div>
                        <p class="buscartutor-tutor-desc">
                            {{ $profile['description'] }}
                        </p>
                    </div>
                    <div class="buscartutor-tutor-actions">
                        <button class="buscartutor-tutor-btn buscartutor-tutor-btn-orange">Reservar una sesión</button>
                        <a href="{{ route('tutor', ['slug' => $profile['slug']]) }}" class="buscartutor-tutor-btn buscartutor-tutor-btn-blue">
                            Ver Perfil
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">No se encontraron tutores para los criterios de búsqueda.</div>
            @endforelse
        </div>
        <div class="buscartutor-pagination">
            <style>
                .buscartutor-pagination nav {
                    display: flex;
                    justify-content: center;
                    margin-top: 2rem;
                }
                .buscartutor-pagination .pagination {
                    display: flex;
                    gap: 0.5rem;
                    list-style: none;
                    padding: 0;
                }
                .buscartutor-pagination .pagination li {
                    display: inline-block;
                }
                .buscartutor-pagination .pagination li a,
                .buscartutor-pagination .pagination li span {
                    padding: 0.5rem 1rem;
                    border-radius: 0.5rem;
                    border: 1px solid #023047;
                    color: #023047;
                    background: #fff;
                    font-weight: 600;
                    text-decoration: none;
                    transition: background 0.2s, color 0.2s;
                }
                .buscartutor-pagination .pagination li.active span,
                .buscartutor-pagination .pagination li span[aria-current="page"] {
                    background: #023047;
                    color: #fff;
                    border-color: #023047;
                }
                .buscartutor-pagination .pagination li a:hover {
                    background: #FB8500;
                    color: #fff;
                    border-color: #FB8500;
                }
                .buscartutor-pagination .pagination li.disabled span {
                    color: #aaa;
                    background: #f5f5f5;
                    border-color: #eee;
                }
            </style>
            {{ $profiles->links() }}
        </div>
    </section>
</div>