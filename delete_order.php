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
    $query = "DELETE FROM Orders WHERE Order_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $orderId);
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
    } else {
        echo "Error deleting order.";
    }
    $stmt->close();
}
?>
