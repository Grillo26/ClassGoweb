<!-- filepath: resources/views/emails/admin-nueva-tutoria.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nueva Tutoría Programada</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #ffffff;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #CDD6DA; padding: 20px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                    style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #219EBC; padding: 24px; text-align: left;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="color: #ffffff; font-size: 24px; font-weight: bold; margin: 0;">
                                        ¡Nueva tutoría registrada!
                                    </td>
                                    <td align="right" style="color: #ffffff; font-size: 32px;">
                                        📝
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Contenido principal -->
                    <tr>
                        <td style="padding: 32px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td
                                        style="color: #374151; font-size: 18px; line-height: 1.6; margin-bottom: 24px; padding-bottom: 24px;">
                                        📅 Se ha registrado una nueva tutoría para el <strong>{{ $sessionDate
                                            }}</strong>.
                                    </td>
                                </tr>
                            </table>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; padding: 20px 0;">
                                <tr>
                                    <td align="center" style="padding: 16px 0;">
                                        <img src="{{ $message->embed(storage_path('app/public/Tugoemail.png')) }}"
                                            alt="ClassGo" width="200" height="auto"
                                            style="display: block; margin: 0 auto 16px auto; max-width: 200px;" />
                                        <p
                                            style="font-size: 18px; color: #023047; text-align: center; margin: 16px 0; line-height: 1.5; font-weight: 600;">
                                            Revisa el panel de administración para gestionar la tutoría.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            <!-- Detalles de la tutoría -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="background-color: #f9fafb; border-radius: 8px; margin: 24px 0;">
                                <tr>
                                    <td style="padding: 16px;">
                                        <h3
                                            style="font-weight: bold; color: #023047; margin: 0 0 8px 0; font-size: 16px;">
                                            Detalles de la tutoría:
                                        </h3>
                                        <p style="margin: 4px 0; color: #374151;"><strong>Estudiante:</strong> {{
                                            $nombre_estudiante }}</p>
                                        <p style="margin: 4px 0; color: #374151;"><strong>Tutor:</strong> {{
                                            $nombre_tutor }}</p>
                                        <p style="margin: 4px 0; color: #374151;"><strong>Materia:</strong> {{
                                            $nombre_materia ?? '-' }}</p>
                                        <p style="margin: 4px 0; color: #374151;"><strong>Fecha y hora:</strong> {{
                                            $sessionDate }}</p>
                                    </td>
                                </tr>
                            </table>
                            <!-- Botón principal -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td>
                                        <a href="https://www.classgoapp.com/admin/tutorias"
                                            style="display: block; width: 100%; background-color: #219EBC; color: #ffffff; font-weight: bold; text-align: center; padding: 16px; border-radius: 8px; text-decoration: none; font-size: 16px; box-sizing: border-box;">
                                            📋 Ir al panel de administración
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #d6f7fd; padding: 16px 32px; text-align: center; font-size: 14px; color: #023047;">
                            <p style="margin: 0;">
                                Gracias por gestionar la comunidad educativa ClassGo ✨
                            </p>
                        </td>
                    </tr>
                </table>
            </td>