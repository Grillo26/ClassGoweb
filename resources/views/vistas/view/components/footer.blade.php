<footer>
    <div class="footer-container">
        <div class="container-info">
            <div class="footer-info"><!--ClassGo Logo + info-->
                <img src="{{ asset('images/logoclassgo.png') }}" alt="Mascota">
                <div class="info-text">
                    <i class="fa-solid fa-envelope icon"></i>
                    <p>classgobol@gmail.com</p>
                </div>
                <div class="info-text">
                    <i class="fa-brands fa-whatsapp icon"></i>
                    <a href="https://wa.link/yiegi5"><p>77573997</p>    </a>
                </div>
                @auth
                    <a href=" {{ route('buscar.tutor')}}"><button class="btn-registrate">Buscar Tutor</button></a>
                @endauth
                @guest
                    <a href=" {{ route('register')}}"><button class="btn-registrate">Registrate Gratis</button></a>
                @endguest

            </div>
            <div class="footer-about"> <!--List about-->
                <div class="box">
                    <h1>Tutores</h1>
                    <a href=" {{ route('buscar.tutor')}}"><p>Tutores en linea</p></a>

                </div>
                <div class="box">
                    <h1>Inicia Hoy</h1>
   
                    <a href=" {{ route('register')}}"><p>Registrate</p></a>
                    <a href="{{ route('buscar.tutor')}}"><p>Encontrar Tutor</p></a>
                </div>
                <div class="box">
                    <h1>Blog</h1>
                    <p>Consejos de <br>expertos</p>
                </div>
                <div class="box">
                    <h1>Opten la App</h1>
                    <p>¡Lleva tu educación a todas partes!</p>
                    <a href="https://play.google.com/store/apps/details?id=com.neurasoft.classgo" target="_blank"><img src="{{ asset('images/googleplay.png')}}" alt="" style=" width: 150px; padding-top: 1rem;"></a>
                </div>
                <div class="box">
                    <a href="{{ route('nosotros')}}"><h1>Nosotros</h1></a>
                    <a href=" {{ route('nosotros')}}"><p>Mision</p></a>
                    <a href=" {{ route('nosotros')}}"><p>Vision</p></a>
                    <a href="{{ route('nosotros')}}"><p>Logros clave</p></a>
                </div>
                <div class="box">
                    <h1>Preguntas</h1>
                    <a href="preguntas"><p>Preguntas frecuentes</p> </a>
                </div>
            </div>

        </div>
        <div class="container-redes">
            <a href="https://www.tiktok.com/@classgoapp" target="_blank"><div class="circle-icon"><i class="fa-brands fa-tiktok fa-2x"></i></div></a>
            <a href="https://www.facebook.com/profile.php?id=61578383078347" target="_blank"><div class="circle-icon"><i class="fa-brands fa-facebook-f"></i></div></a>
            <a href="https://www.instagram.com/classgo_app/" target="_blank"><div class="circle-icon"><i class="fa-brands fa-instagram"></i></div></a>
            <a href="https://wa.link/yiegi5" target="_blank"><div class="circle-icon"><i class="fa-brands fa-whatsapp"></i></div></a>
        </div>
        <hr>
        <p class="derechos-reservados">© 2025 classgobol. Todos los derechos reservados.</p>
        
    </div>
</footer>

