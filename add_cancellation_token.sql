-- Add cancellation_token column to the appointments table
ALTER TABLE appointments ADD COLUMN cancellation_token VARCHAR(255) NULL; 