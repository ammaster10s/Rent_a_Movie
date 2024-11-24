<body?php include 'auth_check.php' ; include 'database.php' ; $user_id=$_SESSION['user_id'];
  $order_id=$_POST['order_id']; // Fetch existing addresses for the user
  $query="SELECT Address_ID, Country, House_Address, Zipcode, Phone_number FROM User_Address WHERE User_ID = ?" ;
  $stmt=$conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $addresses = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  ?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Summary</title>
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="style.css" />
  </head>

  <body>
    <?php include 'navigate.php'; ?>

    <div class="payment-form">
      <label for="payment-method">Select payment method:</label>
      
      <select id="payment-method" name="payment-method">
        <option value="paypal">PayPal</option>
        <option value="mastercard">MasterCard</option>
        <option value="visa">Visa</option>

      </select>
      <div class="payment-methods">
      <img src="/img/paypal.png" alt="PayPal" />
      <img src="/img/MasterCard.png" alt="MasterCard" />
      <img src="/img/visa.png" alt="Visa" />

    </div>
      <label for="holder-name">Holder's name:</label>
      <input type="text" id="holder-name" name="holder-name" />

      <label for="holder-surname">Holder's surname:</label>
      <input type="text" id="holder-surname" name="holder-surname" />

      <label for="cvc">CVC:</label>
      <input type="text" id="cvc" name="cvc" />
      <label for="expiration-date">Expiration date:</label>
      <input type="text" id="expiration-date" name="expiration-date" />

      <div class="checkbox-container">
      <label for="same-address">Use profile address</label>  
      <input type="checkbox" id="same-address" name="same-address" />
        
      </div>

      <label for="zip">ZIP:</label>
      <input type="text" id="zip" name="zip" />

      <label for="country">Country:</label>
      <input type="text" id="country" name="country" />

      <label for="address">Address:</label>
      <input type="text" id="address" name="address" />

      <label for="phone-number">Phone Number:</label>
      <input type="text" id="phone-number" name="phone-number" />

      <div class="total-price">Total Price: $29.98</div>
      <button type="submit">PAY</button>
    </div>


  </body>