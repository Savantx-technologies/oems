<?php

namespace App\Support;

use App\Models\Admin;
use App\Models\ExamAttempt;
use App\Models\ExamMonitorBlock;
use App\Models\SuperAdmin;

class ExamMonitorAccess
{
    public static function adminAttemptScope(Admin $admin, int $examId): ?array
    {
        $assignedBlockIds = static::assignedBlockIdsFor($admin, $examId);

        if (!empty($assignedBlockIds)) {
            return ExamAttempt::where('exam_id', $examId)
                ->whereIn('user_id', static::assignedStudentIdsForBlockIds($assignedBlockIds))
                ->pluck('id')
                ->all();
        }

        if (in_array($admin->role, [Admin::ROLE_INVIGILATOR, Admin::ROLE_STAFF], true)) {
            return [];
        }

        return null;
    }

    public static function superAdminAttemptScope(SuperAdmin $superAdmin, int $examId): ?array
    {
        if ($superAdmin->isMainSuperAdmin()) {
            return null;
        }

        $assignedBlockIds = static::assignedBlockIdsFor($superAdmin, $examId);

        if (!empty($assignedBlockIds)) {
            return ExamAttempt::where('exam_id', $examId)
                ->whereIn('user_id', static::assignedStudentIdsForBlockIds($assignedBlockIds))
                ->pluck('id')
                ->all();
        }

        return [];
    }

    public static function canAdminAccessAttempt(Admin $admin, ExamAttempt $attempt): bool
    {
        $allowedAttemptIds = static::adminAttemptScope($admin, $attempt->exam_id);

        return $allowedAttemptIds === null || in_array($attempt->id, $allowedAttemptIds, true);
    }

    public static function canSuperAdminAccessAttempt(SuperAdmin $superAdmin, ExamAttempt $attempt): bool
    {
        $allowedAttemptIds = static::superAdminAttemptScope($superAdmin, $attempt->exam_id);

        return $allowedAttemptIds === null || in_array($attempt->id, $allowedAttemptIds, true);
    }

    public static function adminStudentScope(Admin $admin, int $examId): ?array
    {
        $assignedBlockIds = static::assignedBlockIdsFor($admin, $examId);

        if (!empty($assignedBlockIds)) {
            return static::assignedStudentIdsForBlockIds($assignedBlockIds);
        }

        if (in_array($admin->role, [Admin::ROLE_INVIGILATOR, Admin::ROLE_STAFF], true)) {
            return [];
        }

        return null;
    }

    public static function superAdminStudentScope(SuperAdmin $superAdmin, int $examId): ?array
    {
        if ($superAdmin->isMainSuperAdmin()) {
            return null;
        }

        $assignedBlockIds = static::assignedBlockIdsFor($superAdmin, $examId);

        if (!empty($assignedBlockIds)) {
            return static::assignedStudentIdsForBlockIds($assignedBlockIds);
        }

        return [];
    }

    protected static function assignedBlockIdsFor($user, int $examId): array
    {
        return ExamMonitorBlock::query()
            ->where('exam_id', $examId)
            ->assignedTo($user)
            ->pluck('id')
            ->all();
    }

    protected static function assignedStudentIdsForBlockIds(array $assignedBlockIds): array
    {
        return \DB::table('exam_monitor_block_student')
            ->whereIn('exam_monitor_block_id', $assignedBlockIds)
            ->pluck('user_id')
            ->unique()
            ->values()
            ->all();
    }
}
