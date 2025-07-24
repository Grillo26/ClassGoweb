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
        // Debug temporal - confirmar que llega al callback
        /* dd([
            'callback_ejecutado' => true,
            'request_url' => $request->fullUrl(),
            'code' => $request->input('code'),
            'all_params' => $request->all()
        ]); */
        $client = new Google_Client();
        $client->setAuthConfig(base_path('app/credentials/credential.json'));
        $client->setScopes([
            'https://www.googleapis.com/auth/calendar',
        ]);
        $client->authenticate($request->input('code'));
        $accessToken = $client->getAccessToken();



       /*  $refreshToken = null;
        if (is_array($accessToken) && array_key_exists('refresh_token', $accessToken)) {
            $refreshToken = $accessToken['refresh_token'];
            //dd("Refresh token obtenido: ", $refreshToken);
        }

        if (!$refreshToken) {
            //dd("No se pudo obtener el refresh token. Verifica la configuración de tu aplicación en Google Cloud Console.");
            return redirect()->route('admin.tutorias.index')
                ->with('error', 'No se pudo obtener el refresh token. Intenta revocar el acceso en tu cuenta de Google y vuelve a autorizar.');
        }
 */
        $refreshToken = $accessToken['refresh_token'];
        //file_put_contents(base_path('.env'), "\nGOOGLE_ADMIN_REFRESH_TOKEN={$refreshToken}", FILE_APPEND);

        file_put_contents(base_path('.env'), "\nGOOGLE_ADMIN_REFRESH_TOKEN={$refreshToken}", FILE_APPEND);
        return redirect()->route('admin.tutorias.index')->with('success', 'Autenticación completada. Refresh token guardado.');
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