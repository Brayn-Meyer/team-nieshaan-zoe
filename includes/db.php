<?php
// ==================================================
// DB connection without Composer / Dotenv
// ==================================================

// Simple function to load .env file
function loadEnv($path)
{
    if (!file_exists($path)) {
        throw new Exception(".env file not found at: $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments or invalid lines
        if (trim($line) === '' || str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, "\"' ");

        // Set environment variables
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

// Load .env file (adjust path if needed)
try {
    loadEnv(__DIR__ . '../../.env');
} catch (Exception $e) {
    die("Error loading .env: " . $e->getMessage());
}

// Assign variables from environment
$db_server   = $_ENV['DB_SERVER']   ?? 'localhost';
$db_user     = $_ENV['DB_USER']     ?? 'root';
$db_password = $_ENV['DB_PASSWORD'] ?? '';
$db_name     = $_ENV['DB_NAME']     ?? '';

try {
    $pdo = new PDO("mysql:host=$db_server;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully!";
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>
