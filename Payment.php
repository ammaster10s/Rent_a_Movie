<?php 
include 'auth_check.php'; 
include 'database.php';


$user_id = $_SESSION['user_id'];
$order_id = $_POST['order_id'];

// Fetch existing addresses for the user
$query = "SELECT Address_ID, Country, House_Address, Zipcode, Phone_number FROM User_Address WHERE User_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$addresses = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Minimalistic Payment Form</title>
  <style>
    /* Wrapper for Centering */
    .payment-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: linear-gradient(to bottom, #1c1c2d, #2c2c44);
    }

    /* Payment Container */
    .payment-container {
      max-width: 600px;
      width: 100%;
      background: #2c2c44;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
      text-align: center;
    }

    /* Notification Text */
    h3 {
      color: #ffffff;
      background-color: #444;
      padding: 10px;
      border-radius: 5px;
      font-size: 14px;
      margin-bottom: 20px;
    }

    /* Payment Methods Section */
    .payment-options {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-bottom: 20px;
    }

    .payment-option {
      text-align: center;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 15px;
      width: 40%;
      cursor: pointer;
      transition: transform 0.2s ease-in-out;
    }

    .payment-option img {
      max-height: 40px;
      margin-bottom: 10px;
    }

    .payment-option.selected {
      border: 2px solid #4caf50;
      background-color: #f9f9f9;
    }

   /* Input Fields */
fieldset label {
  display: block;
  font-size: 14px;
  font-weight: bold;
  margin-bottom: 5px; /* Adjusted spacing */
  color: #ffffff; /* White text for labels */
}

fieldset input,
fieldset select {
  width: 100%;
  padding: 10px;
  font-size: 14px;
  border: 1px solid #ccc; /* Subtle border */
  border-radius: 5px;
  background: #ffffff; /* White background */
  color: #333; /* Dark text */
  margin-bottom: 15px; /* Adjusted spacing */
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1); /* Slight inner shadow */
}

fieldset input::placeholder {
  color: #aaa; /* Subtle placeholder text */
  font-size: 14px;
}

/* On focus */
fieldset input:focus {
  border-color: #4caf50; /* Green border on focus */
  outline: none; /* Remove default focus outline */
  box-shadow: 0 0 5px rgba(76, 175, 80, 0.5); /* Green glow effect */
}

/* Fieldset Styling */
fieldset {
  border: none; /* Remove the default border */
  padding: 10px 0; /* Adjust padding for a compact look */
}

    /* Pay Button */
    .pay-button {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      font-weight: bold;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .pay-button:hover {
      background-color: #0056b3;
    }
  </style>
</head>

<?php include 'navigate.php'; ?>

<body>
  <div class="payment-wrapper">
    <form action="process_payment.php" method="POST" class="payment-container">
    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
      

      <!-- Payment Options -->
      <fieldset>
        <legend>Select Payment Method</legend>
        <div class="payment-options">
          <div class="payment-option" data-method="visa">
            <img src="img/visa.png" alt="Visa">
          </div>
          <div class="payment-option" data-method="mastercard">
            <img src="img/MasterCard.png" alt="MasterCard">
          </div>
        </div>
      </fieldset>

      <!-- Address Details -->
      <fieldset>
        <legend>Address Details</legend>
        <div>
          <input type="checkbox" id="use_existing_address" name="use_existing_address">
          <label for="use_existing_address">Use an existing address</label>
        </div>

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
          <label for="country">Country:</label>
          <input type="text" id="country" name="country" placeholder="Enter your country">
          <label for="address">Address:</label>
          <input type="text" id="address" name="address" placeholder="Enter your address">
          <label for="zip">ZIP Code:</label>
          <input type="text" id="zip" name="zip" placeholder="ZIP Code">
          <label for="phone">Phone Number:</label>
          <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
        </div>
      </fieldset>

      <!-- Credit Card Details -->
      <fieldset>
        <legend>Credit Card Details</legend>
        <label for="credit_card_number">Card Number:</label>
        <input type="text" id="credit_card_number" name="credit_card_number" placeholder="0000 0000 0000 0000" maxlength="19" value="5555 5555 5555 4444" required>
        <label for="expiry_date">Card Expiry Date:</label>
        <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" maxlength="5" required>
        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" name="cvv" placeholder="CVV" maxlength="3" required>
        <label for="cname">Card Holder Name:</label>
        <input type="text" id="cname" name="cname" placeholder="Card Holder name"  required>

      </fieldset>

      <!-- Submit Button -->
      <button type="submit" class="pay-button">PAY</button>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const existingAddressCheckbox = document.getElementById('use_existing_address');
      const existingAddressSection = document.getElementById('existing-address-section');
      const newAddressSection = document.getElementById('new-address-section');
      const paymentOptions = document.querySelectorAll('.payment-option');

      // Toggle existing address section
      existingAddressCheckbox.addEventListener('change', () => {
        if (existingAddressCheckbox.checked) {
          existingAddressSection.style.display = 'block';
          newAddressSection.style.display = 'none';
        } else {
          existingAddressSection.style.display = 'none';
          newAddressSection.style.display = 'block';
        }
      });

      // Select payment method
      paymentOptions.forEach(option => {
        option.addEventListener('click', () => {
          paymentOptions.forEach(opt => opt.classList.remove('selected'));
          option.classList.add('selected');
        });
      });
    });
  </script>
</body>

</html>
