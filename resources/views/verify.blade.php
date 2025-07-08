<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación de cuenta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        window.onload = function() {
            const params = new URLSearchParams(window.location.search);
            const id = params.get('id');
            const hash = params.get('hash');
            // Intenta abrir la app
            window.location = `classgo://verify?id=${id}&hash=${hash}`;
            setTimeout(function() {
                // Si la app no se abrió, verifica automáticamente desde la web
                fetch(`/api/verify-email?id=${id}&hash=${hash}`)
                  .then(response => response.json())
                  .then(data => {
                    if(data.success || data.status === 'success') {
                      window.location = '/reservas'; // Redirige a la vista de reservas
                    } else {
                      document.getElementById('fallback').style.display = 'block';
                      document.getElementById('fallback-msg').innerText = data.message || 'No se pudo verificar el correo.';
                    }
                  })
                  .catch(() => {
                    document.getElementById('fallback').style.display = 'block';
                    document.getElementById('fallback-msg').innerText = 'Error de conexión.';
                  });
            }, 2000);
        }
    </script>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; margin: 0; padding: 0; }
        #fallback { max-width: 400px; margin: 60px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #0001; padding: 32px; text-align: center; display: none; }
        h2 { color: #295C51; }
        .btn { background: #295C51; color: #fff; border: none; border-radius: 6px; padding: 12px 24px; font-size: 16px; cursor: pointer; margin-top: 20px; }
    </style>
</head>
<body>
    <div id="fallback">
        <h2>Verificación de cuenta</h2>
        <p id="fallback-msg">No se pudo verificar el correo automáticamente.</p>
    </div>
</body>
</html> 