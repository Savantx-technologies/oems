<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public const ADMIN_SIDEBAR_PERMISSIONS_KEY = 'admin_sidebar_permissions';

    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    public static function defaultAdminSidebarPermissions(): array
    {
        return [
            'school_admin' => [
                'dashboard' => true,
                'admissions' => true,
                'users' => true,
                'question_bank' => true,
                'exams' => true,
                'live_exams' => true,
                'practice_demo' => true,
                'evaluation' => true,
                'reports' => true,
                'settings' => true,
                'logs' => true,
            ],
            'sub_admin' => [
                'dashboard' => true,
                'admissions' => true,
                'users' => true,
                'question_bank' => true,
                'exams' => true,
                'live_exams' => true,
                'practice_demo' => true,
                'evaluation' => true,
                'reports' => true,
                'settings' => false,
                'logs' => true,
            ],
            'invigilator' => [
                'dashboard' => true,
                'admissions' => false,
                'users' => false,
                'question_bank' => false,
                'exams' => false,
                'live_exams' => true,
                'practice_demo' => false,
                'evaluation' => false,
                'reports' => false,
                'settings' => false,
                'logs' => true,
            ],
            'staff' => [
                'dashboard' => true,
                'admissions' => false,
                'users' => false,
                'question_bank' => true,
                'exams' => false,
                'live_exams' => false,
                'practice_demo' => false,
                'evaluation' => false,
                'reports' => false,
                'settings' => false,
                'logs' => true,
            ],
        ];
    }

    public static function adminSidebarSections(): array
    {
        return [
            'dashboard' => 'Dashboard',
            'admissions' => 'Admissions',
            'users' => 'Users',
            'question_bank' => 'Question Bank',
            'exams' => 'Exams',
            'live_exams' => 'Live Exams',
            'practice_demo' => 'Practice / Demo',
            'evaluation' => 'Evaluation & Results',
            'reports' => 'Reports',
            'settings' => 'Settings',
            'logs' => 'Logs',
        ];
    }

    public static function getAdminSidebarPermissions(): array
    {
        $defaults = static::defaultAdminSidebarPermissions();
        $saved = static::where('key', static::ADMIN_SIDEBAR_PERMISSIONS_KEY)->value('value');

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
