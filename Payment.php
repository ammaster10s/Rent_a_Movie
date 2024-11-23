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
   ?>
  <form action="process_payment.php" method="post" class="payment-form">
    <fieldset>
      <legend>Select Payment Method</legend>
       
          <input type="radio" name="payment_method" value="visa" required />
    
        <img src="img/visa.png" alt="Visa" />

          <input type="radio" name="payment_method" value="mastercard" />

          <img src="img/MasterCard.png" alt="Mastercard" />
 
    </fieldset>

    <!-- Address Selection -->
    <fieldset>
      <legend>Address Details</legend>
      <label>
        <input type="checkbox" id="use_existing_address" name="use_existing_address" />
        Use an existing address
      </label>
      <div id="existing-address-section">
        <label for="existing_address">Select Address:</label>
        <select id="existing_address" name="existing_address">
          <option value="">-- Choose Address --</option>
          <option value="1">123 Main St, City, ZIP 12345</option>
          <option value="2">456 Elm St, Town, ZIP 67890</option>
        </select>
      </div>
      <div id="new-address-section">
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" placeholder="Enter your country" />
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" placeholder="Enter your address" />
        <label for="zip">ZIP Code:</label>
        <input type="text" id="zip" name="zip" placeholder="ZIP Code" />
        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" />
      </div>
    </fieldset>

    <!-- Credit Card Details -->
    <fieldset>
      <legend>Credit Card Details</legend>
      <label for="credit_card_number">Credit Card Number:</label>
      <input type="text" id="credit_card_number" name="credit_card_number" placeholder="Enter your card number" required />
      <label for="expiry_date">Expiry Date:</label>
      <input type="month" id="expiry_date" name="expiry_date"  placeholder="mm/yy"  required />
      <label for="cvv">CVV:</label>
      <input type="text" id="cvv" name="cvv" placeholder="CVV" required />
    </fieldset>

    <!-- Pay Button -->
    <button type="submit" class="pay-button">PAY</button>
  </form>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const useExistingAddressCheckbox = document.getElementById('use_existing_address');
      const existingAddressSection = document.getElementById('existing-address-section');
      const newAddressSection = document.getElementById('new-address-section');

      useExistingAddressCheckbox.addEventListener('change', () => {
        if (useExistingAddressCheckbox.checked) {
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
