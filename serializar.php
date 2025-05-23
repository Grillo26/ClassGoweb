<?php
$template = [
    "info" => "{userName} - Nombre destinatario <br> {sessionDate} - Fecha <br> {sessionTime} - Hora <br> {meetingLink} - Enlace Zoom <br> {oppositeName} - Nombre opuesto <br> {role} - Rol",
    "subject" => "🕒 Recordatorio de tu reunión agendada – {sessionDate}",
    "greeting" => "Hola {userName},",
    "content" => "Hola {userName},<br>
Te recordamos que tienes una reunión programada a través de ClassGo. A continuación, te compartimos los detalles:<br>
📅 Fecha: {sessionDate}<br>
🕒 Hora: {sessionTime}<br>
🎥 Enlace de Zoom: {meetingLink}<br>
👤 {role}: {oppositeName}<br>
Por favor, asegúrate de ingresar unos minutos antes del inicio para verificar tu conexión."
];
echo serialize($template);