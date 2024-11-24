<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Order Summary</title>
  <link rel="stylesheet" href="globals.css" />
  <link rel="stylesheet" href="style.css" />


  <style>
    .payment-form fieldset {
      border: 1px solid #ccc;
      padding: 20px;
      border-radius: 10px;
      background-color: #303148;
      color: white;
      margin-bottom: 20px;
      /* Add gap between sections */
    }

    .payment-form legend {
      font-weight: bold;
      padding: 0 10px;
    }

    .payment-form .payment-options {
      display: flex;
      justify-content: space-around;
      align-items: center;
      margin-top: 10px;
    }

    .payment-form .payment-options label {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .payment-form .payment-options img {
      width: 50px;
      height: auto;
      margin: 5px;
      cursor: pointer;
    }

    .save-address-btn {
      display: block;
      margin: 10px auto;
      padding: 10px 20px;
      font-size: 16px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-align: center;
    }

    .save-address-btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>

<body>
  <?php
  session_start();
  include 'navigate.php';
  include 'database.php';

  if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
  }

  $user_id = $_SESSION['user_id'];
  $total_price = $_POST['total_price'];

  if (!isset($_POST['order_id']) || !is_numeric($_POST['order_id'])) {
    die("Order ID is missing or invalid.");
  }

  $order_id = (int) $_POST['order_id'];

  $query = "SELECT Address_ID, Country, House_Address, Zipcode, Phone_number FROM User_Address WHERE User_ID = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $addresses = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  ?>
  <form action="process_payment.php" method="post" class="payment-form">
    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">

    <!-- Payment Method -->
    <fieldset>
      <legend>Select Payment Method</legend>
      <div class="payment-options">
        <label>
          <img src="/img/paypal.png" alt="PayPal" />
          <input type="radio" name="payment_method" value="paypal" required />
        </label>
        <label>
          <img src="/img/MasterCard.png" alt="MasterCard" />
          <input type="radio" name="payment_method" value="mastercard" />
        </label>
        <label>
          <img src="/img/visa.png" alt="Visa" />
          <input type="radio" name="payment_method" value="visa" />
        </label>
      </div>
    </fieldset>

    <!-- Address Details -->
    <fieldset>
      <legend>Address Details</legend>
      <label>
        <input type="checkbox" id="use_existing_address" name="use_existing_address" />
        Use an existing address
      </label>
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
      <div id="new-address-section">
        <label for="city">City:</label>
        <input type="text" id="city" name="city" placeholder="City" />
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" placeholder="Country" />
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" placeholder="Address" />
        <label for="zip">ZIP:</label>
        <input type="text" id="zip" name="zip" placeholder=" ZIP" />
        <label for="phone-number">Phone Number:</label>
        <input type="text" id="phone-number" name="phone-number" , placeholder="Phone number" />
        <label>
        <input type="checkbox" name="save_new_address" id="save_new_address" />

          Save this address for later use
        </label>

      </div>
    </fieldset>

    <!-- Credit Card Details -->
    <fieldset>
      <legend>Credit Card Details</legend>
      <label for="credit_card_number">Card Number:</label>
      <input type="text" id="credit_card_number" name="credit_card_number" placeholder="Enter your card number" value="5105 1051 0510 5100" required />
      
      <label for="expiry_date">Expiry Date:</label>
      <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" maxlength="5" required />
      
      <label for="cvv">CVV:</label>
      <input type="text" id="cvv" name="cvv" maxlength="3", placeholder="CVV" required />
      
    </fieldset>

    <div class="total-price">Total Price: <?php echo htmlspecialchars($total_price, ENT_QUOTES, 'UTF-8'); ?></div>
    <button type="submit" class="pay-button">PAY</button>
  </form>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const useExistingAddress = document.getElementById('use_existing_address');
      const existingAddressSection = document.getElementById('existing-address-section');
      const newAddressSection = document.getElementById('new-address-section');

      useExistingAddress.addEventListener('change', () => {
        if (useExistingAddress.checked) {
          existingAddressSection.style.display = 'block';
          newAddressSection.style.display = 'none';
        } else {
          existingAddressSection.style.display = 'none';
          newAddressSection.style.display = 'block';
        }
      });
    });
  </script>
</body>

</html>