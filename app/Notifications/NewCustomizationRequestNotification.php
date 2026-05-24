<?php

namespace App\Notifications;

use App\Models\CustomizationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCustomizationRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public CustomizationRequest $customization) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'customization',
            'icon'    => 'document',
            'color'   => 'yellow',
            'title'   => 'New Customization Request',
            'message' => "{$this->customization->name} submitted a custom trip request",
            'url'     => route('admin.customizations.show', $this->customization),
            'id'      => $this->customization->id,
        ];
    }
}
