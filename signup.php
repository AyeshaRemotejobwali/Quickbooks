<?php
session_start();
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $full_name = $_POST['full_name'];
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, $full_name]);
        echo "<script>alert('Registration successful! Please login.'); window.location.href='login.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - QuickBooks Clone</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: linear-gradient(135deg, #1e3c72, #2a5298); color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .signup-container { background: rgba(255, 255, 255, 0.1); padding: 40px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); width: 400px; text-align: center; }
        h2 { font-size: 2em; margin-bottom: 20px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: none; border-radius: 5px; font-size: 1em; }
        button { background: #4caf50; color: #fff; padding: 10px; border: none; border-radius: 5px; width: 100%; cursor: pointer; font-size: 1.1em; }
        button:hover { background: #45a049; }
        a { color: #4caf50; text-decoration: none; }
        a:hover { text-decoration: underline; }
        @media (max-width: 480px) { .signup-container { width: 90%; } }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="full_name" placeholder="Full Name" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="javascript:void(0)" onclick="window.location.href='login.php'">Login</a></p>
    </div>
</body>
</html>
