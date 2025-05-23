@component('mail::message')
# Hola {{ $data['userName'] }},

Te recordamos que tienes una reunión programada a través de ClassGo. A continuación, te compartimos los detalles:

- 📅 **Fecha:** {{ $data['sessionDate'] }}
- 🕒 **Hora:** {{ $data['sessionTime'] }}
- 🎥 **Enlace de Zoom:** [Unirse a la reunión]({{ $data['meetingLink'] }})
- 👤 **{{ $data['role'] }}:** {{ $data['oppositeName'] }}

Por favor, asegúrate de ingresar unos minutos antes del inicio para verificar tu conexión.

¡Feliz aprendizaje!

@endcomponent
