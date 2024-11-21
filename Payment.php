<?php
include 'auth_check.php';
/*session_start();*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Payment</title>
  <link rel="stylesheet" href="globals.css" />
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <!-- Navigation Bar -->
  <?php
  include 'navigate.php';
  ?>


  <!-- Payment Section -->
  <div class="payment-page">
    <h1 class="payment-title">Payment</h1>

    <!-- Total Price -->
    <div class="total-price">
      <span>TOTAL PRICE:</span>
      <span class="price">29.98$</span>
    </div>

    <!-- Payment Form -->
    <form action="process_payment.php" method="post" class="payment-form">
      <!-- Payment Method -->
      <fieldset>
        <legend>Select Payment Method</legend>
        <div class="payment-methods">
          <label>
            <input type="radio" name="payment_method" value="visa" required />
            <img src="img/visa.png" alt="Visa" class="payment-icon" />
          </label>
          <label>
            <input type="radio" name="payment_method" value="mastercard" />
            <img src="img/MasterCard.png" alt="Mastercard" class="payment-icon" />
          </label>
        </div>


      </fieldset>

      <!-- Card Details -->
      <div class="form-group">
        <label for="card_name">Holder's Name:</label>
        <input type="text" id="card_name" name="card_name" placeholder="Enter card holder's name" required />
      </div>
      <div class="form-group">
        <label for="card_surname">Holder's Surname:</label>
        <input type="text" id="card_surname" name="card_surname" placeholder="Enter card holder's surname" required />
      </div>
      <div class="form-group">
        <label for="expiration_date">Expiration Date:</label>
        <input type="month" id="expiration_date" name="expiration_date" placeholder="mm/yy" required />
      </div>
      <div class="form-group">
        <label for="cvc">CVC:</label>
        <input type="text" id="cvc" name="cvc" placeholder="CVC Code" required />
      </div>

      <!-- Address Details -->
      <div class="form-group">
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" placeholder="Enter your country" required />
      </div>
      <div class="form-group">
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" placeholder="Enter your address" required />
      </div>
      <div class="form-group">
        <label for="zip">ZIP Code:</label>
        <input type="text" id="zip" name="zip" placeholder="ZIP Code" required />
      </div>
      <div class="form-group">
        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required />
      </div>
      <div class="form-group">
        <input type="checkbox" id="same_address" name="same_address" />
        <label for="same_address">Use the same address as profile</label>
      </div>

      <!-- Pay Button -->
      <button type="submit" class="pay-button">PAY</button>
    </form>
  </div>
</body>

</html>