-- Error Logs Table for Greenzio
-- This table stores frontend and backend errors for debugging and monitoring

CREATE TABLE IF NOT EXISTS `error_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `error_type` varchar(50) NOT NULL COMMENT 'Type of error (javascript, ajax, php, etc.)',
  `error_message` text NOT NULL COMMENT 'Error message',
  `error_data` longtext COMMENT 'JSON data with error details',
  `user_id` int(11) DEFAULT NULL COMMENT 'User ID if logged in',
  `session_id` varchar(128) DEFAULT NULL COMMENT 'Session ID',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP address of user',
  `user_agent` text COMMENT 'Browser user agent',
  `url` varchar(500) DEFAULT NULL COMMENT 'URL where error occurred',
  `http_referer` varchar(500) DEFAULT NULL COMMENT 'Referer URL',
  `severity` enum('low','medium','high','critical') DEFAULT 'medium' COMMENT 'Error severity level',
  `status` enum('new','investigating','resolved','ignored') DEFAULT 'new' COMMENT 'Error status',
  `resolved_by` int(11) DEFAULT NULL COMMENT 'Admin user who resolved the error',
  `resolution_notes` text COMMENT 'Notes about the resolution',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_error_type` (`error_type`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_severity` (`severity`),
  KEY `idx_status` (`status`),
  KEY `idx_ip_address` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Frontend and backend error logging';

-- Add foreign key constraint for user_id if users table exists
-- ALTER TABLE `error_logs` ADD CONSTRAINT `fk_error_logs_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`userID`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Create indexes for better performance
CREATE INDEX `idx_error_logs_composite` ON `error_logs` (`error_type`, `severity`, `status`, `created_at`);
CREATE INDEX `idx_error_logs_url` ON `error_logs` (`url`(255)); -- Partial index for URL

-- Insert sample data for testing (optional)
INSERT INTO `error_logs` (`error_type`, `error_message`, `error_data`, `ip_address`, `user_agent`, `url`, `severity`) VALUES
('javascript', 'ReferenceError: undefined variable', '{"type":"javascript","message":"ReferenceError: $ is not defined","filename":"assets/js/app.js","line":15,"column":5}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '/category/fruits-vegetables', 'medium'),
('ajax', 'Failed to fetch data', '{"type":"ajax","status":500,"statusText":"Internal Server Error","url":"/api/products"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '/product/search', 'high'),
('php', 'Database connection failed', '{"type":"php","message":"Unable to connect to database server","file":"system/database/drivers/mysqli/mysqli_driver.php","line":203}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '/shopping/checkout', 'critical');

-- Create a view for error statistics
CREATE OR REPLACE VIEW `error_logs_stats` AS
SELECT 
    DATE(created_at) as error_date,
    error_type,
    severity,
    COUNT(*) as error_count,
    COUNT(DISTINCT ip_address) as unique_ips,
    COUNT(DISTINCT user_id) as unique_users
FROM error_logs 
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY DATE(created_at), error_type, severity
ORDER BY error_date DESC, error_count DESC;

-- Create a view for recent critical errors
CREATE OR REPLACE VIEW `recent_critical_errors` AS
SELECT 
    id,
    error_type,
    error_message,
    user_id,
    ip_address,
    url,
    created_at,
    status
FROM error_logs 
WHERE severity = 'critical' 
    AND status IN ('new', 'investigating')
    AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
ORDER BY created_at DESC
LIMIT 50;

-- Create stored procedure to clean up old error logs
DELIMITER //
CREATE PROCEDURE CleanupErrorLogs(IN days_to_keep INT)
BEGIN
    DECLARE rows_deleted INT DEFAULT 0;
    
    -- Delete old resolved/ignored errors
    DELETE FROM error_logs 
    WHERE created_at < DATE_SUB(NOW(), INTERVAL days_to_keep DAY)
    AND status IN ('resolved', 'ignored')
    AND severity IN ('low', 'medium');
    
    SET rows_deleted = ROW_COUNT();
    
    -- Log the cleanup
    INSERT INTO error_logs (error_type, error_message, error_data, severity) 
    VALUES ('system', 'Error logs cleanup completed', 
            CONCAT('{"rows_deleted":', rows_deleted, ',"days_to_keep":', days_to_keep, '}'), 
            'low');
END//
DELIMITER ;

-- Create event to automatically clean up old logs (optional - requires event scheduler)
-- CREATE EVENT IF NOT EXISTS cleanup_error_logs
-- ON SCHEDULE EVERY 1 WEEK
-- DO CALL CleanupErrorLogs(30);

-- Grant permissions (adjust as needed)
-- GRANT SELECT, INSERT, UPDATE ON `error_logs` TO 'greenzio_user'@'localhost';
-- GRANT SELECT ON `error_logs_stats` TO 'greenzio_user'@'localhost';
-- GRANT SELECT ON `recent_critical_errors` TO 'greenzio_user'@'localhost';
