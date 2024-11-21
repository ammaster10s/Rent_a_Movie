<?php
include 'auth_check.php';
include 'database.php';

// session_start();

// Initialize cart session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add movie to cart
if (isset($_GET['action']) && $_GET['action'] === 'add' && isset($_GET['movie_id'])) {
    $movie_id = (int)$_GET['movie_id'];
    if (!in_array($movie_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $movie_id;
        echo json_encode(["success" => true, "message" => "Movie added to cart."]);
    } else {
        echo json_encode(["success" => false, "message" => "Movie already in cart."]);
    }
    exit();
}

// Remove movie from cart
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['movie_id'])) {
    $movie_id = (int)$_GET['movie_id'];
    $_SESSION['cart'] = array_filter($_SESSION['cart'], fn($id) => $id !== $movie_id);
    echo json_encode(["success" => true, "message" => "Movie removed from cart."]);
    exit();
}

// Purchase movies
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['purchase'])) {
    if (empty($_SESSION['cart'])) {
        echo "<script>alert('Your cart is empty!');</script>";
    } else {
        $user_id = $_SESSION['user_id'];

        // Insert a new order
        $conn->begin_transaction();
        try {
            $insert_order = "INSERT INTO Orders (User_ID, Status) VALUES (?, 'Paid')";
            // Status ENUM('Pending', 'Paid', 'Cancelled') DEFAULT 'Pending' 
            $stmt = $conn->prepare($insert_order);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $order_id = $stmt->insert_id;
            $stmt->close();

            // Link user and order in Place_Order table
            $place_order = "INSERT INTO Place_Order (Order_ID, User_ID) VALUES (?, ?)";
            $stmt = $conn->prepare($place_order);
            $stmt->bind_param("ii", $order_id, $user_id);
            $stmt->execute();
            $stmt->close();

            // Add movies to Order_Contain
            $add_movies = "INSERT INTO Order_Contain (Order_ID, Movie_ID) VALUES (?, ?)";
            $stmt = $conn->prepare($add_movies);
            foreach ($_SESSION['cart'] as $movie_id) {
                $stmt->bind_param("ii", $order_id, $movie_id);
                $stmt->execute();
            }
            $stmt->close();

            // Commit the transaction and clear the cart
            $conn->commit();
            $_SESSION['cart'] = [];
            echo "<script>alert('Purchase successful!'); location.href = 'thankyou.php';</script>";
        } catch (Exception $e) {
            $conn->rollback();
            echo "<script>alert('Purchase failed!');</script>";
        }
    }
}

// Fetch movies in the cart
$cart_items = [];
if (!empty($_SESSION['cart'])) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $query = "SELECT Movie_ID, Movie_Name, Price FROM Movie WHERE Movie_ID IN ($placeholders)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat("i", count($_SESSION['cart'])), ...$_SESSION['cart']);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Calculate total price
$total_price = array_reduce($cart_items, fn($sum, $item) => $sum + $item['Price'], 0);
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
  <!-- Navigation Bar -->
  <?php include 'navigate.php'; ?>

  <!-- Order Section -->
  <div class="order-page">
    <h1 class="order-title">Your Order</h1>

    <!-- Order Items -->
    <div class="order-items">
      <?php if (!empty($cart_items)): ?>
        <?php foreach ($cart_items as $item): ?>
          <div class="order-item">
            <div class="item-details">
              <h3 class="movie-title"><?php echo htmlspecialchars($item['Movie_Name']); ?></h3>
              <button class="remove-button" onclick="removeItem(<?php echo $item['Movie_ID']; ?>)">REMOVE</button>
            </div>
            <div class="item-info">
              <span class="quantity">1</span>
              <span class="price"><?php echo htmlspecialchars(number_format($item['Price'], 2)) . "$/WEEK"; ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No items in your cart yet. <a href="mainpage.php">Start shopping</a>.</p>
      <?php endif; ?>
    </div>

    <!-- Total Price -->
    <?php if (!empty($cart_items)): ?>
      <div class="total-price">
        <span>TOTAL PRICE:</span>
        <span class="price"><?php echo htmlspecialchars(number_format($total_price, 2)) . "$"; ?></span>
      </div>

      <!-- Purchase Button -->
      <form method="post">
        <button type="submit" name="purchase" class="pay-button">PURCHASE</button>
      </form>
    <?php endif; ?>

    <!-- Continue Shopping -->
    <a href="mainpage.php" class="continue-shopping">Continue Shopping</a>
  </div>

  <script>
    // Function to remove an item from the cart
    function removeItem(movieId) {
      if (confirm("Are you sure you want to remove this item from your cart?")) {
        fetch(`?action=remove&movie_id=${movieId}`)
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert("Item removed successfully!");
              location.reload();
            } else {
              alert(data.message);
            }
          })
          .catch(error => console.error("Error:", error));
      }
    }
  </script>
</body>

</html>
