<?php

namespace App\Notifications;

use App\Models\Demande;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CancelDemande extends Notification
{
    use Queueable;
    public $dmd;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Demande $dmd)
    {
        $this->dmd = $dmd;
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
            ->subject("Votre prise de rendez vous a été annulé !")
            ->greeting("Bonjour !")
            ->line("Votre prise de rendez vous au ".$this->dmd->patient->adresse." à ".Carbon::create($this->dmd->date)->locale('fr_FR')->isoFormat('LLLL')." à été annulé !")
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
