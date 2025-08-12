{{-- CARD DEL TUTOR --}} 
@foreach($profiles as $profile) @php $data = $subjectsByUser[$profile->user_id] ?? ['materias' => [], 'grupos' => []]; @endphp
<div class="tutor-card carousel-card">
	<div class="tutor-card-img">
		<video
			class="tutor-intro-video"
			muted
			playsinline
			preload="none"
			poster="{{ asset('images/classgo/banner1.png') }}"
			src="{{ $profile->intro_video ? asset('storage/' . $profile->intro_video) : asset('storage/' . $profile->image) }}">
		</video>

		<div class="tutor-banner-overlay" id="tutor-banner-overlay">
			<button id="tutor-banner-play" class="tutor-banner-play">
				<svg class="tutor-banner-play-icon" viewBox="0 0 24 24">
					<polygon points="5 3 19 12 5 21 5 3"></polygon>
				</svg>
			</button>
		</div>

		<div class="tutor-video-controls" id="tutor-video-controls" style="display: none;">
			<button id="tutor-banner-pause" class="tutor-control-button">
				<svg class="tutor-control-icon" viewBox="0 0 24 24">
					<rect x="6" y="4" width="4" height="16"></rect>
					<rect x="14" y="4" width="4" height="16"></rect>
				</svg>
			</button>
			<input id="tutor-banner-volume" type="range" min="0" max="1" step="0.01" value="0.5" class="tutor-control-volume">
		</div>
	</div>
	<div class="tutor-card-content">
		<div class="tutor-card-header">
			<div class="tutor-card-header-left">
				<!-----------------Verifica Imagen por defecto------------------->
               
                <!-------------------------------------------------------------->
                    <img src="{{ asset('images/tutors/default.png') }}" alt="Foto de {{ $tutor->profile->first_name ?? '' }}" class="tutor-profile-img" style="background-color: white">
				<h3>{{ $profile->first_name }} {{ $profile->last_name }}</h3>
				<span class="tutor-verified">✔️</span>
			</div>
			{{-- <button title="Favorito">❤️</button> --}}
		</div>
		@php $maxGrupos = 4; $grupos = $data['grupos']; $countGrupos = count($grupos); @endphp
		<p class="tutor-card-sub mas" title="{{ implode(', ', $grupos) }}">
			Puedo enseñar: {{ implode(', ', $grupos) }}<span class="tutor-card-mas" style="display:none;"> +más</span>
		</p>
		<div class="tutor-card-rating-row desktop">
			<div class="tutor-card-rating">
				<span class="star">⭐</span>
					<span>{{ $profile->avg_rating}}</span>
					<span class="rating-count">( {{ $profile->total_reviews}} reseñas)</span>
			</div>
			<div class="tutor-card-price">
				<p class="price"><i class="fa-solid fa-book icon"></i>10</p>
				<p class="price-desc">Tutorías realizadas</p>
			</div>
		</div>
		{{--
		<div class="tutor-card-tags" title="{{ implode(', ', $data['materias']) }}">
			@foreach($data['materias'] as $materia)
			<span class="tutor-card-tag">{{ $materia }}</span> @endforeach
			<span class="tutor-card-tag tutor-card-mas" style="display:none;">+más</span>
		</div> --}}
		<div class="tutor-card-actions">
			<a href="{{ route('tutor', parameters: ['slug' => $profile->slug]) }}" ><button class="btn-profile">Ver Perfil</button></a>

			<button class="btn-reserve">Reservar</button>
		</div>

	</div>
</div>
@endforeach

  
<div class="tutor-card">
	<div class="mas-tutor-card">
		<div class="numero-paso">
			<i class="fa-solid fa-book"></i>
		</div>
		<h1>Explora más tutores</h1>
		<p>Comienza tu viaje educativo con nosotros. ¡Encuentra un tutor y reserva tu primera sesión hoy mismo!</p>
		<a href="{{ route('buscar.tutor')}}"><button class="button-go">Buscar Tutor</button></a>
	</div>
</div>

<script>
	/*========= CONTROLES DE VIDEO ============*/
	document.addEventListener('DOMContentLoaded', () => {
		const videoCards = document.querySelectorAll('.tutor-card-img');

		videoCards.forEach(card => {
			const video = card.querySelector('.tutor-intro-video');
			const overlay = card.querySelector('.tutor-banner-overlay');
			const playButton = card.querySelector('#tutor-banner-play');
			const videoControls = card.querySelector('#tutor-video-controls');
			const pauseButton = card.querySelector('#tutor-banner-pause');
			const volumeControl = card.querySelector('#tutor-banner-volume');

			// Lógica principal de Play/Pause con la superposición
			overlay.addEventListener('click', () => {
				video.play();
			});

			// Lógica del botón de pausa
			pauseButton.addEventListener('click', (e) => {
				e.stopPropagation(); // Evita que el evento se propague al overlay
				video.pause();
			});

			// Lógica para el control de volumen
			volumeControl.addEventListener('input', () => {
				video.volume = volumeControl.value;
			});

			// Eventos del video para controlar la UI
			video.addEventListener('play', () => {
				overlay.style.display = 'none';
				videoControls.style.display = 'flex';
			});

			video.addEventListener('pause', () => {
				overlay.style.display = 'flex';
				videoControls.style.display = 'none';
			});

			video.addEventListener('ended', () => {
				overlay.style.display = 'flex';
				videoControls.style.display = 'none';
				video.currentTime = 0; // Opcional: reiniciar el video
			});

			// Sincronizar el volumen inicial
			video.volume = volumeControl.value;
		});
	});
</script>
