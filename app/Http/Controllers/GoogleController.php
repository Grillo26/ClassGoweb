<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Carbon\Carbon;

class GoogleController extends Controller
{
    // app/Http/Controllers/GoogleMeetController.php

    public function authenticate()
    {
        $client = new Google_Client();
        $client->setAuthConfig(base_path('app/credentials/credential.json'));
        $client->setScopes([
            'https://www.googleapis.com/auth/calendar',
        ]);
        $callbackUrl = route('google.callback');
        $client->setRedirectUri($callbackUrl);
        //dd("La URL que DEBES registrar en Google es:", $callbackUrl);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }

    public function callback(Request $request)
{
    $client = new Google_Client();
    $client->setAuthConfig(base_path('app/credentials/credential.json'));
    
    // Es importante que el redirect URI aquí sea el mismo que usaste en la función redirect()
    $client->setRedirectUri(route('google.callback'));

    // Usamos fetchAccessTokenWithAuthCode que es más explícito y devuelve el token directamente.
    $accessToken = $client->fetchAccessTokenWithAuthCode($request->input('code'));

    // --- Comprobación Robusta ---
    // Verificamos si el array del token contiene la clave 'refresh_token'.
    if (isset($accessToken['refresh_token'])) {
        
        $refreshToken = $accessToken['refresh_token'];
        
        // Aquí puedes guardar el refresh token donde lo necesites.
        // Por ejemplo, en el archivo .env o en la base de datos de un usuario.
        // CUIDADO: La siguiente línea agrega el token al final del .env cada vez. 
        // Es mejor para una configuración de una sola vez, no para cada login.
         file_put_contents(base_path('.env'), "\nGOOGLE_ADMIN_REFRESH_TOKEN={$refreshToken}", FILE_APPEND);

        dd("¡Éxito! Tu nuevo refresh token es:", $refreshToken);

        return redirect()->route('admin.tutorias.index')->with('success', 'Autenticación completada. Refresh token obtenido.');

    } else {
        // Esto ocurrirá si el usuario ya había autorizado la app antes.
        // El access_token es válido para usar ahora, pero no obtuvimos un nuevo refresh_token.
        // dd("Autenticación exitosa, pero no se recibió un nuevo refresh token.", $accessToken);
        
        return redirect()->route('admin.tutorias.index')
            ->with('error', 'No se recibió un nuevo refresh token. Si necesitas forzar la generación de uno, primero revoca el acceso desde tu cuenta de Google.');
    }
}




    public function createMeeting(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date_time' => 'required|date',
            'end_date_time' => 'required|date|after:start_date_time',
        ]);

        $client = new Google_Client();
        $client->setAuthConfig(base_path('app/credentials/credential.json'));
        $client->setAccessToken([
            'access_token' => env('GOOGLE_ADMIN_ACCESS_TOKEN'),
            'refresh_token' => env('GOOGLE_ADMIN_REFRESH_TOKEN'),
        ]);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken(env('GOOGLE_ADMIN_REFRESH_TOKEN'));
        }

        $service = new Google_Service_Calendar($client);

        $event = new Google_Service_Calendar_Event([
            'summary' => $validated['title'],
            'description' => $validated['description'],
            'start' => [
                'dateTime' => Carbon::parse($validated['start_date_time'])->toRfc3339String(),
                'timeZone' => 'UTC',
            ],
            'end' => [
                'dateTime' => Carbon::parse($validated['end_date_time'])->toRfc3339String(),
                'timeZone' => 'UTC',
            ],
            'conferenceData' => [
                'createRequest' => [
                    'conferenceSolutionKey' => ['type' => 'hangoutsMeet'],
                    'requestId' => 'random-string',
                ],
            ],
        ]);

        $calendarId = 'primary';
        $event = $service->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);

        return view('meeting_success', [
            'message' => 'Reunión creada con éxito.',
            'meet_link' => $event->getHangoutLink(),
        ]);
    }

    public function showCreateMeetingForm()
    {
        return view('create_meeting');
    }
}