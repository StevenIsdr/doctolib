<?php

namespace App\Notifications;

use App\Models\Demande;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmDemande extends Notification
{
    use Queueable;

    public $demande;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Demande $demande)
    {
        $this->demande = $demande;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            ->subject("Votre prise de rendez vous a été confirmé !")
            ->greeting("Bonjour !")
            ->line("Votre prise de rendez vous au ".$this->demande->patient->adresse." à ".Carbon::create($this->demande->date)->locale('fr_FR')->isoFormat('LLLL')." à bien été validé !")
            ->action('Mon espace patient', url('/tableau-de-bord'));
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
            //
        ];
    }
}
