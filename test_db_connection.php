<?php

require 'vendor/autoload.php'; // Make sure to include the autoload file

$dotenvPath = __DIR__ . '/.env';
if (file_exists($dotenvPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} else {
    die('.env file not found');
}

$servername = env('DB_HOST');
$username = env('DB_USERNAME');
$password = env('DB_PASSWORD');
$database = env('DB_DATABASE');

// Debugging output
var_dump($servername, $username, $password, $database);

// Attempt to connect to the database
$mysqli = new mysqli($servername, $username, $password, $database);

// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "Connected successfully";