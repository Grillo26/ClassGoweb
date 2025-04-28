<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Calendar;
use Illuminate\Http\Request;

class GoogleMeetController extends Controller
{
    public function createMeeting(Request $request)
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->setRedirectUri(route('google.callback'));

        // Si el usuario no está autenticado, redirigir para autenticar
        if (!$request->session()->has('access_token')) {
            $authUrl = $client->createAuthUrl();
            return redirect()->to($authUrl);
        }

        $client->setAccessToken($request->session()->get('access_token'));
        
        if ($client->isAccessTokenExpired()) {
            // Si el token ha expirado, redirigir para refrescarlo
            $authUrl = $client->createAuthUrl();
            return redirect()->to($authUrl);
        }

        $service = new Google_Service_Calendar($client);

        // Crear un evento (reunión)
        $event = new \Google_Service_Calendar_Event([
            'summary' => 'Reunión de Tutoría',
            'description' => 'Reunión entre tutor y estudiante',
            'start' => [
                'dateTime' => '2025-02-25T10:00:00-07:00',
                'timeZone' => 'America/Los_Angeles',
            ],
            'end' => [
                'dateTime' => '2025-02-25T11:00:00-07:00',
                'timeZone' => 'America/Los_Angeles',
            ],
            'conferenceData' => [
                'createRequest' => [
                    'requestId' => '7qxalsvy0e',
                    'conferenceSolutionKey' => [
                        'type' => 'hangoutsMeet',
                    ],
                ],
            ],
            'attendees' => [
                ['email' => 'tutor@example.com'],  // Email del tutor
                ['email' => 'student@example.com'], // Email del estudiante
            ],
        ]);

        // Crear el evento en el calendario
        $event = $service->events->insert('primary', $event, ['conferenceDataVersion' => 1]);

        // Obtener el enlace de Google Meet
        $meetLink = $event->getConferenceData()->getEntryPoints()[0]->getUri();

        return view('meet_link', ['link' => $meetLink]);
    }

    public function callback(Request $request)
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google/your-credentials.json'));
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->setRedirectUri(route('google.callback'));

        $code = $request->get('code');
        $accessToken = $client->fetchAccessTokenWithAuthCode($code);
        $request->session()->put('access_token', $accessToken);

        return redirect()->route('meet.create');
    }
}
