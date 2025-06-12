-- Add trash functionality to reviews table
USE salon_raya;

ALTER TABLE reviews 
ADD COLUMN is_deleted BOOLEAN DEFAULT FALSE,
ADD COLUMN deleted_at TIMESTAMP NULL;

-- Update existing reviews to ensure they are not deleted
UPDATE reviews SET is_deleted = FALSE WHERE is_deleted IS NULL; 