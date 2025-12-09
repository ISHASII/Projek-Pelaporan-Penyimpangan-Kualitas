<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationPush extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'notification_push';

    /**
     * Indicates if the model should be timestamped.
     * We only have created_at, not updated_at
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'phone_number',
        'user_email',
        'message',
        'flag',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];
}
