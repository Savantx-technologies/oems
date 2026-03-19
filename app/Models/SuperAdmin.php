<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Authenticatable
{
    use Notifiable;

     protected $fillable = [
        'name','email','password','is_active','mobile'
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Get the entity's notifications.
     */
    public function notifications()
    {
        // Use an explicit hasMany with a where on notifiable_type so that
        // we always match the actual class name stored in the DB, and
        // avoid any issues with global morph maps or aliases.
        return $this->hasMany(Notification::class, 'notifiable_id')
            ->where('notifiable_type', static::class)
            ->latest();
    }

    /**
     * Get the entity's unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', 0);
    }
}
