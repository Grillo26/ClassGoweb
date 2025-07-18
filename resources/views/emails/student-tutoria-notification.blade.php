<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nueva Reserva de Tutoría</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #ffffff;">
    
    <!-- Container principal -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #CDD6DA; padding: 20px;">
        <tr>
            <td align="center">
                
                <!-- Card principal -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #219EBC; padding: 24px; text-align: left;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="color: #ffffff; font-size: 24px; font-weight: bold; margin: 0;">
                                        ¡Hola, {{ $userName }}!
                                    </td>
                                    <td align="right" style="color: #ffffff; font-size: 32px;">
                                        🎓
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Contenido principal -->
                    <tr>
                        <td style="padding: 32px;">
                            
                            <!-- Mensaje de bienvenida -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="color: #374151; font-size: 18px; line-height: 1.6; margin-bottom: 24px; padding-bottom: 24px;">
                                        📅 Tu tutoría ha sido confirmada para el <strong>{{ $sessionDate }}</strong> a las <strong>{{ $sessionTime }}</strong>.
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Sección de imagen y texto -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; padding: 20px 0;">
                                <tr>
                                    <td align="center" style="padding: 16px 0;">
                                        <!-- ✅ USAR EMBED EN LUGAR DE BASE64 -->
                                        <img src="{{ $message->embed(storage_path('app/public/Tugoemail.png')) }}" 
                                             alt="Tugo tutor" 
                                             width="200" 
                                             height="auto" 
                                             style="display: block; margin: 0 auto 16px auto; max-width: 200px;" />
                                        
                                        <!-- Texto de la imagen -->
                                        <p style="font-size: 18px; color: #023047; text-align: center; margin: 16px 0; line-height: 1.5; font-weight: 600;">
                                            Tu tutor {{ $oppositeName }} te está esperando. ¡Prepárate para una sesión increíble!
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Detalles de la tutoría -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f9fafb; border-radius: 8px; margin: 24px 0;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <h3 style="font-weight: bold; color: #023047; margin: 0 0 8px 0; font-size: 16px;">
                                            Detalles de la tutoría:
                                        </h3>
                                        <p style="margin: 4px 0; color: #374151;"><strong>Fecha:</strong> {{ $sessionDate }}</p>
                                        <p style="margin: 4px 0; color: #374151;"><strong>Hora:</strong> {{ $sessionTime }}</p>
                                        <p style="margin: 4px 0; color: #374151;"><strong>Tutor:</strong> {{ $oppositeName }}</p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Botón principal -->
                            {{-- <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 16px;">
                                <tr>
                                    <td>
                                        <a href="{{ $meetingLink }}" style="display: block; width: 100%; background-color: #219EBC; color: #ffffff; font-weight: bold; text-align: center; padding: 16px; border-radius: 8px; text-decoration: none; font-size: 16px; box-sizing: border-box;">
                                            🎥 Unirse a la reunión
                                        </a>
                                    </td>
                                </tr>
                            </table> --}}
                            
                            <!-- Botón secundario -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td>
                                        <a href="https://www.classgoapp.com/" style="display: block; width: 100%; background-color: #6b7280; color: #ffffff; font-weight: bold; text-align: center; padding: 12px; border-radius: 8px; text-decoration: none; font-size: 14px; box-sizing: border-box;">
                                            📋 Ir a mi panel
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #d6f7fd; padding: 16px 32px; text-align: center; font-size: 14px; color: #023047;">
                            <p style="margin: 0;">
                                Gracias por ser parte de la comunidad educativa ClassGo ✨
                            </p>
                        </td>
                    </tr>
                    
                </table>
                
            </td>
        </tr>
    </table>
    
</body>
</html>