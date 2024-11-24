<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Minimalistic Payment Form</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <?php
  session_start();
  include 'navigate.php';
  include 'database.php';

  // Ensure the user is logged in
  if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
  }

  $user_id = $_SESSION['user_id'];

  // Get the order ID
  if (!isset($_POST['order_id']) || !is_numeric($_POST['order_id'])) {
    die("Order ID is missing or invalid.");
  }

  $order_id = (int) $_POST['order_id'];

  // Fetch existing addresses for the user
  $query = "SELECT Address_ID, Country, House_Address, Zipcode, Phone_number FROM User_Address WHERE User_ID = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $addresses = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  ?>

<form action="process_payment.php" method="post" class="payment-form">
  <!-- Hidden input to pass order_id -->
  <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">

  <fieldset>
    <legend>Select Payment Method</legend>
    <label>
      <input type="radio" name="payment_method" value="visa" required />
      <img src="img/visa.png" alt="Visa" />
    </label>
    <label>
      <input type="radio" name="payment_method" value="mastercard" />
      <img src="img/MasterCard.png" alt="Mastercard" />
    </label>
  </fieldset>
  
    <!-- Address Selection -->
    <fieldset>
      <legend>Address Details</legend>
      <label>
        <input type="checkbox" id="use_existing_address" name="use_existing_address" />
        Use an existing address
      </label>
      <!-- Existing Address Section -->
      <div id="existing-address-section" style="display: none;">
        <label for="existing_address">Select Address:</label>
        <select id="existing_address" name="existing_address">
          <option value="">-- Choose Address --</option>
          <?php foreach ($addresses as $address): ?>
            <option value="<?php echo htmlspecialchars($address['Address_ID']); ?>">
              <?php echo htmlspecialchars($address['House_Address'] . ', ' . $address['Country'] . ', ZIP: ' . $address['Zipcode'] . ', Phone: ' . $address['Phone_number']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <!-- New Address Section -->
      <div id="new-address-section">
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" placeholder="Enter your country" />
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" placeholder="Enter your address" />
        <label for="zip">ZIP Code:</label>
        <input type="text" id="zip" name="zip" placeholder="ZIP Code" />
        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" />
        <label>
          <input type="checkbox" name="save_new_address" />
          Save this address for future use
        </label>
      </div>
    </fieldset>
    <!-- Credit Card Details -->
    <fieldset>
      <legend>Credit Card Details</legend>
      <label for="credit_card_number">Credit Card Number:</label>
      <input type="text" id="credit_card_number" name="credit_card_number" placeholder="Enter your card number" required />
      <small id="card-error" style="color: red; display: none;">Invalid card number. Please enter a valid Visa or MasterCard number.</small>
      <label for="expiry_date">Expiry Date:</label>
      <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" maxlength="5" required />
      <small id="expiry-error" style="color: red; display: none;">Invalid expiry date. Use format MM/YY.</small>
      <label for="cvv">CVV:</label>
      <input type="text" id="cvv" name="cvv" placeholder="CVV" maxlength="3" required />
    </fieldset>

    <!-- Pay Button -->
    <button type="submit" class="pay-button">PAY</button>
  </form>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const creditCardInput = document.getElementById('credit_card_number');
      const cardError = document.getElementById('card-error');
      const expiryInput = document.getElementById('expiry_date');
      const expiryError = document.getElementById('expiry-error');

      // Validate card type based on number
      creditCardInput.addEventListener('input', () => {
        const cardNumber = creditCardInput.value;
        cardError.style.display = 'none';

        if (/^4/.test(cardNumber)) {
          // Visa starts with 4
          document.querySelector('input[value="visa"]').checked = true;
        } else if (/^(5[1-5]|22[2-9]|2[3-6]\d|27[01])/.test(cardNumber)) {
          // MasterCard starts with 51-55 or 2221-2720
          document.querySelector('input[value="mastercard"]').checked = true;
        } else if (cardNumber.length >= 4) {
          cardError.style.display = 'block';
        }
      });

      // Validate expiry date format
      expiryInput.addEventListener('input', () => {
        const expiryDate = expiryInput.value;
        expiryError.style.display = 'none';

        if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiryDate)) {
          expiryError.style.display = 'block';
        }
      });
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const useExistingAddressCheckbox = document.getElementById('use_existing_address');
      const existingAddressSection = document.getElementById('existing-address-section');
      const newAddressSection = document.getElementById('new-address-section');

      // Toggle visibility based on checkbox state
      useExistingAddressCheckbox.addEventListener('change', () => {
        if (useExistingAddressCheckbox.checked) {
          existingAddressSection.style.display = 'block';
          newAddressSection.style.display = 'none';
        } else {
          existingAddressSection.style.display = 'none';
          newAddressSection.style.display = 'block';
        }
      });

      // Initialize state to default
      existingAddressSection.style.display = 'none';
      newAddressSection.style.display = 'block';
    });
  </script>
</body>

</html>