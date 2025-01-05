<?php

namespace App\Notification\Infrastructure\Persistence;

use App\Notification\Domain\Models\Notification;
use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
    protected $table = 'user_notifications';

    protected $fillable = [
        'user_id',
        'event_name',
        'metadata',
        'read'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function toNotification(): Notification
    {
        return new Notification(
            $this->id,
            $this->user_id,
            $this->event_name,
            $this->metadata,
            $this->read,
            $this->created_at
        );
    }

}
