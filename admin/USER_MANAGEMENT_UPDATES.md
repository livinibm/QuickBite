# User Management Updates - NIC Field Addition

## Changes Made

### 1. Database Changes
- Added NIC column to the users table
- SQL script provided: `admin/database/add_nic_column.sql`
- Run this script to add the NIC column if it doesn't exist in your database

### 2. Add User Modal Updates
- Added User ID display field (read-only, shows "Auto-generated")
- Added NIC input field with validation
- Updated form validation and submission

### 3. Edit User Modal Updates
- Added NIC input field
- Updated JavaScript function to populate NIC field when editing

### 4. Admin.php Controller Updates
- Updated `add_user` case to include NIC data
- Updated `update_user` case to include NIC data
- Form data now includes the NIC field

### 5. Admin Model Updates
- Updated `addUser()` method to include NIC in database insertion
- Updated `updateUser()` method to include NIC in database updates
- Both methods now handle the NIC column properly

### 6. Users Table Display Updates
- Added User ID column to the users table
- Added NIC column to the users table
- Updated table headers and data rows
- Updated colspan for empty state message

### 7. JavaScript Enhancements
- Added NIC validation function for Sri Lankan NIC formats:
  - Old format: 9 digits + V/v/X/x
  - New format: 12 digits
- Real-time validation with visual feedback
- Updated editUser function to populate NIC field

## Database Schema Update Required

Run the following SQL to add the NIC column:

```sql
ALTER TABLE `users` 
ADD COLUMN `nic` VARCHAR(15) NOT NULL DEFAULT '' 
AFTER `contact_number`;
```

## NIC Validation Rules
- Old NIC format: 9 digits followed by V, v, X, or x
- New NIC format: 12 digits
- Client-side validation provides immediate feedback
- Server-side validation should be added for security

## Usage
1. Run the SQL script to add the NIC column to your database
2. The add user form now includes NIC field
3. The edit user form now includes NIC field
4. The users table displays User ID and NIC columns
5. NIC validation ensures proper format before submission

## Files Modified
- `admin/admin.php` - Main admin interface
- `admin/models/Admin.php` - Admin model with database operations
- `admin/database/add_nic_column.sql` - Database schema update (new file)
