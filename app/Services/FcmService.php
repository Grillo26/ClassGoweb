<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class FcmService
{
    protected $messaging;

    public function __construct()
    {
        try {
            $credentialsPath = env('FIREBASE_CREDENTIALS');
            $fullPath = base_path($credentialsPath);
            
            Log::info('FcmService: Inicializando Firebase', [
                'credentials_path' => $credentialsPath,
                'full_path' => $fullPath,
                'file_exists' => file_exists($fullPath),
                'file_size' => file_exists($fullPath) ? filesize($fullPath) : 'N/A'
            ]);
            
            if (!file_exists($fullPath)) {
                throw new \Exception("Archivo de credenciales de Firebase no encontrado: {$fullPath}");
            }
            
            $factory = (new Factory)->withServiceAccount($fullPath);
            $this->messaging = $factory->createMessaging();
            
            Log::info('FcmService: Firebase inicializado correctamente');
            
        } catch (\Exception $e) {
            Log::error('FcmService: Error al inicializar Firebase', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function sendNotification($fcmToken, $title, $body, $data = [])
    {
        try {
            Log::info('FcmService: Enviando notificación', [
                'fcm_token_length' => strlen($fcmToken),
                'fcm_token_preview' => substr($fcmToken, 0, 20) . '...',
                'title' => $title,
                'body' => $body,
                'data' => $data
            ]);

            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            Log::info('FcmService: Mensaje creado, enviando a Firebase');

            $result = $this->messaging->send($message);

            Log::info('FcmService: Notificación enviada exitosamente', [
                'result' => $result
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('FcmService: Error al enviar notificación', [
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
} 