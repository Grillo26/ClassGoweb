<?php

namespace Modules\MeetFusion\Drivers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Modules\MeetFusion\Contracts\MeetFusionDriverInterface;
use GuzzleHttp\Client;

class Zoom implements MeetFusionDriverInterface
{

    protected string $accessToken;
    protected $client;
    protected $clientCredentials;

    public function setKeys($credentials)
    {
        $this->clientCredentials = $credentials;
    }

    protected function setupClient()
    {
        $this->accessToken = $this->getAccessToken();

        $this->client = new Client([
            'base_uri' => 'https://api.zoom.us/v2/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    protected function getAccessToken()
    {

        $client = new Client([
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->clientCredentials['client_id'] . ':' . $this->clientCredentials['client_secret']),
                'Host' => 'zoom.us',
            ],
        ]);

        $response = $client->request('POST', "https://zoom.us/oauth/token", [
            'form_params' => [
                'grant_type' => 'account_credentials',
                'account_id' => $this->clientCredentials['account_id'],
            ],
        ]);

        $responseBody = json_decode($response->getBody(), true);
        return $responseBody['access_token'];
    }

    /**
     * @params $params
     *       [
     *        'host_email',
     *        'topic',
     *        'agenda',       
     *        'duration',       
     *        'timezone',       
     *        'start_time',       
     *        'schedule_for'
     *     ]       
     */

    // create meeting
    public function createMeeting(array $data)
    {
        Log::info("🟢 Iniciando la creación de la reunión en Zoom.", ['data' => $data]);

        try {
            // Configuración del cliente Zoom
            Log::info("🔵 Configurando cliente Zoom...");
            $this->setupClient();
            Log::info("✅ Cliente Zoom configurado correctamente.");

            // Enviando solicitud a Zoom API
            Log::info("🔵 Enviando solicitud a Zoom API...");
            $response = $this->client->request('POST', 'users/me/meetings', [
                'json' => $this->getMeetingData($data),
            ]);

            // Respuesta de Zoom
            Log::info("✅ Respuesta recibida de Zoom API.");
            $res = json_decode($response->getBody(), true);
            Log::info("📄 Datos recibidos de Zoom:", ['response' => $res]);

            // Obtener el Meeting ID
            $meetingId = $res['id'] ?? null;
            if (!$meetingId) {
                Log::error("❌ No se pudo obtener el Meeting ID de la respuesta de Zoom.");
                return [
                    'status' => false,
                    'message' => 'No se pudo crear la reunión en Zoom.',
                ];
            }

            Log::info("✅ Meeting ID obtenido.", ['meeting_id' => $meetingId]);

            // Calcular el tiempo de finalización (5 minutos después del inicio)
            $startTime = isset($res['start_time']) ? Carbon::parse($res['start_time'])->addMinutes(5) : null;
            Log::info("⏳ Tiempo de finalización calculado.", ['start_time' => $startTime]);


            return [
                'status' => true,
                'data' => [
                    'link' => $res['join_url'] ?? '',
                    'meeting_id' => $meetingId,
                    'start_time' => $res['start_time'] ?? '',
                    'password' => $res['password'] ?? '',
                ],
            ];
        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            $errorResponse = $ex->getResponse() ? $ex->getResponse()->getBody()->getContents() : 'No response';
            Log::error("❌ Error en la solicitud a Zoom API.", ['error' => $errorResponse]);

            return [
                'status' => false,
                'message' => $errorResponse,
            ];
        } catch (\Exception $ex) {
            Log::error("❌ Error inesperado al crear la reunión en Zoom.", ['error' => $ex->getMessage()]);

            return [
                'status' => false,
                'message' => $ex->getMessage(),
            ];
        }
    }

    protected function getMeetingData($params) {
        Log::info("📌 Generando datos para la reunión.", ['params' => $params]);
    
        $meetingData = [
            "topic"         => $params['topic'] ?? "Reunión de Zoom",
            "agenda"        => $params['agenda'] ?? "",
            "type"          => 2, // 1 => instantánea, 2 => programada, 3 => recurrente sin horario, 8 => recurrente con horario
            "start_time"    => $params['start_time'] ?? now()->toIso8601String(),
            "timezone"      => $params['timezone'] ?? "UTC",
            "duration"      => 5, // Duración en minutos
            "password"      => generatePassword(),
            "settings"      => [
                "waiting_room"      => false,  // Desactiva la sala de espera
                "join_before_host"  => true,  // No permite que los participantes ingresen antes del host
                "approval_type"     => 0,     // Aprobación automática de los participantes
                "mute_upon_entry"   => true,  // Silencia a los participantes al entrar
                "auto_recording"    => "none" // No graba automáticamente la sesión
            ]
        ];
    
        Log::info("✅ Datos de reunión generados correctamente.", ['meeting_data' => $meetingData]);
    
        return $meetingData;
    }
    

}
