<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Critical404 extends Notification
{
    use Queueable;

    public array $logData;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $logData)
    {
        $this->logData = $logData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $siteName = config('app.name', 'NUWESOFT');

        return (new MailMessage)
            ->subject("⚠️ 404 Detectado — {$siteName}")
            ->greeting("Error 404 en {$siteName}")
            ->line('Se detectó un error 404 que podría indicar un broken link:')
            ->line("**URL:** {$this->logData['url']}")
            ->when($this->logData['referer'] ?? null, fn ($msg) => $msg->line("**Desde:** {$this->logData['referer']}"))
            ->line("**IP:** {$this->logData['ip']}")
            ->line("**Fecha:** {$this->logData['timestamp']}")
            ->action('Ver Dashboard', url('/dashboard/logs'))
            ->line('Revisá el panel de logs para más detalles.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'url' => $this->logData['url'],
            'referer' => $this->logData['referer'] ?? null,
            'ip' => $this->logData['ip'],
            'timestamp' => $this->logData['timestamp'],
        ];
    }
}
