<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación de cuenta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(isset($redirect))
    <meta http-equiv="refresh" content="2;url={{ $redirect }}">
    @endif
    @if(isset($status) && ($status === 'success' || $status === 'info') && isset($id) && isset($hash))
    <script>
        window.onload = function() {
            window.location = 'classgo://verify?id={{ $id }}&hash={{ $hash }}';
        }
    </script>
    @endif
    <style>
        body { background: #f7f7f7; font-family: 'Segoe UI', Arial, sans-serif; }
        .container { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card {
            max-width: 420px;
            width: 100%;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px #0002;
            padding: 40px 32px 32px 32px;
            text-align: center;
            margin: 32px auto;
        }
        h2 {
            color: #295C51;
            font-size: 2rem;
            margin-bottom: 18px;
        }
        .success { color: #295C51; font-weight: 500; }
        .error { color: #c00; font-weight: 500; }
        .info { color: #888; font-weight: 500; }
        .login-msg {
            margin-top: 18px;
            color: #295C51;
            background: #eafbe7;
            border-radius: 8px;
            padding: 12px 0;
            font-size: 1.08rem;
            font-weight: 500;
        }
        .loader {
            margin: 18px auto 0 auto;
            border: 4px solid #eafbe7;
            border-top: 4px solid #295C51;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Verificación de cuenta</h2>
            <p class="{{ $status }}">{{ $message }}</p>
            @if($status === 'success' || $status === 'info')
                <div class="login-msg">Por favor, loguéate para terminar la verificación.</div>
            @endif
            @if(isset($redirect))
                <div class="loader"></div>
                <p style="margin-top:10px; color:#888;">Redirigiendo...</p>
            @endif
        </div>
    </div>
</body>
</html> 