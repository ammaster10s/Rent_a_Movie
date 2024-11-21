<?php
session_start();
include 'database.php';

// Function to check if the user profile is complete
function isProfileComplete($userId, $conn)
{
    $query = "SELECT address FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return false; // Fail gracefully
    }
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $address = null;
    $stmt->bind_result($address);
    $stmt->fetch();
    $stmt->close();

    return !empty($address);
}

// Check user session and profile completion
$showNotification = false;
if (isset($_SESSION['user_id'])) {
    $showNotification = !isProfileComplete($_SESSION['user_id'], $conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Navigation Bar -->
    <?php
    include 'navigate.php';
    ?>
    <div class="profile-form-container">
        <h3>Profile</h3>
        <p>View or update your profile details below.</p>
        <form action="" method="post">
            <!-- Username -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
            </div>

            <!-- Name -->
            <div class="form-group">
                <label for="f_name">Name:</label>
                <input type="text" id="f_name" name="f_name" value="<?php echo htmlspecialchars($user_data['f_name']); ?>" required>
            </div>

            <!-- Surname -->
            <div class="form-group">
                <label for="l_name">Surname:</label>
                <input type="text" id="l_name" name="l_name" value="<?php echo htmlspecialchars($user_data['l_name']); ?>" required>
            </div>

            <!-- ZIP and Country -->
            <div class="form-group-inline">
                <div>
                    <label for="zip">ZIP:</label>
                    <input type="text" id="zip" name="zip" value="<?php echo htmlspecialchars($user_data['zip']); ?>" required>
                </div>
                <div>
                    <label for="country">Country:</label>
                    <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user_data['country']); ?>" required>
                </div>
            </div>

            <!-- Address -->
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user_data['address']); ?>" required>
            </div>

            <!-- Phone Number -->
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user_data['phone']); ?>" required>
            </div>

            <!-- Apply Button -->
            <button type="apply" class="apply-button">APPLY CHANGE</button>
        </form>
    </div>
</body>

</html>