<?php
session_start();
if (isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='dashboard.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickBooks Clone - Financial Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: linear-gradient(135deg, #1e3c72, #2a5298); color: #fff; }
        header { background: rgba(0, 0, 0, 0.8); padding: 20px; text-align: center; }
        header h1 { font-size: 2.5em; margin-bottom: 10px; }
        nav a { color: #fff; margin: 0 15px; text-decoration: none; font-weight: bold; }
        nav a:hover { color: #4caf50; }
        .hero { text-align: center; padding: 50px; }
        .hero h2 { font-size: 2em; margin-bottom: 20px; }
        .hero p { font-size: 1.2em; margin-bottom: 30px; }
        .btn { background: #4caf50; color: #fff; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-size: 1.1em; }
        .btn:hover { background: #45a049; }
        .features { display: flex; justify-content: center; gap: 20px; padding: 50px; }
        .feature-card { background: rgba(255, 255, 255, 0.1); padding: 20px; border-radius: 10px; width: 300px; text-align: center; }
        .feature-card h3 { font-size: 1.5em; margin-bottom: 10px; }
        footer { background: rgba(0, 0, 0, 0.8); text-align: center; padding: 20px; position: fixed; bottom: 0; width: 100%; }
        @media (max-width: 768px) { .features { flex-direction: column; align-items: center; } .feature-card { width: 90%; } }
    </style>
</head>
<body>
    <header>
        <h1>QuickBooks Clone</h1>
        <nav>
            <a href="javascript:void(0)" onclick="window.location.href='signup.php'">Sign Up</a>
            <a href="javascript:void(0)" onclick="window.location.href='login.php'">Login</a>
        </nav>
    </header>
    <div class="hero">
        <h2>Manage Your Finances with Ease</h2>
        <p>Track income, expenses, create invoices, and generate financial reports all in one place.</p>
        <a href="javascript:void(0)" onclick="window.location.href='signup.php'" class="btn">Get Started</a>
    </div>
    <div class="features">
        <div class="feature-card">
            <h3>Invoicing</h3>
            <p>Create and send professional invoices to clients effortlessly.</p>
        </div>
        <div class="feature-card">
            <h3>Expense Tracking</h3>
            <p>Monitor your expenses and categorize transactions with ease.</p>
        </div>
        <div class="feature-card">
            <h3>Financial Reports</h3>
            <p>Generate insightful monthly and yearly financial summaries.</p>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 QuickBooks Clone. All rights reserved.</p>
    </footer>
</body>
</html>
