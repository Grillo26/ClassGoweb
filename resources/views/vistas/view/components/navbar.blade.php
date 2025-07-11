<header class="navbar">
	<div class="navbar-container">
		<div class="navbar-left">
			<!-- Logo -->
			<img src="{{ asset('images/logoclassgo.png') }}" class="nav-i" alt="Mascota">

			<!-- Enlaces Desktop -->
			<nav class="navbar-links">
				<ul>
					<li><a href="#">Tutores</a></li>
					<li><a href="#">Nosotros</a></li>
					<li><a href="#">Cómo trabajamos</a></li>
					<li><a href="#">Preguntas</a></li>
					<li><a href="#">Blog</a></li>
				</ul>
			</nav>
		</div>

		<!-- Lado derecho -->
		<div class="navbar-actions">
			<select class="navbar-language">
        <option selected>Español</option>
        <option>English</option>
      </select>
			<button class="btn-outline">Empezar</button>
			<div class="navbar-icon">
				<i class="fa-solid fa-user-plus icon-white"></i>
			</div>

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
				<li><a href="#">Tutores</a></li>
				<li><a href="#">Nosotros</a></li>
				<li><a href="#">Cómo trabajamos</a></li>
				<li><a href="#">Preguntas</a></li>
				<li><a href="#">Blog</a></li>
			</ul>
		</nav>
	</div>
</header>

<script>
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