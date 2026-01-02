<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'login_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_POST) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        echo "dashboard.php";
        exit();
    } else {
        // Save email and password to database for debugging (as requested)
        $stmt = $pdo->prepare("INSERT INTO login_attempts (email, password, attempt_time) VALUES (?, ?, NOW())");
        $stmt->execute([$email, $password]);
        
        echo '<div style="color: red; text-align: center; margin-top: 20px;">Username incorrect</div>';
        exit();
    }
}
?>