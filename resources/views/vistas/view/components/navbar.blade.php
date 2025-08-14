<header class="navbar">
	<div class="navbar-container">
		<div class="navbar-left">
			<!-- Logo -->
			<a href="{{ route('home') }}">
				<img src="{{ asset('/images/home/logoclassgo.png') }}" class="nav-i" alt="Mascota">
			</a>	

			<!-- Enlaces Desktop -->
			<nav class="navbar-links">
				<ul>
					<li><a href="{{ route('buscar.tutor')}}" class="{{ (request()->is('tutores*') || request()->is('tutors*') || request()->routeIs('buscar.tutor')) ? 'active' : '' }}">Tutores</a></li>					<li><a href="{{ route('nosotros')}}" class="{{ request()->is('nosotros*') ? 'active' : '' }}">Nosotros</a></li>
					<li><a href="{{ route('como-trabajamos')}}" class="{{ request()->is('como-trabajamos*') ? 'active' : '' }}">Cómo trabajamos</a></li>
					<li><a href="{{ route('preguntas')}}" class="{{ request()->is('preguntas*') ? 'active' : '' }}">Preguntas</a></li>
					{{-- <li><a href="#" class="{{ request()->is('blog*') ? 'active' : '' }}">Blog</a></li> --}}
				</ul>
			</nav>
		</div>

		<!-- Lado derecho -->
		<div class="navbar-actions">
			<div class="language-select">
				<div class="selected-option" onclick="toggleDropdown()">
					<img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/svg/1f1ea-1f1f8.svg" alt="Español">
					<span>Español</span>
				</div>
				<ul class="options-dropdown" id="languageDropdown">
					<li onclick="selectLanguage('es')">
						<img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/svg/1f1ea-1f1f8.svg" alt="Español">
					Español
					</li>
					<li onclick="selectLanguage('en')">
						<img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/svg/1f1ec-1f1e7.svg" alt="English">
					English
					</li>
					<li onclick="selectLanguage('pt')">
						<img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/svg/1f1f5-1f1f9.svg" alt="English">
					Português
					</li>
				</ul>
			</div>

			<!-- Verifica si está logueado, caso contrario se muestran los botones -->
			@auth

			<div class="user-menu">
				<button type="button" class="user-menu__trigger">
					@role('tutor')
						<img class="user-menu__avatar" 
							src="{{ Auth::user()->profile->image ? asset('storage/'.Auth::user()->profile->image) : asset('images/default.png') }}" 
							style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; cursor: pointer;">
					@elserole('student')
						<img class="user-menu__avatar" 
							src="{{ Auth::user()->profile->image ? asset('storage/'.Auth::user()->profile->image) : asset('images/tutors/default_estudiante.png') }}" 
							style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; cursor: pointer;">
					@endrole
				</button>

				<div class="user-menu__dropdown">
						<!-- Según rol-->
						<a href=" {{ auth()->user()->hasRole('tutor') ? route('tutor.dashboard') :  route('student.bookings') }}">
							
							<div class="user-menu__header">
								{{-- <img class="user-menu__avatar" src="{{ asset('storage/'.Auth::user()->profile->image) ?? asset('images/default.png') }}" > --}}
								<div class="user-menu__details">
									<span class="user-menu__name">Hola {{ Auth::user()->profile->first_name}}!</span>
									<span class="user-menu__email">{{ Auth::user()->email }}</span>
								</div>
							</div>
						</a>
					@role('tutor') <!-- ======== ROL TUTOR ===========-->
						<ul class="user-menu__nav">
							<li>
								<a href="{{ route('tutor.dashboard')}}" class="user-menu__link">
									<i class="user-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg></i> 
									Panel
								</a>
							</li>
							<li>
								<a href="{{ route('tutor.profile.personal-details') }}" class="user-menu__link">
									<i class="user-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
										<circle cx="12" cy="7" r="4"/>
									</svg></i> 
									Configuración de perfil
								</a>
							</li>
							<li>
								<a href="{{ route('tutor.bookings.subjects') }}" class="user-menu__link">
									<i class="user-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
										<line x1="16" y1="2" x2="16" y2="6"/>
										<line x1="8" y1="2" x2="8" y2="6"/>
										<line x1="3" y1="10" x2="21" y2="10"/>
									</svg></i> 
									Reservas
								</a>
							</li>
							<li>
								<a href="{{ route('tutor.invoices') }}" class="user-menu__link">
									<i class="user-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
										<polyline points="14 2 14 8 20 8"/>
										<line x1="12" y1="18" x2="12" y2="12"/>
										<path d="M14.5 15.5h-5c-.83 0-1.5-.67-1.5-1.5v0c0-.83.67-1.5 1.5-1.5h5"/>
									</svg></i> 
									Recibos
								</a>
							</li>
							{{-- <li>
								<a href="#" class="user-menu__link">
									<i class="user-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
										<line x1="9" y1="10" x2="15" y2="10"/>
										<line x1="9" y1="14" x2="13" y2="14"/>
									</svg></i> 
									Bandeja de entrada
								</a>
							</li> --}}
					@elserole('student') <!-- ======== ROL ESTUDIANTE ==========-->
						<ul class="user-menu__nav">
							<li>
								<a href="{{ route('student.profile.personal-details') }}" class="user-menu__link">
									<i class="user-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg></i> 
									Configuración de perfil
								</a>
							</li>
							<li>
								<a href="{{ route('student.bookings') }}" class="user-menu__link">
									<i class="user-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
										<line x1="16" y1="2" x2="16" y2="6"/>
										<line x1="8" y1="2" x2="8" y2="6"/>
										<line x1="3" y1="10" x2="21" y2="10"/>
									</svg></i>
									Reservas
								</a>
							</li>
							<li>
								<a href="{{ route('student.billing-detail') }}" class="user-menu__link">
									<i class="user-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
										<polyline points="14 2 14 8 20 8"/>
										<line x1="12" y1="18" x2="12" y2="12"/>
										<path d="M14.5 15.5h-5c-.83 0-1.5-.67-1.5-1.5v0c0-.83.67-1.5 1.5-1.5h5"/>
									</svg></i> 
									Detalle de recibo
								</a>
							</li>
							<li>
								<a  href="{{ route('student.favourites') }}" class="user-menu__link">
									<i class="user-menu__icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
										</svg>
									</i>
									Favoritos
								</a>
							</li>
							<li>
								<a href="{{ route('buscar.tutor') }}" class="user-menu__link">
									<i class="user-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
									<circle cx="9" cy="7" r="4"></circle>
									<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
									<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
									</svg></i> 
									Buscar Tutores
								</a>
							</li>
							{{-- <li>
								<a href="{{ route('find-tutors') }}" class="user-menu__link">
									<i class="user-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
										<polyline points="14 2 14 8 20 8"/>
										<line x1="12" y1="18" x2="12" y2="12"/>
										<path d="M14.5 15.5h-5c-.83 0-1.5-.67-1.5-1.5v0c0-.83.67-1.5 1.5-1.5h5"/>
									</svg></i> 
									Mensajes
								</a>
							</li> --}}

					@endrole
						<li class="user-menu__item--logout">
							<a href="{{ route('logout') }}" class="user-menu__link">
								<i class="user-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
									<polyline points="16 17 21 12 16 7"/>
									<line x1="21" y1="12" x2="9" y2="12"/>
								</svg></i>
									Desconectar
							</a>
						</li>
					</ul>
				</div>
			</div>

			@else
				<a href=" {{ route('login')}} "><button class="btn-outline">Ingresar</button></a>
				<div class="navbar-icon">
					<a href=" {{ route('register')}}"><i class="fa-solid fa-user-plus icon-white"></i></a>
				</div>		
			@endauth 

			<!-- Menú Hamburguesa -->
			<div class="hamburger-menu" id="hamburger-menu">
				<span></span>
				<span></span>
				<span></span>
			</div>
		</div>

		<!-- Menú Móvil -->
		<nav class="navbar-mobile" id="navbar-mobile">
			<ul>
				<li><a href="{{ route('buscar.tutor')}}" class="{{ (request()->is('tutores*') || request()->is('tutors*') || request()->routeIs('buscar.tutor')) ? 'active' : '' }}">Buscar Tutores</a></li>				
				<li><a href="{{ route('nosotros')}}" class="{{ request()->is('nosotros*') ? 'active' : '' }}">Nosotros</a></li>
				<li><a href="{{ route('como-trabajamos')}}" class="{{ request()->is('como-trabajamos*') ? 'active' : '' }}">Cómo trabajamos</a></li>
				<li><a href="{{ route('preguntas')}}" class="{{ request()->is('preguntas*') ? 'active' : '' }}">Preguntas</a></li>
				{{-- <li><a href="{{ route('')}}" class="{{ request()->is('blog*') ? 'active' : '' }}">Blog</a></li> --}}
				@auth
					@role('tutor')
						<li><a href="{{ route('tutor.dashboard')}}" class="{{ request()->is('tutor-dashboard*') ? 'active' : '' }}">Panel</a></li>
						<li><a href="{{ route('tutor.profile.personal-details') }}"  class="{{ request()->is('tutor.profile.personal-details*') ? 'active' : '' }}">Configuración</a></li>

					@elserole('student')
						<li><a href="{{ route('student.bookings') }}" class="{{ request()->is('student.bookings*') ? 'active' : '' }}">Reservas</a></li>
						<li><a href="{{ route('student.profile.personal-details') }}" class="{{ request()->is('student.profile.personal-details*') ? 'active' : '' }}">Configuración</a></li>
					@endrole
						<li><a href="{{ route('logout')}}">Cerrar Sesión</a></li>
				@else
					<li><a href="{{ route('register')}}">Regístrate</a></li>
					<li><a href="{{ route('login')}}">Login</a></li>
				@endauth

				
			</ul>
		</nav>
	</div>
</header>

<script>
	function toggleDropdown() {
    const dropdown = document.getElementById("languageDropdown");
    dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
	}

	function selectLanguage(lang) {
		// Aquí puedes guardar en localStorage, cambiar idioma, etc.
		console.log("Idioma seleccionado:", lang);
		toggleDropdown();
	}

	// Cierra el dropdown si se hace clic fuera
	document.addEventListener('click', function (e) {
		const select = document.querySelector('.language-select');
		if (!select.contains(e.target)) {
		document.getElementById("languageDropdown").style.display = "none";
		}
	});

	// Funcionalidad del menú hamburguesa
	document.addEventListener('DOMContentLoaded', function() {
	    const hamburgerMenu = document.getElementById('hamburger-menu');
	    const mobileMenu = document.getElementById('navbar-mobile');
	    
	    hamburgerMenu.addEventListener('click', function() {
	        hamburgerMenu.classList.toggle('active');
	        mobileMenu.classList.toggle('active');
	    });
	    
	    // Cerrar menú al hacer clic en un enlace
	    const mobileLinks = mobileMenu.querySelectorAll('a');
	    mobileLinks.forEach(link => {
	        link.addEventListener('click', function() {
	            hamburgerMenu.classList.remove('active');
	            mobileMenu.classList.remove('active');
	        });
	    });
	    
	    // Cerrar menú al hacer clic fuera
	    document.addEventListener('click', function(e) {
	        if (!hamburgerMenu.contains(e.target) && !mobileMenu.contains(e.target)) {
	            hamburgerMenu.classList.remove('active');
	            mobileMenu.classList.remove('active');
	        }
	    });
	});
	

	document.addEventListener('DOMContentLoaded', function () {
		const userMenu = document.querySelector('.user-menu');
		
		// Si no existe el menú en la página, no hagas nada.
		if (!userMenu) {
			return;
		}

		const trigger = userMenu.querySelector('.user-menu__trigger');

		// 1. Abrir/Cerrar el menú al hacer clic en el botón
		trigger.addEventListener('click', function (event) {
			// Evita que el clic se propague y cierre el menú inmediatamente
			event.stopPropagation(); 
			userMenu.classList.toggle('is-open');
		});

		// 2. Cerrar el menú si se hace clic fuera de él
		document.addEventListener('click', function (event) {
			if (userMenu.classList.contains('is-open') && !userMenu.contains(event.target)) {
				userMenu.classList.remove('is-open');
			}
		});
	});
</script>
