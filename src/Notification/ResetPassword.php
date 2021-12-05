<?php

namespace Zekini\CrudGenerator\Notification;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param string $token
     * @return void
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     * @return array|string
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        //TODO change to template?
        return (new MailMessage)
            ->line(trans('zekini/livewire-crud-generator::resets.email.line'))
            ->action(trans('zekini/livewire-crud-generator::resets.email.action'), route('zekini/livewire-crud-generator::admin/password/showResetForm', $this->token))
            ->line(trans('zekini/livewire-crud-generator::resets.email.notRequested'));
    }
}
