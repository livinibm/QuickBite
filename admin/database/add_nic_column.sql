-- Add NIC column to users table
-- Run this script if the NIC column doesn't exist in your users table

-- Check if the column exists first, then add it if it doesn't
ALTER TABLE `users` 
ADD COLUMN `nic` VARCHAR(15) NOT NULL DEFAULT '' 
AFTER `contact_number`;

-- Optional: Add an index on NIC for better performance
-- ALTER TABLE `users` ADD INDEX `idx_nic` (`nic`);

-- Update existing users with placeholder NIC values (optional)
-- You may want to manually update these with real values
-- UPDATE `users` SET `nic` = 'PENDING' WHERE `nic` = '' OR `nic` IS NULL;
