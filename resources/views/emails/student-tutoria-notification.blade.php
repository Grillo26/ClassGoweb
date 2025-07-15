
// Crear archivo: resources/views/emails/student-tutoria-notification.blade.php
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Nueva Reserva de Tutoría</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <style type="text/tailwindcss">
        :root {
          --primary-color: #023047;
          --secondary-color: #219EBC;
          --secondary-color2: #CDD6DA;
          --tertiary-color: #8ECAE6;
          --tertiary-color2: #FB8500;
          --bg-color: #fff;
          --max-width: 1200px;
          --transition: all 0.3s ease;
          --footer-white: #d6f7fd;
        }
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body class="bg-[var(--secondary-color2)] flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md mx-auto">
        <div class="bg-[var(--bg-color)] rounded-xl shadow-lg overflow-hidden">
            <div class="bg-[var(--primary-color)] p-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-white">¡Hola, {{ $userName }}!</h1>
                <span class="material-icons text-white text-4xl">school</span>
            </div>
            <div class="p-8">
                <p class="text-gray-700 text-lg mb-6">📅 Tu tutoría ha sido confirmada para el {{ $sessionDate }} a las {{ $sessionTime }}.</p>
                
                <div class="border-t border-b border-gray-200 py-4">
                    <div class="flex flex-col items-center space-y-4 py-4">
                        <img alt="Tugo tutor" class="w-60 h-auto mx-auto my-4" src="Tugo con celular.png" />
                        <p class="text-lg text-[var(--primary-color)] text-center">
                            Tu tutor {{ $oppositeName }} te está esperando. ¡Prepárate para una sesión increíble!
                        </p>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="font-bold text-[var(--primary-color)] mb-2">Detalles de la tutoría:</h3>
                    <p><strong>Fecha:</strong> {{ $sessionDate }}</p>
                    <p><strong>Hora:</strong> {{ $sessionTime }}</p>
                    <p><strong>Tutor:</strong> {{ $oppositeName }}</p>
                </div>
                
                <a class="block w-full bg-[var(--secondary-color)] hover:bg-[var(--tertiary-color2)] text-white font-bold text-center py-4 rounded-lg transition-all duration-300 ease-in-out mb-4"
                   href="{{ $meetingLink }}">Unirse a la reunión</a>
                   
                <a class="block w-full bg-gray-500 hover:bg-gray-600 text-white font-bold text-center py-3 rounded-lg transition-all duration-300 ease-in-out"
                   href="#">Ir a mi panel</a>
            </div>
            <div class="bg-[var(--footer-white)] px-8 py-4 text-center text-sm text-[var(--primary-color)]">
                <p>Gracias por ser parte de la comunidad educativa ClassGo ✨</p>
            </div>
        </div>
    </div>
</body>
</html>