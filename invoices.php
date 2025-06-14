<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = $_POST['client_name'];
    $client_email = $_POST['client_email'];
    $amount = $_POST['amount'];
    $issue_date = $_POST['issue_date'];
    $due_date = $_POST['due_date'];
    $invoice_number = 'INV-' . time();
    $stmt = $pdo->prepare("INSERT INTO invoices (user_id, invoice_number, client_name, client_email, amount, issue_date, due_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $invoice_number, $client_name, $client_email, $amount, $issue_date, $due_date]);
    echo "<script>alert('Invoice created!'); window.location.href='invoices.php';</script>";
}
$invoices = $pdo->query("SELECT * FROM invoices WHERE user_id = $user_id ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices - QuickBooks Clone</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f4f7fa; }
        .sidebar { background: #1e3c72; color: #fff; width: 250px; height: 100vh; position: fixed; padding: 20px; }
        .sidebar h2 { font-size: 1.5em; margin-bottom: 20px; }
        .sidebar a { color: #fff; display: block; padding: 10px; text-decoration: none; margin: 5px 0; border-radius: 5px; }
        .sidebar a:hover { background: #4caf50; }
        .main-content { margin-left: 270px; padding: 20px; }
        .form-container { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); margin-bottom: 20px; }
        input, button { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #4caf50; color: #fff; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #1e3c72; color: #fff; }
        .action-btn { background: #4caf50; color: #fff; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
        .action-btn:hover { background: #45a049; }
        @media (max-width: 768px) { .sidebar { width: 100%; height: auto; position: static; } .main-content { margin-left: 0; } }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF(invoiceId) {
            const element = document.getElementById('invoice-' + invoiceId);
            html2pdf().from(element).save('invoice-' + invoiceId + '.pdf');
        }
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
        <h2>Create Invoice</h2>
        <div class="form-container">
            <form method="POST">
                <input type="text" name="client_name" placeholder="Client Name" required>
                <input type="email" name="client_email" placeholder="Client Email" required>
                <input type="number" step="0.01" name="amount" placeholder="Amount" required>
                <input type="date" name="issue_date" required>
                <input type="date" name="due_date" required>
                <button type="submit">Create Invoice</button>
            </form>
        </div>
        <h3>Invoices</h3>
        <table>
            <tr>
                <th>Invoice Number</th>
                <th>Client</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Issue Date</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($invoices as $invoice): ?>
            <tr id="invoice-<?php echo $invoice['id']; ?>">
                <td><?php echo $invoice['invoice_number']; ?></td>
                <td><?php echo $invoice['client_name']; ?></td>
                <td>$<?php echo number_format($invoice['amount'], 2); ?></td>
                <td><?php echo ucfirst($invoice['status']); ?></td>
                <td><?php echo $invoice['issue_date']; ?></td>
                <td>
                    <a href="javascript:void(0)" onclick="downloadPDF(<?php echo $invoice['id']; ?>)" class="action-btn">Download PDF</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
