-- Add status column to the appointments table
ALTER TABLE appointments ADD COLUMN status VARCHAR(20) DEFAULT 'pending'; 