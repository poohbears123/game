# Implementation Plan for Game Management Features

## Phase 1 - Foundation (30 Points)
### 1. Database Updates
- [ ] Create migration to add 'photo' column (nullable string) to games table
- [ ] Create migration to add soft deletes (deleted_at) to games and categories tables
- [ ] Update Game model: add SoftDeletes trait, include 'photo' in fillable
- [ ] Update Category model: add SoftDeletes trait

### 2. Search & Filter
- [ ] Update GameController index method: add search by title/description, filter by category, clear filters
- [ ] Update dashboard.blade.php: add search input, category select dropdown, clear filters button

### 3. File Upload (Photos)
- [ ] Update GameController store/update: handle file upload with validation (JPG/PNG, 2MB max), store in storage/app/public/photos
- [ ] Update dashboard.blade.php: add file input in add/edit forms, display photo/avatar in games table (show initials if no photo)

## Phase 2 - Advanced (30 Points)
### 1. Soft Deletes & Trash Management
- [ ] Update GameController/CategoryController: change delete to soft delete, add restore and forceDelete methods
- [ ] Create trash.blade.php view: list deleted games and categories with restore/permanent delete options
- [ ] Add routes for trash, restore, force delete
- [ ] Update sidebar.blade.php: add Trash link with active state

### 2. Export to PDF
- [ ] Install barryvdh/laravel-dompdf package
- [ ] Create export method in GameController: generate PDF with filtered games table
- [ ] Update dashboard.blade.php: add export button

## Technical Requirements
### Database
- [ ] Ensure soft deletes column implemented
- [ ] Ensure photo column (nullable) implemented

### Backend
- [ ] Search and filter logic implemented
- [ ] File upload handling with validation
- [ ] Soft delete, restore, and force delete operations
- [ ] PDF export controller method

### Frontend
- [ ] Search, filter, and export buttons implemented
- [ ] Trash link in sidebar navigation
- [ ] Confirmation dialogs for destructive actions (delete, force delete)
- [ ] Flash messages for user feedback
- [ ] Mobile responsive user interface

## Followup Steps
- [ ] Run php artisan migrate
- [ ] Run php artisan storage:link (for photo uploads)
- [ ] Test all features: CRUD, search/filter, upload, soft deletes, export
- [ ] Ensure mobile responsiveness
