# Project Overview

## Project Name

OEMS appears to be an online examination and live proctoring/monitoring platform for schools.

## Main Purpose

The system manages:

- school onboarding
- school admin and staff management
- student admission and profile management
- question bank creation
- exam creation and scheduling
- live exam monitoring
- exam violations and proctoring
- result review and reports
- notifications and audit/security logs

## Core Actor Model

The codebase operates with 3 main platform actors:

1. `superadmin`
2. `admin`
3. `student`

### 1. SuperAdmin

The superadmin layer is platform-level control.

Responsibilities:

- create and manage schools
- create or edit school admins
- manage all admins across schools
- manage sub-superadmins
- approve or reject staff requests
- review admin requests like block/unblock staff
- manage system-wide settings and permission matrices
- access global monitoring and reports

### 2. Admin

The admin layer is school-specific control.

Responsibilities:

- manage students for one school
- manage question bank and passages
- create and publish exams
- assign monitoring blocks
- monitor live exams
- manage school-level settings
- review results and generate reports

### 3. Student

The student layer is the exam delivery side.

Responsibilities:

- login and access dashboard
- review instructions and system check
- start live or mock exams
- submit answers
- send heartbeat and monitoring signals
- view results and marksheet
- manage profile and notifications

## Internal Admin Sub-Roles

Within the `admins` table, the current implementation uses:

- `school_admin`
- `sub_admin`
- `invigilator`
- `staff`

These are not separate top-level products. They are internal permission variants inside the admin guard.

## Current Technical Stack

Based on the repository structure, the project is built with:

- Laravel
- Blade views
- Eloquent ORM
- MySQL-style migrations
- Vite for frontend asset build
- Laravel authentication guards for multiple actor types

## Main High-Level Architecture

The application is organized around:

- `app/Models`: domain and persistence layer
- `app/Http/Controllers`: request/response and module workflows
- `app/Http/Middleware`: auth, school status, and access rules
- `resources/views`: Blade-based admin, superadmin, and student UI
- `routes/web.php`: route registration for all panels
- `database/migrations`: schema history

## Authentication Design

The project uses multi-actor authentication:

- `superadmin` guard for platform admins
- `admin` guard for school-side admins/staff
- default `web` user flow for students

This is visible from route middleware such as:

- `auth:superadmin`
- `auth:admin`
- `auth`

## Data Ownership Model

The center of ownership is `school_id`.

Most school-bound data is scoped by school:

- admins
- students/users
- exams
- attempts
- settings
- reports

This is an important architectural rule because it prevents one school from accessing another school's data.

## Key Domain Objects

Important domain entities include:

- `School`
- `SuperAdmin`
- `Admin`
- `User` for students
- `Question`
- `Passage`
- `Exam`
- `ExamSchedule`
- `ExamAttempt`
- `UserExamAnswer`
- `ExamViolation`
- `ExamStream`
- `ExamMonitorBlock`
- `Notification`
- `Setting`
- `SchoolSetting`

## Current Codebase Reality

A few implementation realities worth documenting:

- `routes/web.php` is doing a lot of work and contains large grouped route definitions.
- Admin access is controlled both by role logic and settings-driven sidebar section access.
- The project includes both platform-wide settings (`settings`) and school-specific overrides (`school_settings`).
- Notifications were evolved from user-only to polymorphic notifications.
- Live monitoring is block-based and can be assigned to admins or superadmins through polymorphic assignees.
