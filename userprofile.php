<?php
session_start();
include 'database.php';

// Ensure the user is logged in
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
  <?php include 'navigate.php'; ?>

  <!-- Profile Container -->
  <div class="profile-container">
    <h3>Manage Addresses</h3>
    <form action="handle_addresses.php" method="post">
      <div id="addresses-container">
        <?php if (!empty($addresses)): ?>
          <?php foreach ($addresses as $index => $address): ?>
            <div class="address-block">
              <input type="hidden" name="address_id[]" value="<?php echo htmlspecialchars($address['Address_ID'] ?? ''); ?>">
              <h4>Address <?php echo $index + 1; ?></h4>
              <label>City:</label>
              <input type="text" name="city[]" value="<?php echo htmlspecialchars($address['City'] ?? ''); ?>" placeholder="Enter city" required>
              <label>House Address:</label>
              <input type="text" name="house_address[]" value="<?php echo htmlspecialchars($address['House_Address'] ?? ''); ?>" placeholder="Enter house address" required>
              <label>ZIP:</label>
              <input type="text" name="zipcode[]" value="<?php echo htmlspecialchars($address['Zipcode'] ?? ''); ?>" placeholder="Enter ZIP code" required>
              <label>Country:</label>
              <input type="text" name="country[]" value="<?php echo htmlspecialchars($address['Country'] ?? ''); ?>" placeholder="Enter country" required>
              <label>Phone:</label>
              <input type="text" name="phone_number[]" value="<?php echo htmlspecialchars($address['Phone_number'] ?? ''); ?>" placeholder="Enter phone number">
              <button type="button" onclick="markAddressForDeletion(this, '<?php echo $address['Address_ID']; ?>')">Remove Address</button>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No addresses added yet. Add one now.</p>
        <?php endif; ?>
      </div>
      <input type="hidden" name="deleted_addresses" id="deleted-addresses" value="">
      <div class="button-group">
  <button type="button" onclick="addAddress()">Add Address</button>
  <button type="submit">Save Change</button>
</div>

    </form>
  </div>

  <script>
    function addAddress() {
    const container = document.getElementById('addresses-container');
    const addressBlock = document.createElement('div');
    addressBlock.className = 'address-block';
    addressBlock.innerHTML = `
        <h4>Address</h4>
        <input type="hidden" name="address_id[]" value="">
        <label>City:</label>
        <input type="text" name="city[]" placeholder="Enter city" required>
        <label>House Address:</label>
        <input type="text" name="house_address[]" placeholder="Enter house address" required>
        <label>ZIP:</label>
        <input type="text" name="zipcode[]" placeholder="Enter ZIP code" required>
        <label>Country:</label>
        <input type="text" name="country[]" placeholder="Enter country" required>
        <label>Phone:</label>
        <input type="text" name="phone_number[]" placeholder="Enter phone number">
        <button type="button" onclick="removeAddress(this)">Remove Address</button>
    `;
    container.appendChild(addressBlock);
    renumberAddresses(); // Ensure numbering is updated after addition
}

function removeAddress(button) {
    const addressBlock = button.parentElement;
    if (addressBlock) {
        addressBlock.remove();
        renumberAddresses(); // Ensure numbering is updated after removal
    }
}

function renumberAddresses() {
    const addressBlocks = document.querySelectorAll('.address-block');
    addressBlocks.forEach((block, index) => {
        const heading = block.querySelector('h4');
        if (heading) {
            heading.textContent = `Address ${index + 1}`; // Always start from 1
        }
    });
}

  </script>
</body>

</html>
