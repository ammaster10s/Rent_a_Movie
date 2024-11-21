<?php
session_start();
include 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

// Fetch user data
$query = "SELECT * FROM Users WHERE Username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch user's addresses
$query = "SELECT * FROM User_Address WHERE User_ID = (SELECT User_ID FROM Users WHERE Username = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$addresses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
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
    <?php include 'navigate.php'; ?>

    <div class="profile-form-container">
        <h3>Profile</h3>
        <p>View or update your profile details below.</p>

        <form action="handle_addresses.php" method="post">
            <!-- Username -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['Username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required readonly>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['Email_Address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <!-- Addresses Section -->
            <div id="addresses-container">
                <?php if (!empty($addresses)): ?>
                    <?php foreach ($addresses as $index => $address): ?>
                        <div class="address-block">
                            <input type="hidden" name="address_id[]" value="<?php echo htmlspecialchars($address['ADDRESS_ID'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            <h4>Address <?php echo $index + 1; ?></h4>
                            <label>City:</label>
                            <input type="text" name="city[]" value="<?php echo htmlspecialchars($address['City'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

                            <label>House Address:</label>
                            <input type="text" name="house_address[]" value="<?php echo htmlspecialchars($address['House_Address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

                            <label>ZIP:</label>
                            <input type="text" name="zipcode[]" value="<?php echo htmlspecialchars($address['Zipcode'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

                            <label>Country:</label>
                            <input type="text" name="country[]" value="<?php echo htmlspecialchars($address['Country'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>

                            <label>Phone:</label>
                            <input type="text" name="phone_number[]" value="<?php echo htmlspecialchars($address['Phone_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                            <button type="button" class="remove-address-btn" onclick="removeAddress(this)">Remove Address</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No addresses added yet.</p>
                <?php endif; ?>
            </div>

            <button type="button" id="add-address-btn">Add Address</button>
            <button type="submit" class="apply-button">Save Changes</button>
        </form>
    </div>

    <script>
        document.getElementById('add-address-btn').addEventListener('click', function() {
            const container = document.getElementById('addresses-container');
            const index = container.children.length;
            const addressBlock = document.createElement('div');
            addressBlock.className = 'address-block';

            addressBlock.innerHTML = `
                <h4>Address ${index + 1}</h4>
                <input type="hidden" name="address_id[]" value="">
                <label>City:</label>
                <input type="text" name="city[]" required>

                <label>House Address:</label>
                <input type="text" name="house_address[]" required>

                <label>ZIP:</label>
                <input type="text" name="zipcode[]" required>

                <label>Country:</label>
                <input type="text" name="country[]" required>

                <label>Phone:</label>
                <input type="text" name="phone_number[]">

                <button type="button" class="remove-address-btn" onclick="removeAddress(this)">Remove Address</button>
            `;
            container.appendChild(addressBlock);
        });

        function removeAddress(button) {
            button.closest('.address-block').remove();
        }
    </script>
</body>

</html>