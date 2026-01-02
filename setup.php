<?php
// Database setup script
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Create database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS login_db");
    $pdo->exec("USE login_db");
    
    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Create login_attempts table to store failed login attempts
    $pdo->exec("CREATE TABLE IF NOT EXISTS login_attempts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL,
        attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Insert a test user
    $testPassword = password_hash('password123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute(['testuser', 'test@example.com', $testPassword]);
    
    echo "Database setup completed successfully!<br>";
    echo "Test user created: email = test@example.com, password = password123";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>