<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    use HasFactory;

    public const ADMIN_SIDEBAR_PERMISSIONS_KEY = 'admin_sidebar_permissions';

    protected $fillable = [
        'school_id',
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public static function getAdminSidebarPermissionsForSchool(?int $schoolId): array
    {
        if (!$schoolId) {
            return Setting::getAdminSidebarPermissions();
        }

        $defaults = Setting::defaultAdminSidebarPermissions();
        $saved = static::where('school_id', $schoolId)
            ->where('key', static::ADMIN_SIDEBAR_PERMISSIONS_KEY)
            ->value('value');

        if (is_string($saved)) {
            $saved = json_decode($saved, true) ?? [];
        }

        if (!is_array($saved)) {
            $saved = [];
        }

        foreach ($defaults as $role => $sections) {
            $saved[$role] = array_merge($sections, $saved[$role] ?? []);
        }

        return $saved;
    }
}
