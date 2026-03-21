<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SuperAdmin extends Authenticatable
{
    use Notifiable;

    public const ROLE_SUPERADMIN = 'superadmin';
    public const ROLE_SUB_SUPERADMIN = 'sub_superadmin';

     protected $fillable = [
        'name','email','password','is_active','mobile'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'permissions' => 'array',
    ];

    public function isMainSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    public function isSubSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUB_SUPERADMIN;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function canAccessSection(string $section): bool
    {
        if ($this->isMainSuperAdmin()) {
            return true;
        }

        $defaultPermissions = Setting::defaultSuperAdminSidebarPermissions();
        $rolePermissions = $defaultPermissions[$this->role] ?? [];
        $customPermissions = is_array($this->permissions) ? $this->permissions : [];

        return (bool) ($customPermissions[$section] ?? $rolePermissions[$section] ?? false);
    }

    public function assignSectionPermissions(array $permissions): void
    {
        $allowedSections = array_keys(Setting::superAdminSidebarSections());
        $normalized = [];

        foreach ($allowedSections as $section) {
            $normalized[$section] = (bool) ($permissions[$section] ?? false);
        }

        $this->permissions = $normalized;
    }

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

    public function notificationSoundPreference()
    {
        return $this->morphOne(NotificationSoundPreference::class, 'notifiable');
    }
}
