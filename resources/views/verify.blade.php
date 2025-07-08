<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación de cuenta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(isset($redirect))
    <meta http-equiv="refresh" content="2;url={{ $redirect }}">
    @endif
    <style>
        body { background: #f7f7f7; font-family: Arial, sans-serif; }
        .card { max-width: 400px; margin: 80px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px #0001; padding: 32px; text-align: center; }
        h2 { color: #295C51; }
        .success { color: #295C51; }
        .error { color: #c00; }
        .info { color: #888; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Verificación de cuenta</h2>
        <p class="{{ $status }}">{{ $message }}</p>
        @if(isset($redirect))
            <p>Redirigiendo...</p>
        @endif
    </div>
</body>
</html> 