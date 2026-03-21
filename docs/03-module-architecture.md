# Module Architecture

## 1. Identity and Access Module

### Main Files

- `app/Models/SuperAdmin.php`
- `app/Models/Admin.php`
- `app/Models/User.php`
- `app/Http/Middleware/EnsureAdminRole.php`
- `app/Http/Middleware/EnsureAdminSectionAccess.php`
- `app/Http/Middleware/CheckSchoolActive.php`

### Responsibility

This module handles:

- actor identity
- role checks
- section-level access
- school active/inactive restrictions
- login guard separation

### Key Design Point

The project has 3 top-level actors, but `admin` contains internal sub-role logic. That means access control is partly actor-based and partly role-based.

## 2. School Management Module

### Core Objects

- `School`
- school admin creation/update
- school settings

### Responsibility

This module is responsible for:

- creating schools
- maintaining school profile data
- activating/suspending schools
- attaching admins and students to the school

### Data Anchor

`school_id` is the core multi-tenant boundary for the app.

## 3. Staff and Admin Workflow Module

### Core Tables/Models

- `admins`
- `staff_requests`
- `admin_requests`

### Responsibility

This module covers:

- school-side staff onboarding wizard
- pending verification flow
- superadmin approval/rejection
- internal admin management
- admin block/unblock requests

### Technical Note

`staff_type` is a profile classification field.

Examples:

- `teacher`
- `admin_staff`
- `librarian`
- `lab_assistant`

`role` is the system access field.

Examples:

- `school_admin`
- `sub_admin`
- `invigilator`
- `staff`

This distinction is important because `staff_type` and `role` serve different purposes.

## 4. Student Management Module

### Core Tables/Models

- `users`
- student-related controllers

### Responsibility

This module handles:

- student creation
- bulk import
- batch assignment
- status changes
- transfer/reset actions
- profile and result access

### Technical Note

Students are stored in `users`, with `role = student`.

## 5. Question Bank Module

### Core Tables/Models

- `questions`
- `question_options`
- `passages`

### Responsibility

This module handles:

- question CRUD
- passage-based content
- MCQ options
- class and subject segmentation
- difficulty and marks metadata

## 6. Exam Lifecycle Module

### Core Tables/Models

- `exams`
- `exam_schedules`
- `exam_attempts`
- `user_exam_answers`

### Responsibility

This module handles:

- exam creation
- question attachment
- scheduling
- publish/close lifecycle
- attempt tracking
- score and answer storage
- evaluation and approval flow

### Lifecycle Summary

1. Create exam metadata.
2. Attach questions.
3. Create schedule.
4. Publish exam.
5. Student starts attempt.
6. Answers and monitoring data are recorded.
7. Attempt is submitted or terminated.
8. Results are reviewed and reported.

## 7. Proctoring and Monitoring Module

### Core Tables/Models

- `exam_violations`
- `exam_streams`
- `exam_monitor_blocks`
- `exam_monitor_block_student`

### Responsibility

This module handles:

- live monitoring access
- attempt-level stream requests
- viewer signaling
- violation capture
- block-based student segmentation
- invigilator/staff monitoring scope

### Design Pattern

The monitor assignment system uses polymorphism:

- `exam_monitor_blocks.assignee_type`
- `exam_monitor_blocks.assignee_id`

So a block can be assigned to different actor models, such as admin-side users or superadmin-side users.

## 8. Notification Module

### Core Tables/Models

- `notifications`
- `notification_sound_preferences`

### Responsibility

This module handles:

- unread/read state
- redirectable action notifications
- per-actor notification history
- sound preference storage

### Technical Note

Notifications were originally user-bound and later migrated to polymorphic notifiables.

## 9. Reporting and Audit Module

### Core Tables/Models

- `security_logs`
- report controllers

### Responsibility

This module handles:

- login/activity auditing
- security export
- exam analytics
- violation reporting
- school analytics

## 10. Settings and Configuration Module

### Core Tables/Models

- `settings`
- `school_settings`

### Responsibility

This module handles:

- default rules
- proctoring settings
- anti-cheat settings
- role/sidebar permission matrix
- school-level override values

## 11. Route Architecture

The route design is prefix-based:

- `/superadmin/*`
- `/admin/*`
- `/student/*`

This is a clean high-level separation.

### Current Maintenance Note

`routes/web.php` is large and contains some repeated sections in the admin and student areas. It works as the central route file, but long-term maintainability would improve if it were split by actor/module.
