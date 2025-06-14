<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}
$user_id = $_SESSION['user_id'];
$monthly = $pdo->query("SELECT MONTH(transaction_date) as month, YEAR(transaction_date) as year, SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income, SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expenses FROM transactions WHERE user_id = $user_id GROUP BY YEAR(transaction_date), MONTH(transaction_date)")->fetchAll();
$yearly = $pdo->query("SELECT YEAR(transaction_date) as year, SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income, SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expenses FROM transactions WHERE user_id = $user_id GROUP BY YEAR(transaction_date)")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - QuickBooks Clone</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f4f7fa; }
        .sidebar { background: #1e3c72; color: #fff; width: 250px; height: 100vh; position: fixed; padding: 20px; }
        .sidebar h2 { font-size: 1.5em; margin-bottom: 20px; }
        .sidebar a { color: #fff; display: block; padding: 10px; text-decoration: none; margin: 5px 0; border-radius: 5px; }
        .sidebar a:hover { background: #4caf50; }
        .main-content { margin-left: 270px; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; margin-bottom: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #1e3c72; color: #fff; }
        canvas { max-width: 100%; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        @media (max-width: 768px) { .sidebar { width: 100%; height: auto; position: static; } .main-content { margin-left: 0; } }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('financialChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [<?php foreach ($monthly as $m) echo "'{$m['month']}/{$m['year']}',"; ?>],
                    datasets: [
                        {
                            label: 'Income',
                            data: [<?php foreach ($monthly as $m) echo "{$m['income']},"; ?>],
                            backgroundColor: '#4caf50'
                        },
                        {
                            label: 'Expenses',
                            data: [<?php foreach ($monthly as $m) echo "{$m['expenses']},"; ?>],
                            backgroundColor: '#f44336'
                        }
                    ]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
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
        <h2>Financial Reports</h2>
        <h3>Monthly Reports</h3>
        <table>
            <tr>
                <th>Period</th>
                <th>Income</th>
                <th>Expenses</th>
                <th>Balance</th>
            </tr>
            <?php foreach ($monthly as $m): ?>
            <tr>
                <td><?php echo "{$m['month']}/{$m['year']}"; ?></td>
                <td>$<?php echo number_format($m['income'], 2); ?></td>
                <td>$<?php echo number_format($m['expenses'], 2); ?></td>
                <td>$<?php echo number_format($m['income'] - $m['expenses'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <h3>Yearly Reports</h3>
        <table>
            <tr>
                <th>Year</th>
                <th>Income</th>
                <th>Expenses</th>
                <th>Balance</th>
            </tr>
            <?php foreach ($yearly as $y): ?>
            <tr>
                <td><?php echo $y['year']; ?></td>
                <td>$<?php echo number_format($y['income'], 2); ?></td>
                <td>$<?php echo number_format($y['expenses'], 2); ?></td>
                <td>$<?php echo number_format($y['income'] - $y['expenses'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <h3>Income vs Expenses</h3>
        <canvas id="financialChart"></canvas>
    </div>
</body>
</html>
