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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .admin-dashboard-container {
            width: 90%;
            margin: 30px auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display : flex ;
            flex-direction: row;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="admin-dashboard-container">
        <h1>Admin Dashboard</h1>

        <!-- Manage Users Section -->
        <h2>Manage Users</h2>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($userResult && $userResult->num_rows > 0): ?>
                    <?php while ($row = $userResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['User_ID']); ?></td>
                            <td><?php echo htmlspecialchars($row['Username']); ?></td>
                            <td><?php echo htmlspecialchars($row['F_Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['L_Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Email_Address']); ?></td>
                            <td><?php echo htmlspecialchars($row['Role']); ?></td>
                            <td>
                                <a href="edit_user.php?user_id=<?php echo $row['User_ID']; ?>">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Manage Orders Section -->
        <h2>Manage Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Payment ID</th>
                    <th>User ID</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orderResult && $orderResult->num_rows > 0): ?>
                    <?php while ($row = $orderResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Order_ID']); ?></td>
                            <td><?php echo htmlspecialchars($row['Payment_ID'] ?? 'Not Paid'); ?></td>
                            <td><?php echo htmlspecialchars($row['User_ID']); ?></td>
                            <td><?php echo htmlspecialchars($row['Status']); ?></td>
                            <td>
                                <a href="edit_order.php?order_id=<?php echo $row['Order_ID']; ?>">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
