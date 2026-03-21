# Redis and System Scaling

## Purpose

This document explains the Redis-related setup and system-handling optimizations that were added to the OEMS project to make login, exam start, exam submission, and operational monitoring smoother under concurrent traffic.

This is a project-specific implementation note, not a generic Redis tutorial.

## What Was Added

The following areas were improved:

- Redis-backed cache, session, and queue config alignment
- Redis health verification tools
- superadmin dashboard system-health metrics
- superadmin dashboard participation trend chart
- exam question payload caching
- exam cache warm-up on publish
- queued violation notifications
- batched answer inserts during exam submission
- performance indexes for hot exam tables
- superadmin sidebar infrastructure links

## Redis Configuration

### Environment

Redis is configured in `.env` with:

- `CACHE_STORE=redis`
- `SESSION_DRIVER=redis`
- `QUEUE_CONNECTION=redis`
- `SESSION_CONNECTION=session`
- `SESSION_STORE=redis`
- `REDIS_DB=0`
- `REDIS_CACHE_CONNECTION=cache`
- `REDIS_CACHE_DB=1`
- `REDIS_SESSION_DB=2`
- `REDIS_QUEUE_CONNECTION=queue`
- `REDIS_QUEUE_DB=3`

### Config Files Updated

- `config/database.php`
- `.env`

### Why This Matters

This separates Redis usage into logical areas:

- default Redis connection
- cache Redis database
- session Redis database
- queue Redis database

This helps avoid one concern interfering with another under load.

## Redis Health Verification

### Web Route

A local/staging-only health route was added:

- `/redis-health`

Location:

- `routes/web.php`

It reports:

- Redis ping
- current cache store
- session driver
- queue connection
- cache roundtrip result

### Artisan Command

A console test command was added:

- `php artisan redis:test`

Location:

- `routes/console.php`

It verifies:

- Redis connectivity
- cache write/read roundtrip
- session config alignment
- queue Redis connection alignment

## SuperAdmin Dashboard Improvements

### System Health

The old hardcoded "System Health" block was replaced with dynamic runtime metrics.

Location:

- `app/Http/Controllers/SuperAdmin/DashboardController.php`
- `resources/views/superadmin/dashboard.blade.php`

Current metrics:

- Server Load
- Database Connections
- Redis Status
- Redis Queue
- Active Live Students
- Failed Jobs
- Failed Logins (24h)
- Storage Usage

### Exam Participation Trends

The old placeholder chart was replaced with a real 7-day participation trend.

Current values shown:

- Peak Attempts
- 7 Day Attempts
- Today
- Daily bar chart for last 7 days

Data source:

- `exam_attempts.started_at`
- fallback `exam_attempts.created_at`

## SuperAdmin Sidebar Infrastructure Update

The Infrastructure section in the superadmin sidebar was updated to point to real operational destinations.

Location:

- `resources/views/layouts/superadmin.blade.php`

Current infrastructure items:

- System Health Overview
- Security & Audit Logs
- App & Mail Settings
- Notification Delivery
- Redis Health Route (marked local only)

## Exam Scaling Optimizations

### 1. Exam Question Payload Cache

A reusable cache helper was added:

- `app/Support/ExamPayloadCache.php`

Purpose:

- cache selected exam question payload once
- reduce repeated DB reads when many students start the same exam
- reuse cached question payload during student live exam flow

Cached data includes:

- question id
- question text
- marks
- options
- correct option

Student live exam uses cache-first retrieval and removes `correct_option` for non-mock exams before rendering.

### 2. Cache Warm on Publish / Forget on Update

Admin exam flow was updated to manage the cache lifecycle.

Location:

- `app/Http/Controllers/Admin/ExamController.php`

Current behavior:

- when questions are changed, cache is forgotten
- when exam is published, cache is warmed
- when mock exam auto-publishes, cache is warmed
- when exam is updated and still published, cache is rewarmed
- when exam is closed, cache is forgotten

### 3. Queue Violation Notifications

A queued job was added:

- `app/Jobs/SendExamViolationNotifications.php`

Student violation flow now dispatches this job instead of creating notification rows inline during the request.

Updated location:

- `app/Http/Controllers/Student/ExamController.php`

Why this matters:

- the violation endpoint returns faster
- heavy notification creation does not block the student request
- under traffic spikes, the queue smooths the workload

Important:

- queue worker must be running for these notifications to process

### 4. Batch Insert for Answer Saving

Exam submission evaluation was optimized.

Location:

- `app/Services/ExamAutoEvaluationService.php`

Old behavior:

- one `UserExamAnswer::create()` per question

New behavior:

- builds rows in memory
- inserts answers in one batch with `UserExamAnswer::insert()`

Why this matters:

- fewer queries during submission
- faster finish for students
- lower DB write overhead during concurrent submissions

### 5. Performance Indexes

A migration was added:

- `database/migrations/2026_03_21_000001_add_performance_indexes_for_exam_scale.php`

Indexes were added for hot read/write paths on:

- `users`
- `exams`
- `exam_attempts`
- `user_exam_answers`
- `exam_violations`
- `exam_streams`

Purpose:

- speed up lookup queries used during login, exam start, monitoring, violation logging, and result processing

## Key Files Added

- `app/Support/ExamPayloadCache.php`
- `app/Jobs/SendExamViolationNotifications.php`
- `database/migrations/2026_03_21_000001_add_performance_indexes_for_exam_scale.php`

## Key Files Updated

- `.env`
- `config/database.php`
- `routes/web.php`
- `routes/console.php`
- `app/Http/Controllers/Admin/ExamController.php`
- `app/Http/Controllers/Student/ExamController.php`
- `app/Http/Controllers/SuperAdmin/DashboardController.php`
- `app/Services/ExamAutoEvaluationService.php`
- `resources/views/superadmin/dashboard.blade.php`
- `resources/views/layouts/superadmin.blade.php`

## What Redis Is Doing in This Project

Redis is currently useful for:

- sessions
- cache
- queue
- health validation
- dashboard operational visibility

Redis is not replacing MySQL.

Core exam data still stays in MySQL:

- exams
- attempts
- answers
- violations
- streams
- students

Redis is acting as an accelerator and workload smoother.

## What Still Requires Server Setup

Code-level Redis support is in place, but server behavior still depends on infrastructure.

You still need:

- Redis service installed/running on server
- queue worker running
- migrations executed
- app config cleared/rebuilt after deployment

Recommended commands:

```bash
php artisan migrate
php artisan optimize:clear
php artisan redis:test
php artisan queue:work redis --tries=3 --timeout=120
```

## How to Verify Everything

### 1. Redis Basic Verification

- open `/redis-health`
- run `php artisan redis:test`

### 2. Session Verification

- login as superadmin/admin/student
- refresh browser
- confirm session remains active

### 3. Queue Verification

- trigger a violation notification
- ensure queue worker is running
- verify notification appears after job processing

### 4. Dashboard Verification

Go to superadmin dashboard and verify:

- system health values are dynamic
- participation chart shows real numbers

### 5. Exam Performance Verification

Start the same exam from multiple student accounts and verify:

- exam open is smooth
- question load is fast
- submit is faster than before

## Important Limitation

Redis improves performance, but it does not automatically solve all scaling issues by itself.

For high concurrency, system health still depends on:

- MySQL performance
- queue worker availability
- server CPU and RAM
- polling frequency
- network quality

## Recommended Next Optimizations

If traffic increases further, the next best improvements are:

- move heartbeat activity tracking partly to Redis
- reduce monitoring polling pressure
- add more background jobs for heavy admin notifications
- add failed-job dashboard tools
- move to Linux + Horizon if managed queue monitoring is needed
