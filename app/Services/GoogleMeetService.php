<?php


namespace App\Services;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Carbon\Carbon;
class GoogleMeetService
{



    public function createMeeting($meetingData)
    {
        /* $validated = $meetingData->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date_time' => 'required|date',
            'end_date_time' => 'required|date|after:start_date_time',
        ]); */

        $title = $meetingData['title'] ?? 'Tutoría';
        $description = $meetingData['description'] ?? 'Sesión de tutoría';
        $start_date_time = $meetingData['start_time'] ?? now()->addMinutes(10)->format('Y-m-d H:i:s');
        $end_date_time = $meetingData['end_time'] ?? now()->addMinutes(40)->format('Y-m-d H:i:s');
        $timezone = $meetingData['timezone'] ?? 'UTC';


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
            'summary' => $title,
            'description' => $description,
            'start' => [
                'dateTime' => Carbon::parse($start_date_time)->toRfc3339String(),
                'timeZone' => 'UTC',
            ],
            'end' => [
                'dateTime' => Carbon::parse($end_date_time)->toRfc3339String(),
                'timeZone' => $timezone,
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
        return $event->getHangoutLink();
    }
}