<div id="modalCompartir" class="modal-compartir">
    <div class="modal-box">
        <div class="modal-header">
            <img class="img-modal" src="{{ asset('images/Tugo_With_Phone.png')}}" alt="">
            <p>Selecciona una red social para compartir tu código de invitación.</p>
        </div>

        <div class="modal-redes">
            <a href="#" class="red-btn whatsapp" id="whatsapp-link" target="_blank"><img src="{{ asset('images/whatsapp.png')}}" alt="">WhatsApp</a>
            <a href="#" class="red-btn facebook" id="facebook-link" target="_blank"><img src="{{ asset('images/facebook.png')}}" alt="" srcset="">Facebook</a>
        </div>

        <div class="modal-footer">
            <button id="cerrarModal" class="btn-cerrar">Cerrar</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const invCodeElement = document.getElementById('inv-code');
        if (invCodeElement) {
            const codigoInvitacion = invCodeElement.innerText.trim();

            const mensaje = `¡Instala nuestra app y obtén un descuento en tu próxima tutoría! Regístrate aquí 👉 https://classgoapp.com/register?ref=${codigoInvitacion} y utiliza el siguiente código: ${codigoInvitacion}`;
            const mensajeCodificado = encodeURIComponent(mensaje);
            const urlRegistro = `https://classgoapp.com/register?ref=${codigoInvitacion}`;

            // WhatsApp
            const whatsappUrl = `https://wa.me/?text=${mensajeCodificado}`;
            document.getElementById('whatsapp-link').href = whatsappUrl;

            // Facebook (compartir enlace)
            const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(urlRegistro)}&quote=${mensajeCodificado}`;
            document.getElementById('facebook-link').href = facebookUrl;

        }
    });
</script>



