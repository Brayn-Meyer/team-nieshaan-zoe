<?php
    require __DIR__ . '/vendor/autoload.php';  // autoload from Composer

    use Dotenv\Dotenv;

    // Load the .env file
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // Assign variables from .env
    $db_server   = $_ENV['DB_SERVER'];
    $db_user     = $_ENV['DB_USER'];
    $db_password = $_ENV['DB_PASSWORD'];
    $db_name     = $_ENV['DB_NAME'];

    try {
        $pdo = new PDO("mysql:host=$db_server;dbname=$db_name", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected successfully!";
    } catch (PDOException $e) {
        die("Could not connect to the database: " . $e->getMessage());
    }
?>