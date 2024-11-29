<?php
session_start();
include 'database.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: Login.php");
    exit();
}

// Fetch all users dynamically from the database
$query = "SELECT User_ID, Username, F_Name, L_Name, Email_Address, Role FROM Users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-dashboard-container">
        <h1>Welcome, Admin</h1>
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
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['User_ID']); ?></td>
                    <td><?php echo htmlspecialchars($row['Username']); ?></td>
                    <td><?php echo htmlspecialchars($row['F_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['L_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['Email_Address']); ?></td>
                    <td><?php echo htmlspecialchars($row['Role']); ?></td>
                    <td>
                        <a href="edit_user.php?user_id=<?php echo $row['User_ID']; ?>">Edit</a> |
                        <a href="delete_user.php?user_id=<?php echo $row['User_ID']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
