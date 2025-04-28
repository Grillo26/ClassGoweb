<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\EmailNotification;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $template;
    public string $recipientEmail;
    public ?User $recipient;
    public array $templateData;

    /**
     * Create a new job instance.
     */
    public function __construct(string $template, User|string $recipient, array $templateData)
    {
        Log::info("🔄 Iniciando el job SendNotificationJob");

        $this->template = $template;
        $this->recipientEmail = is_string($recipient) ? $recipient : $recipient->email;
        $this->recipient = $recipient instanceof User ? $recipient : null;
        $this->templateData = $templateData;

        Log::info("📌 Job creado con los siguientes datos:", [
            'template' => $this->template,
            'recipientEmail' => $this->recipientEmail,
            'recipientUser' => $this->recipient ? $this->recipient->id : 'No registrado',
            'templateData' => $this->templateData
        ]);
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notifyService): void
    {
        Log::info("📩 Enviando notificación a {$this->recipientEmail}");

        // Determinar el rol del destinatario (si no es usuario registrado, se usa 'admin')
        $role = $this->recipient ? $this->recipient->role : 'admin';
        Log::info("👤 Rol determinado para el destinatario: {$role}");

        // Obtener la plantilla de email con los datos y el rol adecuado
        $template = $notifyService->parseEmailTemplate($this->template, $role, $this->templateData);
        
        if (!empty($template)) {
            Log::info("📜 Plantilla generada correctamente", ['template' => $template]);

            if ($this->recipient) {
                // Enviar notificación a usuario registrado
                $this->recipient->notify(new EmailNotification($template));
                Log::info("✅ Correo enviado correctamente a usuario registrado: {$this->recipientEmail}");
            } else {
                // Enviar notificación a un email externo
                Notification::route('mail', $this->recipientEmail)->notify(new EmailNotification($template));
                Log::info("✅ Correo enviado correctamente a email externo: {$this->recipientEmail}");
            }
        } else {
            Log::error("❌ No se pudo generar el template para {$this->recipientEmail}");
        }

        Log::info("🏁 Finalización del job SendNotificationJob para {$this->recipientEmail}");
    }
}
