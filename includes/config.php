<?php
/**
 * Configuration File
 * Contains database credentials and application settings
 */

/**
 * Simple .env loader (no Composer)
 * Loads KEY=VALUE pairs into getenv(), $_ENV and $_SERVER.
 */
function loadEnvFile(string $path): void
{
    if (!is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name  = trim($name);
        $value = trim($value);

        // Remove surrounding quotes if present
        if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"')
            || (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
            $value = substr($value, 1, -1);
        }

        putenv("$name=$value");
        $_ENV[$name]    = $value;
        $_SERVER[$name] = $value;
    }
}

// Load .env from project root (one level up from includes/)
$envPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
loadEnvFile($envPath);

// Database Configuration (use env values with fallbacks)
$dbHost = getenv('DB_HOST');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASS');
$dbName = getenv('DB_NAME');

define('DB_HOST', $dbHost);
define('DB_USER', $dbUser);
define('DB_PASS', $dbPass);
define('DB_NAME', $dbName);

// Application Settings
$appName = getenv('APP_NAME') !== false ? getenv('APP_NAME') : 'Clock It - Attendance Tracker';
define('APP_NAME', $appName);

// BASE_URL should point to public folder for assets
$baseUrl = getenv('BASE_URL') !== false ? getenv('BASE_URL') : '/team-nieshaan-zoe/public';
define('BASE_URL', $baseUrl);

// Timezone (can be overridden by APP_TIMEZONE in .env)
$timezone = getenv('APP_TIMEZONE') !== false ? getenv('APP_TIMEZONE') : 'Africa/Johannesburg';
date_default_timezone_set($timezone);

// Error Reporting controlled by APP_DEBUG (true/false)
$appDebug = getenv('APP_DEBUG');
if ($appDebug === false) {
    $appDebug = '1'; // default to enabled during development
}
$appDebugBool = filter_var($appDebug, FILTER_VALIDATE_BOOLEAN);

if ($appDebugBool) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Session Configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
