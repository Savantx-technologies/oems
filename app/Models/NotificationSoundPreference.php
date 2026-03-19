<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NotificationSoundPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'tone',
        'custom_sound_path',
        'custom_sound_name',
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function getCustomSoundUrlAttribute(): ?string
    {
        if (!$this->custom_sound_path) {
            return null;
        }

        return Storage::disk('public')->url($this->custom_sound_path);
    }
}
