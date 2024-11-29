<?php
session_start();
include 'database.php';

// Ensure admin access
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Fetch users
$userQuery = "SELECT User_ID, Username, F_Name, L_Name, Email_Address, Role FROM Users";
$userResult = $conn->query($userQuery);

// Fetch orders
$orderQuery = "SELECT Order_ID, Payment_ID, User_ID, Status FROM Orders";
$orderResult = $conn->query($orderQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-dashboard-container">
        <h1>Admin Dashboard</h1>

        <!-- Manage Users Section -->
        <h2>Manage Users</h2>
        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $userResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['User_ID']); ?></td>
                    <td><?php echo htmlspecialchars($row['Username']); ?></td>
                    <td><?php echo htmlspecialchars($row['F_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['L_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['Email_Address']); ?></td>
                    <td><?php echo htmlspecialchars($row['Role']); ?></td>
                    <td>
                        <!-- Link to Edit User -->
                        <a href="edit_user.php?user_id=<?php echo $row['User_ID']; ?>">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <!-- Manage Orders Section -->
        <h2>Manage Orders</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Payment ID</th>
                <th>User ID</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $orderResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Order_ID']); ?></td>
                    <td><?php echo htmlspecialchars($row['Payment_ID'] ?? 'Not Paid'); ?></td>
                    <td><?php echo htmlspecialchars($row['User_ID']); ?></td>
                    <td><?php echo htmlspecialchars($row['Status']); ?></td>
                    <td>
                        <!-- Link to Edit Order -->
                        <a href="edit_order.php?order_id=<?php echo $row['Order_ID']; ?>">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
