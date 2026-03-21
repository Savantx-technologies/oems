<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use App\Models\SchoolSetting;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_SCHOOL_ADMIN = 'school_admin';
    public const ROLE_SUB_ADMIN = 'sub_admin';
    public const ROLE_INVIGILATOR = 'invigilator';
    public const ROLE_STAFF = 'staff';

    protected $guard = 'admin';

    protected $fillable = [
        'school_id',
        'name',
        'email',
        'mobile',
        'password',
        'role',
        'photo',
        'staff_type',
        'professional_details',
        'status',
        'aadhaar_number',
        'aadhaar_name',
        'aadhaar_dob',
        'aadhaar_gender',
        'login_method',
        'two_factor',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'two_factor' => 'boolean',
        'professional_details' => 'array',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function canManageStaffRequests(): bool
    {
        return $this->hasRole(self::ROLE_SCHOOL_ADMIN);
    }

    public function canManageStudents(): bool
    {
        return $this->hasAnyRole([
            self::ROLE_SCHOOL_ADMIN,
            self::ROLE_SUB_ADMIN,
        ]);
    }

    public function canManageQuestionBank(): bool
    {
        return $this->hasAnyRole([
            self::ROLE_SCHOOL_ADMIN,
            self::ROLE_SUB_ADMIN,
            self::ROLE_STAFF,
        ]);
    }

    public function canManageExams(): bool
    {
        return $this->hasAnyRole([
            self::ROLE_SCHOOL_ADMIN,
            self::ROLE_SUB_ADMIN,
        ]);
    }

    public function canMonitorExams(): bool
    {
        return $this->hasAnyRole([
            self::ROLE_SCHOOL_ADMIN,
            self::ROLE_SUB_ADMIN,
            self::ROLE_INVIGILATOR,
            self::ROLE_STAFF,
        ]);
    }

    public function canViewReports(): bool
    {
        return $this->hasAnyRole([
            self::ROLE_SCHOOL_ADMIN,
            self::ROLE_SUB_ADMIN,
        ]);
    }

    public function canManageSettings(): bool
    {
        return $this->hasRole(self::ROLE_SCHOOL_ADMIN);
    }

    public function canAccessSidebarSection(string $section): bool
    {
        $permissions = SchoolSetting::getAdminSidebarPermissionsForSchool($this->school_id);

        return (bool) data_get($permissions, $this->role . '.' . $section, false);
    }

    public function notificationSoundPreference()
    {
        return $this->morphOne(NotificationSoundPreference::class, 'notifiable');
    }
}
