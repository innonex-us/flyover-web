<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewContactMessageNotification extends Notification
{
    use Queueable;

    public function __construct(public ContactMessage $message) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $name = trim($this->message->first_name . ' ' . $this->message->last_name);

        return [
            'type'    => 'contact',
            'icon'    => 'mail',
            'color'   => 'red',
            'title'   => 'New Contact Message',
            'message' => "{$name} sent a message: " . \Str::limit($this->message->message, 60),
            'url'     => route('admin.contact-messages.show', $this->message),
            'id'      => $this->message->id,
        ];
    }
}
