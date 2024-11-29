<?php
session_start();
include 'database.php';

// Ensure only admins can access this page
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch user details
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $query = "SELECT Username, F_Name, L_Name, Email_Address, Role FROM Users WHERE User_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($username, $fName, $lName, $email, $role);
    $stmt->fetch();
    $stmt->close();
}

// Fetch orders specific to this user
$orderQuery = "SELECT Order_ID, Payment_ID, Status FROM Orders WHERE User_ID = ?";
$orderStmt = $conn->prepare($orderQuery);
$orderStmt->bind_param("i", $userId);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user details
    $newEmail = $_POST['email'];
    $newRole = $_POST['role'];
    $newFName = $_POST['f_name'];
    $newLName = $_POST['l_name'];

    $updateQuery = "UPDATE Users SET Email_Address = ?, Role = ?, F_Name = ?, L_Name = ? WHERE User_ID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssi", $newEmail, $newRole, $newFName, $newLName, $userId);
    $stmt->execute();
    $stmt->close();
    header('Location: admin_dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="edit-user-container">
        <h1>Edit User</h1>
        <form method="POST">
            <label>Username:</label>
            <input type="text" value="<?php echo htmlspecialchars($username); ?>" disabled>

            <label>First Name:</label>
            <input type="text" name="f_name" value="<?php echo htmlspecialchars($fName); ?>" required>

            <label>Last Name:</label>
            <input type="text" name="l_name" value="<?php echo htmlspecialchars($lName); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label>Role:</label>
            <select name="role">
                <option value="user" <?php echo $role === 'user' ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>

            <button type="submit">Save Changes</button>
        </form>

        <!-- Display Orders for the Specific User -->
        <h2>User's Orders</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Payment ID</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $orderResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Order_ID']); ?></td>
                    <td><?php echo htmlspecialchars($row['Payment_ID'] ?? 'Not Paid'); ?></td>
                    <td><?php echo htmlspecialchars($row['Status']); ?></td>
                    <td>
                        <a href="edit_order.php?order_id=<?php echo $row['Order_ID']; ?>">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
