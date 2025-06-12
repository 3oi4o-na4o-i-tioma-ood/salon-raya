-- Fix appointments table by adding missing duration_minutes column
USE salon_raya;

-- Add duration_minutes column if it doesn't exist
ALTER TABLE appointments 
ADD COLUMN IF NOT EXISTS duration_minutes INT DEFAULT 60 AFTER comment;

-- Add status column if it doesn't exist (for admin panel)
ALTER TABLE appointments 
ADD COLUMN IF NOT EXISTS status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending' AFTER duration_minutes;

-- Add cancellation_token column if it doesn't exist (for cancellation links)
ALTER TABLE appointments 
ADD COLUMN IF NOT EXISTS cancellation_token VARCHAR(64) AFTER status;

-- Show final table structure
DESCRIBE appointments; 