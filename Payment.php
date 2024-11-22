<?php
include 'auth_check.php';
include 'database.php';

// Validate the user's session
if (!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    die("Invalid session. Please log in again.");
}
$user_id = (int) $_SESSION['user_id'];

// Fetch the user's pending order
$stmt = $conn->prepare("
    SELECT o.Order_ID 
    FROM Orders o
    WHERE o.User_ID = ? AND o.Status = 'Pending'
    LIMIT 1
");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($order_id);

if (!$stmt->fetch()) {
    $order_id = null; // No pending order found
}
$stmt->close();

// Initialize cart items and total price
$cart_items = [];
$total_price = 0;

if ($order_id) {
    // Fetch movies in the cart for the pending order
    $stmt = $conn->prepare("
        SELECT m.Movie_ID, m.Movie_Name, m.Price 
        FROM Order_Contain oc
        INNER JOIN Movie m ON oc.Movie_ID = m.Movie_ID
        WHERE oc.Order_ID = ?
    ");
    if ($stmt) {
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart_items = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Calculate total price
        $total_price = array_reduce($cart_items, function ($sum, $item) {
            return $sum + $item['Price'];
        }, 0);
    } else {
        die("Error fetching cart items: " . $conn->error);
    }
}

// Fetch the user's existing addresses
$stmt = $conn->prepare("
    SELECT Address_ID, Country, House_Address, Zipcode, Phone_Number 
    FROM User_Address 
    WHERE User_ID = ?
");
if ($stmt) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $addresses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    die("Error fetching addresses: " . $conn->error);
}

if (empty($addresses)) {
    $addresses = []; // Ensure $addresses is always an array
}
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
  <?php include 'navigate.php'; ?>

  <!-- Payment Section -->
  <div class="payment-page">
    <h1 class="payment-title">Payment</h1>

    <!-- Total Price -->
    <div class="total-price">
      <span>TOTAL PRICE:</span>
      <span class="price"><?php echo number_format($total_price, 2) . "$"; ?></span>
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
      <!-- Cart Summary -->
      <?php if (empty($cart_items)): ?>
        <div class="empty-cart">
          <p>Your cart is empty. <a href="browse.php">Browse movies</a> to add items.</p>
        </div>
      <?php else: ?>
        <div class="total-price">
          <span>TOTAL PRICE:</span>
          <span class="price"><?php echo number_format($total_price, 2) . "$"; ?></span>
        </div>
      <?php endif; ?>

      <!-- Address Validation -->
      <fieldset>
        <legend>Address Details</legend>
        <?php if (!empty($addresses)): ?>
          <label for="existing_address">Select an Existing Address:</label>
          <select id="existing_address" name="existing_address">
            <option value="">-- Choose Address --</option>
            <?php foreach ($addresses as $address): ?>
              <option value="<?php echo $address['Address_ID']; ?>">
                <?php echo "{$address['Country']}, {$address['House_Address']}, ZIP: {$address['Zipcode']}, Phone: {$address['Phone_Number']}"; ?>
              </option>
            <?php endforeach; ?>
          </select>
          <p>Or enter a new address below:</p>
        <?php endif; ?>
        <div class="form-group">
          <label for="country">Country:</label>
          <input type="text" id="country" name="country" placeholder="Enter your country" />
        </div>
        <div class="form-group">
          <label for="address">Address:</label>
          <input type="text" id="address" name="address" placeholder="Enter your address" />
        </div>
        <div class="form-group">
          <label for="zip">ZIP Code:</label>
          <input type="text" id="zip" name="zip" placeholder="ZIP Code" />
        </div>
        <div class="form-group">
          <label for="phone">Phone Number:</label>
          <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" />
        </div>
      </fieldset>


      <!-- Pay Button -->
      <button type="submit" class="pay-button">PAY</button>
    </form>
  </div>
</body>

</html>