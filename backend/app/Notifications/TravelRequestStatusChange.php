<?php

namespace App\Notifications;

use App\Enums\TravelResquestStatus;
use App\Models\TravelRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TravelRequestStatusChange extends Notification
{
    use Queueable;

    public function __construct(
        public User $user,
        public string $status,
        public TravelRequest $travelRequest
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $destination = $this->travelRequest->destination;
        $status = $this->status === TravelResquestStatus::tryFrom('approved')->value ? 'aprovado' : 'cancelado';

        return (new MailMessage)
            ->line("Olá {$this->user->name}, seu agendamento para {$destination} acaba de ser atualzado para: {$status}!");
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
