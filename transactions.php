<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $transaction_date = $_POST['transaction_date'];
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, category, description, transaction_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $type, $amount, $category, $description, $transaction_date]);
    echo "<script>alert('Transaction added!'); window.location.href='transactions.php';</script>";
}
$transactions = $pdo->query("SELECT * FROM transactions WHERE user_id = $user_id ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - QuickBooks Clone</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f4f7fa; }
        .sidebar { background: #1e3c72; color: #fff; width: 250px; height: 100vh; position: fixed; padding: 20px; }
        .sidebar h2 { font-size: 1.5em; margin-bottom: 20px; }
        .sidebar a { color: #fff; display: block; padding: 10px; text-decoration: none; margin: 5px 0; border-radius: 5px; }
        .sidebar a:hover { background: #4caf50; }
        .main-content { margin-left: 270px; padding: 20px; }
        .form-container { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); margin-bottom: 20px; }
        input, select, button { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #4caf50; color: #fff; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #1e3c72; color: #fff; }
        @media (max-width: 768px) { .sidebar { width: 100%; height: auto; position: static; } .main-content { margin-left: 0; } }
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
        <h2>Add Transaction</h2>
        <div class="form-container">
            <form method="POST">
                <select name="type" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
                <input type="number" step="0.01" name="amount" placeholder="Amount" required>
                <input type="text" name="category" placeholder="Category" required>
                <input type="text" name="description" placeholder="Description">
                <input type="date" name="transaction_date" required>
                <button type="submit">Add Transaction</button>
            </form>
        </div>
        <h3>Transaction History</h3>
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
