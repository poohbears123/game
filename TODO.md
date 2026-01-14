# Game Management App - New Features Implementation

## Phase 1 – Foundation (Search & Filter, File Upload)

### 1. Search & Filter
- [x] Update GameController index to accept search (by title) and filter (by category) parameters, apply them to the query.
- [x] Update dashboard.blade.php to add search input field, category filter dropdown, and clear filters button above the games table.

### 2. File Upload
- [x] Update GameController store and update methods to handle photo upload: validate JPG/PNG formats, max 2MB size, store in storage/app/public/games.
- [x] Update dashboard.blade.php to add file input in add and edit forms, display uploaded photo or initials in the games table.

## Phase 2 – Advanced (Soft Deletes & Trash, Export to PDF)

### 1. Soft Deletes & Trash Management
- [x] Update GameController destroy to use soft delete instead of permanent delete.
- [x] Add trash method to display trashed games.
- [x] Add restore and forceDelete methods for restoring and permanently deleting trashed games.
- [x] Add routes for trash, restore, forceDelete.
- [x] Update dashboard.blade.php to add "Trash" link in sidebar navigation.
- [x] Create resources/views/trash.blade.php view similar to dashboard but for trashed games, with restore and permanent delete options.

### 2. Export to PDF
- [x] Add export method in GameController to generate PDF of current filtered games using dompdf.
- [x] Create resources/views/pdf/games.blade.php view for PDF layout.
- [x] Update dashboard.blade.php to add "Export to PDF" button that triggers the export.

## Followup Steps
- [x] Ensure storage link is created for public files.
- [x] Run any pending migrations.
- [x] Test all new features manually and update tests accordingly.
- [x] Fix failed test in GameCrudTest (undefined variable $data in update method).
- [x] Show trashed categories in Trash view with restore and force delete options.
- [x] Verify mobile responsiveness and flash messages.
