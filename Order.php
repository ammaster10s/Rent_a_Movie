<?php
include 'database.php';
include 'auth_check.php';

// Fetch the current user's ID
$user_id = $_SESSION['user_id'];

// Fetch the current order for the user
$query = "SELECT Order_ID FROM Orders WHERE User_ID = ? AND Payment_ID IS NULL";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Database error: {$conn->error}");
}
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($order_id);
$stmt->fetch();
$stmt->close();

$cart_items = [];
$total_price = 0; // Initialize total price

if ($order_id) {
    // Fetch movies in the cart
    $query = "
        SELECT m.Movie_ID, m.Movie_Name, m.Price 
        FROM Order_Contain oc
        INNER JOIN Movie m ON oc.Movie_ID = m.Movie_ID
        WHERE oc.Order_ID = ?
    ";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Database error: " . $conn->error);
    }
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Calculate total price
    foreach ($cart_items as $item) {
        $total_price += $item['Price'];
    }
}
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

    <div class="order-page">
        <h2>Your Order</h2>

        <?php if (!empty($cart_items)): ?>
            <?php foreach ($cart_items as $item): ?>
                <div class="order-item">
                    <div class="item-details">
                        <span class="movie-title"><?php echo htmlspecialchars($item['Movie_Name']); ?></span>
                        <span class="quantity">1</span>
                        <span class="price"><?php echo htmlspecialchars(number_format($item['Price'], 2)) . "$/WEEK"; ?></span>
                    </div>
                    <form action="Model/removeOrderItem.php" method="POST">
                        <input type="hidden" name="movie_id" value="<?php echo $item['Movie_ID']; ?>">
                        <button type="submit" onclick="return confirm('Are you sure?');">Remove</button>
                    </form>
                </div>
            <?php endforeach; ?>

            <div class="total-price">
                <span>Total Price:</span>
                <span id="total-price"><?php echo htmlspecialchars(number_format($total_price, 2)) . "$"; ?></span>
            </div>

            <form action="Payment.php" method="POST">
                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
                <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($total_price); ?>">
                <button class="pay-button">Pay Now</button>
            </form>

        <?php else: ?>
            <p>No items in your order yet. <!--<a href="mainpage.php" class="continue-shopping">Start shopping</a>--></p>
        <?php endif; ?>

        <a href="mainpage.php" class="continue-shopping">Continue Shopping</a>
    </div>

    <script>
        function updateTotalPrice() {
            const prices = document.querySelectorAll('.order-item .price');
            let total = 0;
            prices.forEach(price => {
                total += parseFloat(price.textContent.replace('$', ''));
            });
            document.getElementById('total-price').textContent = total.toFixed(2) + "$";
        }
    </script>
</body>

</html>
