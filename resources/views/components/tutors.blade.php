@foreach($profiles as $profile) @php $data = $subjectsByUser[$profile->user_id] ?? ['materias' => [], 'grupos' => []]; @endphp
<div class="tutor-card">
	<div class="tutor-card-img">
		<video controls muted playsinline preload="none" poster="{{ $profile->image ? asset('storage/' . $profile->image) : asset('storage/' . $profile->image) }}" src="{{ $profile->intro_video ? asset('storage/' . $profile->intro_video) : asset('storage/' . $profile->image) }}"></video>
	</div>
	<div class="tutor-card-content">
		<div class="tutor-card-header">
			<div class="tutor-card-header-left">
				<h3>{{ $profile->first_name }} {{ $profile->last_name }}</h3>
				<span class="tutor-verified">✔️</span>
			</div>
			<button title="Favorito">❤️</button>
		</div>
		@php $maxGrupos = 4; $grupos = $data['grupos']; $countGrupos = count($grupos); @endphp
		<p class="tutor-card-sub mas" title="{{ implode(', ', $grupos) }}">
			{{ implode(', ', $grupos) }}<span class="tutor-card-mas" style="display:none;"> +más</span>
		</p>
		<div class="tutor-card-rating-row">
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
			<button class="btn-profile">Ver Perfil</button>
			<button class="btn-reserve">Reservar</button>
		</div>
	</div>
</div>
@endforeach