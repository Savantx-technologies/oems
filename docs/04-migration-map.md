# Migration Map

This file documents the migration history currently present in `database/migrations`.

## 1. Laravel Base Migrations

### `0001_01_01_000000_create_users_table.php`

Creates:

- `users`
- `password_reset_tokens`
- `sessions`

Purpose:

- base Laravel user/auth/session foundation

### `0001_01_01_000001_create_cache_table.php`

Purpose:

- cache storage tables

### `0001_01_01_000002_create_jobs_table.php`

Purpose:

- queue and failed job support

## 2. School and Admin Foundation

### `2025_02_02_000000_create_schools_table.php`

Creates `schools`.

Important fields:

- `name`
- `code`
- `type`
- `board`
- `registration_no`
- `address`
- `contact_number`
- `email`
- `is_active`
- `status`

### `2025_02_03_000000_create_admins_table.php`

Creates `admins`.

Important fields:

- `school_id`
- `name`
- `email`
- `mobile`
- `password`
- `role`
- `status`
- Aadhaar fields
- `two_factor`
- `login_method`

Note:

This is the core school-side admin/staff table.

### `2025_02_05_000000_create_admin_requests_table.php`

Creates `admin_requests`.

Purpose:

- approval flow for actions such as block/unblock staff users

Important fields:

- `school_id`
- `requester_id`
- `target_user_id`
- `request_type`
- `reason`
- `status`

## 3. SuperAdmin and Approval Workflow

### `2026_02_04_052734_create_super_admins_table.php`

Creates `super_admins`.

Initial fields:

- `name`
- `email`
- `password`
- `is_active`

### `2026_02_04_060854_create_super_admin_otps_table.php`

Purpose:

- OTP workflow for superadmin authentication

### `2026_02_05_000001_create_staff_requests_table.php`

Creates `staff_requests`.

Purpose:

- school-side staff onboarding request with superadmin approval

Important fields:

- `school_id`
- `requester_id`
- basic identity data
- Aadhaar data
- `staff_type`
- `professional_details`
- `role`
- `password`
- `status`
- `approved_by`
- `approved_at`

### `2026_03_19_000001_add_role_and_permissions_to_super_admins_table.php`

Adds to `super_admins`:

- `role`
- `permissions`

Purpose:

- support `superadmin` and `sub_superadmin`
- support custom section-level access

## 4. Admin and Student Detail Expansion

### `2026_02_05_000002_add_staff_details_to_admins_table.php`

Adds staff profile fields to `admins`.

Purpose:

- support richer admin/staff onboarding

Expected fields from code usage:

- `photo`
- `staff_type`
- `professional_details`

### `2026_02_06_000000_add_student_details_to_users_table.php`

Adds student-specific fields to `users`.

Important fields:

- `school_id`
- `admission_number`
- `grade`
- `section`
- `role`
- `status`

### `2026_02_07_000001_add_profile_fields_to_users_table.php`

Purpose:

- extend student profile data

### `2026_02_07_113319_create_admin_otps_table.php`

Purpose:

- OTP authentication support for admins

### `2026_02_19_113323_add_deleted_at_to_users_table.php`

Purpose:

- soft delete support for students

## 5. Audit and Security

### `2026_02_07_113448_create_security_logs_table.php`

Purpose:

- audit and security event storage

## 6. Question Bank and Content

### `2026_02_07_122824_create_questions_table.php`

Creates `questions`.

Important fields:

- `school_id`
- `class`
- `subject`
- `type`
- `question_text`
- `marks`
- `difficulty`
- `created_by`
- `status`

### `2026_02_11_123607_add_passage_to_questions_table.php`

Purpose:

- support passage-linked question content

### `2026_02_11_125122_create_passages_table.php`

Creates `passages`.

Purpose:

- reusable reading comprehension/content passages

### `2026_02_11_125220_add_passage_id_to_questions_table.php`

Purpose:

- relational link from question to passage

### `2026_02_11_165040_add_options_to_questions_table.php`

Purpose:

- extend question data for MCQ option support

### `2026_02_11_165445_add_options_to_questions_table.php`

Purpose:

- follow-up option-related schema change

## 7. Exam Core

### `2026_02_07_144123_create_exams_table.php`

Creates `exams`.

Important fields:

- `school_id`
- `title`
- `class`
- `subject`
- `total_marks`
- `duration_minutes`
- `instructions`
- `status`
- `created_by`

### `2026_02_07_144227_create_exam_schedules_table.php`

Creates `exam_schedules`.

Important fields:

- `exam_id`
- `start_at`
- `end_at`

### `2026_02_10_101703_add_exam_columns_to_exams_table.php`

Purpose:

- expands exam metadata

Likely areas based on current code:

- academic session
- exam type
- shuffle settings
- marking configuration

### `2026_02_10_102346_add_exam_columns_to_exam_schedules_table.php`

Purpose:

- adds scheduling-related metadata/extensions

### `2026_02_12_103629_add_selected_questions_to_exams_table.php`

Purpose:

- store selected question IDs directly on exam

### `2026_02_26_150228_add_rules_column_to_exams_table.php`

Purpose:

- store exam-specific rule overrides

## 8. Attempts, Answers, and Evaluation

### `2026_02_11_130000_create_exam_attempts_table.php`

Creates `exam_attempts`.

Important fields at creation time:

- `school_id`
- `user_id`
- `exam_id`
- `total_questions`
- `total_correct`
- `score`
- `started_at`
- `submitted_at`

### `2026_02_11_165450_create_user_exam_answers_table.php`

Purpose:

- stores per-question student answers

### `2026_02_13_120000_add_details_to_exam_attempts.php`

Purpose:

- extend attempts with runtime/session metadata

### `2026_02_13_120000_add_session_token_to_exam_attempts_table.php`

Purpose:

- bind attempts to session identity

### `2026_02_17_112250_add_tracking_columns_to_exam_attempts.php`

Purpose:

- activity tracking for live monitoring

### `2026_02_19_162639_add_marks_awarded_to_user_exam_answers.php`

Purpose:

- evaluator/manual marks support

### `2026_02_19_171654_add_approval_columns_to_exam_attempts.php`

Purpose:

- result approval workflow

### `2026_02_19_175349_modify_status_enum_in_exam_attempts.php`

Purpose:

- expands or refines attempt lifecycle statuses

### `2026_03_13_160509_add_admin_checked_to_user_exam_answers.php`

Purpose:

- mark answers reviewed by admin/evaluator

## 9. Proctoring and Monitoring

### `2026_02_13_102358_create_exam_violations_table.php`

Creates `exam_violations`.

Important fields:

- `attempt_id`
- `user_id`
- `type`
- `occurred_at`
- `ip_address`

### `2026_02_13_162113_create_exam_streams_table.php`

Creates `exam_streams`.

Important fields:

- `attempt_id`
- `viewer_id`
- `viewer_type`
- `viewer_session_id`
- `offer`
- `answer`
- ICE candidate fields
- `status`

Purpose:

- signaling and viewer tracking for live proctoring

### `2026_03_19_000002_create_exam_monitor_blocks_table.php`

Creates `exam_monitor_blocks` and adds `monitor_block_id` to `exam_attempts`.

Purpose:

- block-based monitoring assignment

Important fields:

- `exam_id`
- `name`
- `assignee_type`
- `assignee_id`

### `2026_03_19_000003_create_exam_monitor_block_student_table.php`

Creates `exam_monitor_block_student`.

Purpose:

- many-to-many mapping of students into monitor blocks

## 10. Notifications

### `2026_02_23_040000_create_notifications_table.php`

Initial notification table.

Original design:

- user-linked notifications

### `2026_02_23_050000_make_notifications_polymorphic.php`

Refactors notifications to polymorphic ownership.

Changes:

- `user_id` renamed to `notifiable_id`
- `notifiable_type` added

Purpose:

- allow notifications for students, admins, and superadmins

### `2026_03_18_000001_create_notification_sound_preferences_table.php`

Purpose:

- store per-notifiable sound preference for alerts

## 11. Settings and Configuration

### `2026_02_26_000000_add_exam_rules_to_schools_table.php`

Purpose:

- school-level exam rules storage

### `2026_02_26_000001_add_notification_settings_to_schools_table.php`

Purpose:

- school-level notification config

### `2026_02_26_100000_create_settings_table.php`

Creates `settings`.

Purpose:

- platform-wide key-value configuration store

### `2026_03_16_000000_create_school_settings_table.php`

Creates `school_settings`.

Purpose:

- school-specific key-value configuration store

## 12. Data Cleanup and Fix Migrations

### `2026_02_11_055500_drop_section_column_from_users_table.php`

Purpose:

- remove old or redundant student `section` implementation detail

Note:

This is notable because `section` is earlier introduced in another migration, meaning the schema evolved during development.

### `2026_02_13_123913_fix_exam_schedules_exam_id.php`

Purpose:

- corrective migration for exam schedule relationship/schema behavior

## 13. Suggested Reading Order for New Developers

For understanding the database quickly, read migrations in this order:

1. school and actor tables
2. question and exam tables
3. attempt/answer tables
4. proctoring and monitoring tables
5. notifications and settings tables

Best first files:

1. `2025_02_02_000000_create_schools_table.php`
2. `2025_02_03_000000_create_admins_table.php`
3. `2026_02_04_052734_create_super_admins_table.php`
4. `2026_02_06_000000_add_student_details_to_users_table.php`
5. `2026_02_07_122824_create_questions_table.php`
6. `2026_02_07_144123_create_exams_table.php`
7. `2026_02_11_130000_create_exam_attempts_table.php`
8. `2026_02_13_102358_create_exam_violations_table.php`
9. `2026_02_13_162113_create_exam_streams_table.php`
10. `2026_03_19_000002_create_exam_monitor_blocks_table.php`
