# OEMS Technical Documentation

This folder contains project-level technical documentation for the current OEMS codebase.

## Files

- `01-project-overview.md`: high-level summary of the product, stack, actors, and architecture.
- `02-role-flow.md`: role-wise functional flow for `superadmin`, `admin`, and `student`.
- `03-module-architecture.md`: module-by-module breakdown of the main business areas.
- `04-migration-map.md`: migration inventory with grouped explanations of schema evolution.

## Import- `05-redis-and-system-scaling.md`: Redis setup, system health monitoring, exam scaling improvements, and operational verification notes.
ant Note

At product level, the platform has 3 main actor types:

- `superadmin`
- `admin`
- `student`

Inside the `admin` actor, the codebase currently uses sub-roles:

- `school_admin`
- `sub_admin`
- `invigilator`
- `staff`

So in documentation:

- "Admin" means the overall school-side panel/guard.
- "Admin sub-role" means the internal permission role stored in `admins.role`.

## Documentation Scope

This documentation is based on the current implementation in:

- `routes/web.php`
- `app/Models/*`
- `app/Http/Controllers/*`
- `database/migrations/*`

It is intended as a technical handover/reference document for development, onboarding, and future maintenance.

