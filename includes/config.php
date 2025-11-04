<?php
/**
 * Configuration File
 * Contains database credentials and application settings
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'MySQL15B4d#00');
define('DB_NAME', 'tracker_db');

// Application Settings
define('APP_NAME', 'Clock It - Attendance Tracker');
// Changed to point to public folder for assets (CSS, JS, images)
// Views are loaded through the public/front controller so BASE_URL should point at the public folder
define('BASE_URL', '/team-nieshaan-zoe/public');

// Timezone
date_default_timezone_set('Africa/Johannesburg');

// Error Reporting (Set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session Configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
