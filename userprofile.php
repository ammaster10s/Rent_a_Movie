<?php
// session_start();
include 'database.php';
include 'auth_check.php';

// Fetch user data
$user_id = $_SESSION['username'];
$query = "SELECT * FROM Users WHERE Username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $Username);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch user's addresses
$query = "SELECT ADDRESS_ID FROM User_Address WHERE (SELECT User_ID From Users where Username=?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $Username);
$stmt->execute();
$result = $stmt->get_result();
$addresses = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?><!DOCTYPE html>
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
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email_address']); ?>" required>
            </div>

            <!-- Addresses Section -->
            <div id="addresses-container">
                <?php if (!empty($addresses)): ?>
                    <?php foreach ($addresses as $index => $address): ?>
                        <div class="address-block">
                            <h4>Address <?php echo $index + 1; ?></h4>
                            <label for="address_label_<?php echo $index; ?>">Label:</label>
                            <input type="text" name="address_label[]" value="<?php echo htmlspecialchars($address['address_label']); ?>" required>

                            <label for="address_<?php echo $index; ?>">Address:</label>
                            <input type="text" name="address[]" value="<?php echo htmlspecialchars($address['address']); ?>" required>

                            <label for="zip_<?php echo $index; ?>">ZIP:</label>
                            <input type="text" name="zip[]" value="<?php echo htmlspecialchars($address['zip']); ?>" required>

                            <label for="country_<?php echo $index; ?>">Country:</label>
                            <input type="text" name="country[]" value="<?php echo htmlspecialchars($address['country']); ?>" required>

                            <label for="phone_<?php echo $index; ?>">Phone:</label>
                            <input type="text" name="phone[]" value="<?php echo htmlspecialchars($address['phone']); ?>">

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
        document.getElementById('add-address-btn').addEventListener('click', function () {
            const container = document.getElementById('addresses-container');
            const index = container.children.length;
            const addressBlock = document.createElement('div');
            addressBlock.className = 'address-block';

            addressBlock.innerHTML = `
                <h4>Address ${index + 1}</h4>
                <label for="address_label_${index}">Label:</label>
                <input type="text" name="address_label[]" required>

                <label for="address_${index}">Address:</label>
                <input type="text" name="address[]" required>

                <label for="zip_${index}">ZIP:</label>
                <input type="text" name="zip[]" required>

                <label for="country_${index}">Country:</label>
                <input type="text" name="country[]" required>

                <label for="phone_${index}">Phone:</label>
                <input type="text" name="phone[]">

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
