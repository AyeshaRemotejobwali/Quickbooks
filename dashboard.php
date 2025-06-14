<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}
$user_id = $_SESSION['user_id'];
$income = $pdo->query("SELECT SUM(amount) as total FROM transactions WHERE user_id = $user_id AND type = 'income'")->fetch()['total'] ?? 0;
$expenses = $pdo->query("SELECT SUM(amount) as total FROM transactions WHERE user_id = $user_id AND type = 'expense'")->fetch()['total'] ?? 0;
$balance = $income - $expenses;
$transactions = $pdo->query("SELECT * FROM transactions WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - QuickBooks Clone</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f4f7fa; }
        .sidebar { background: #1e3c72; color: #fff; width: 250px; height: 100vh; position: fixed; padding: 20px; }
        .sidebar h2 { font-size: 1.5em; margin-bottom: 20px; }
        .sidebar a { color: #fff; display: block; padding: 10px; text-decoration: none; margin: 5px 0; border-radius: 5px; }
        .sidebar a:hover { background: #4caf50; }
        .main-content { margin-left: 270px; padding: 20px; }
        .summary { display: flex; gap: 20px; margin-bottom: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); flex: 1; text-align: center; }
        .card h3 { font-size: 1.2em; margin-bottom: 10px; }
        .card p { font-size: 1.5em; color: #4caf50; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #1e3c72; color: #fff; }
        @media (max-width: 768px) { .sidebar { width: 100%; height: auto; position: static; } .main-content { margin-left: 0; } .summary { flex-direction: column; } }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>QuickBooks Clone</h2>
        <a href="javascript:void(0)" onclick="window.location.href='dashboard.php'">Dashboard</a>
        <a href="javascript:void(0)" onclick="window.location.href='invoices.php'">Invoices</a>
        <a href="javascript:void(0)" onclick="window.location.href='transactions.php'">Transactions</a>
        <a href="javascript:void(0)" onclick="window.location.href='reports.php'">Reports</a>
        <a href="javascript:void(0)" onclick="window.location.href='logout.php'">Logout</a>
    </div>
    <div class="main-content">
        <h2>Financial Overview</h2>
        <div class="summary">
            <div class="card">
                <h3>Total Income</h3>
                <p>$<?php echo number_format($income, 2); ?></p>
            </div>
            <div class="card">
                <h3>Total Expenses</h3>
                <p>$<?php echo number_format($expenses, 2); ?></p>
            </div>
            <div class="card">
                <h3>Balance</h3>
                <p>$<?php echo number_format($balance, 2); ?></p>
            </div>
        </div>
        <h3>Recent Transactions</h3>
        <table>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Category</th>
                <th>Description</th>
            </tr>
            <?php foreach ($transactions as $t): ?>
            <tr>
                <td><?php echo $t['transaction_date']; ?></td>
                <td><?php echo ucfirst($t['type']); ?></td>
                <td>$<?php echo number_format($t['amount'], 2); ?></td>
                <td><?php echo $t['category']; ?></td>
                <td><?php echo $t['description']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
