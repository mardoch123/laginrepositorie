<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Notification de test')
                    ->greeting('Bonjour!')
                    ->line('Ceci est une notification de test pour votre application de gestion d\'élevage.')
                    ->line('Si vous recevez ce message, cela signifie que le système de notification fonctionne correctement.')
                    ->action('Voir les rappels', url('/reminders'))
                    ->line('Merci d\'utiliser notre application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Notification de test',
            'message' => 'Ceci est une notification de test pour vérifier le bon fonctionnement du système.',
            'type' => 'test',
            'icon' => 'bell',
            'color' => 'purple'
        ];
    }
}