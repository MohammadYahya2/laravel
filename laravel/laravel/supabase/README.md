# Supabase Database Cleanup

This folder contains scripts to clean up the Supabase database, keeping only the required tables for this project.

## Required Tables

The project requires only these 5 tables:
1. `users` - User information
2. `courses` - Course information
3. `categories` - Categories information
4. `lessons` - Lesson information
5. `user_course` - Pivot table connecting users and courses

## How to Run the Cleanup Script

1. Log in to your Supabase dashboard
2. Navigate to the SQL Editor
3. Copy the contents of the `cleanup_tables.sql` file
4. Paste the SQL into the editor
5. Run the query

This will:
- Create the `user_course` table if it doesn't exist
- Drop all tables except the 5 required tables (plus the `migrations` table which is needed by Laravel)
- Display a list of remaining tables for verification

## Verification

After running the script, you should only see the following tables in your database:
- users
- courses
- categories
- lessons
- user_course
- migrations

Any other tables will be removed from the database.

## Important Note

Make sure to back up your database before running this script if you have important data in any of the other tables. 