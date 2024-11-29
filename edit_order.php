<?php
session_start();
include 'database.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php");
    exit();
}

if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];
    $query = "SELECT Order_ID, Payment_ID, User_ID, Status FROM Orders WHERE Order_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $stmt->bind_result($orderId, $paymentId, $userId, $status);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = $_POST['status']; // Get the selected status from the form

    // Update the order's status
    $query = "UPDATE Orders SET Status = ? WHERE Order_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $newStatus, $orderId);

    if ($stmt->execute()) {
        header('Location: admin_dashboard.php'); // Redirect to dashboard
        exit();
    } else {
        echo "Error updating order: " . $stmt->error;
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Order</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="edit-user-container">
        <h1>Edit Order</h1>
        <form method="POST">
            <label>Order ID:</label>
            <input type="text" value="<?php echo htmlspecialchars($orderId); ?>" disabled>

            <label>Payment ID:</label>
            <input type="text" value="<?php echo htmlspecialchars($paymentId ?? 'Not Paid'); ?>" disabled>

            <label>User ID:</label>
            <input type="text" value="<?php echo htmlspecialchars($userId); ?>" disabled>

            <label>Status:</label>
        
<select name="status">
    <option value="Pending" <?php echo $status === 'Pending' ? 'selected' : ''; ?>>Pending</option>
    <option value="Completed" <?php echo $status === 'Completed' ? 'selected' : ''; ?>>Completed</option>
    <option value="Cancelled" <?php echo $status === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
</select>

            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
