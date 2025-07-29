<header class="navbar">
	<!-- INICIO: Inclusión de CSS responsivos para navbar -->
	<link rel="stylesheet" href="{{ asset('css/estilos/navbar-tablet.css') }}">
	<link rel="stylesheet" href="{{ asset('css/estilos/navbar-mobile.css') }}">
	<!-- FIN: Inclusión de CSS responsivos para navbar -->
	<div class="navbar-container">
		<div class="navbar-left">
			<!-- Logo -->
			<a href="/home">
				<img src="{{ asset('storage/optionbuilder/uploads/453302-18-2025_0409pmClassGo%20Logo-23%20(1).png') }}" class="nav-i" alt="Mascota">
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

			<a href=" {{ route('login')}} "><button class="btn-outline"> Empezar</button></a>
			@auth
			    <a href="#"><img src="{{ Auth::user()->img ?? asset('images/default.png') }}" 
			        alt="Foto de perfil" 
			        class="navbar-profile-img"
			        style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; cursor: pointer;"></a>
			@else
				<div class="navbar-icon">
					<a href=" {{ route('login')}}"><i class="fa-solid fa-user-plus icon-white"></i></a>
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
				<li><a href="{{ route('buscar.tutor')}}" class="{{ (request()->is('tutores*') || request()->is('tutors*') || request()->routeIs('buscar.tutor')) ? 'active' : '' }}">Tutores</a></li>				<li><a href="{{ route('nosotros')}}" class="{{ request()->is('nosotros*') ? 'active' : '' }}">Nosotros</a></li>
				<li><a href="{{ route('como-trabajamos')}}" class="{{ request()->is('como-trabajamos*') ? 'active' : '' }}">Cómo trabajamos</a></li>
				<li><a href="{{ route('preguntas')}}" class="{{ request()->is('preguntas*') ? 'active' : '' }}">Preguntas</a></li>
				{{-- <li><a href="{{ route('')}}" class="{{ request()->is('blog*') ? 'active' : '' }}">Blog</a></li> --}}
				<li><a href="#">Empezar</a></li>
				<li><a href="#">Login</a></li>
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
	
</script>
