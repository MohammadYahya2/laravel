-- This script will clean up the Supabase database to keep only the required tables
-- Keep these tables: users, courses, categories, lessons, user_course

-- First, create the user_course table if it doesn't exist
CREATE TABLE IF NOT EXISTS user_course (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    course_id BIGINT NOT NULL REFERENCES courses(id) ON DELETE CASCADE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, course_id)
);

-- Now drop all unwanted tables
-- Check if each table exists before dropping to avoid errors
DO $$
DECLARE
    tables_to_keep TEXT[] := ARRAY['users', 'courses', 'categories', 'lessons', 'user_course', 'migrations'];
    all_tables TEXT[];
    table_name TEXT;
BEGIN
    -- Get all tables in the public schema
    SELECT ARRAY_AGG(tablename) INTO all_tables
    FROM pg_tables
    WHERE schemaname = 'public';
    
    -- Loop through all tables and drop those not in the keep list
    FOREACH table_name IN ARRAY all_tables
    LOOP
        IF NOT table_name = ANY(tables_to_keep) THEN
            EXECUTE 'DROP TABLE IF EXISTS "' || table_name || '" CASCADE';
            RAISE NOTICE 'Dropped table: %', table_name;
        END IF;
    END LOOP;
END $$;

-- List all remaining tables to verify
SELECT tablename FROM pg_tables WHERE schemaname = 'public'; 