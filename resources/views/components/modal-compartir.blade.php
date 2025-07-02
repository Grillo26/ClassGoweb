<div id="modalCompartir" class="modal-compartir">
    <div class="modal-box">
        <div class="modal-header">
            <img class="img-modal" src="{{ asset('images/send.png')}}" alt="">
            <p>Selecciona una red social para compartir tu código de invitación.</p>
        </div>

        <div class="modal-redes">
            <a href="#" class="red-btn whatsapp" id="whatsapp-link" target="_blank">WhatsApp</a>
            <a href="#" class="red-btn facebook" id="facebook-link" target="_blank">Facebook</a>
            <a href="#" class="red-btn instagram" id="instagram-link" onclick="compartirCodigo()" target="_blank">Instagram</a>
            <a href="#" class="red-btn tiktok" id="tiktok-link" target="_blank">TikTok</a>
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

            // Instagram 


            // TikTok (no permite compartir directo por URL, solo redirigir a perfil o sugerencia de enlace)
            const tiktokUrl = `https://www.tiktok.com/share?url=${encodeURIComponent(urlRegistro)}&text=${mensajeCodificado}`;
            document.getElementById('tiktok-link').href = tiktokUrl;
        }
    });

    function compartirCodigo() {
        const codigo = document.getElementById('inv-code').innerText.trim();
        const mensaje = `¡Instala nuestra app y obtén un descuento en tu próxima tutoría! Usa este código: ${codigo}`;
        const url = `https://classgoapp.com/register?ref=${codigo}`;

        if (navigator.share) {
            navigator.share({
                title: 'ClassGo',
                text: mensaje,
                url: url
            })
            .then(() => console.log('Contenido compartido exitosamente'))
            .catch((error) => console.error('Error al compartir:', error));
        } else {
            alert('La función de compartir no es compatible con tu navegador');
        }
    }
</script>



