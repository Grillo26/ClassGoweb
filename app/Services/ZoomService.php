<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ZoomService {

    protected string $accessToken;
    protected $client;
    protected $account_id;
    protected $client_id;
    protected $client_secret;

    public function __construct()
    {
        $this->client_id = env('ZOOM_CLIENT_ID');
        $this->client_secret = env('ZOOM_CLIENT_SECRET');
        $this->account_id = env('ZOOM_ACCOUNT_ID');

        // Verificar que las credenciales estén configuradas
        if (empty($this->client_id) || empty($this->client_secret) || empty($this->account_id)) {
            Log::warning('ZoomService: Credenciales de Zoom no configuradas', [
                'client_id_empty' => empty($this->client_id),
                'client_secret_empty' => empty($this->client_secret),
                'account_id_empty' => empty($this->account_id)
            ]);
            return; // No inicializar si no hay credenciales
        }

        try {
            $this->accessToken = $this->getAccessToken();

            $this->client = new Client([
                'base_uri' => 'https://api.zoom.us/v2/',
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('ZoomService: Error al inicializar', [
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function getAccessToken()
    {

        $client = new Client([
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->client_id . ':' . $this->client_secret),
                'Host' => 'zoom.us',
            ],
        ]);

        $response = $client->request('POST', "https://zoom.us/oauth/token", [
            'form_params' => [
                'grant_type' => 'account_credentials',
                'account_id' => $this->account_id,
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
        Log::info("ZoomService: Intentando crear reunión", ['data' => $data]);
        
        // Verificar si el servicio está inicializado correctamente
        if (!$this->client) {
            Log::error('ZoomService: Cliente no inicializado, no se puede crear reunión');
            return [
                'status' => false,
                'message' => 'Zoom no está configurado correctamente. Credenciales faltantes.',
            ];
        }
        
        try {
            $response = $this->client->request('POST', 'users/me/meetings', [
                'json' => $this->getMeetingData($data),
            ]);
            $res = json_decode($response->getBody(), true);
            Log::info('ZoomService: Reunión creada exitosamente', ['meeting_id' => $res['id'] ?? 'N/A']);
            return [
                'status' => true,
                'data' => $res,
            ];
        } catch (\Throwable $th) {
            Log::error('ZoomService: Error al crear reunión', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
            return [
                'status' => false,
                'message' => $th->getMessage(),
            ];
        }
    }

    protected function getMeetingData($params) {
        Log::info("Entro a la nueva funcion creada ");
        return array_merge($params, [
            "type"          => 2, // 1 => instant, 2 => scheduled, 3 => recurring with no fixed time, 8 => recurring with fixed time
            "password"      => generatePassword(), // Opcional, si no quieres que tenga contraseña, puedes eliminarlo.
            "settings"      => [
                'join_before_host'  => true,  // Permitir que los participantes entren antes que el host
                'host_video'        => true,  
                'participant_video' => true,  
                'mute_upon_entry'   => false,  
                'waiting_room'      => false, // Desactivar sala de espera
                'audio'             => 'both', 
                'auto_recording'    => 'none', 
                'approval_type'     => 2, // 0 = aprobación automática, 1 = aprobación manual, 2 = sin registro requerido
                'enforce_login'     => false, // No se requiere que los participantes tengan cuenta en Zoom
            ]
        ]);
    }



}