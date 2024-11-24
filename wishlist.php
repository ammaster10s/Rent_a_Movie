<?php
include 'database.php';
include 'auth_check.php';

$userId = $_SESSION['user_id'];

// Fetch wishlist items
$query = "
    SELECT m.Movie_ID, m.Movie_Name, m.Poster_Path, m.Description, m.Price 
    FROM Wishlist w
    INNER JOIN Movie m ON w.Movie_ID = m.Movie_ID
    WHERE w.User_ID = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$wishlist_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wishlist</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
<?php include 'navigate.php'; ?>

<div class="wishlist-container">
  <h2>Your Wishlist</h2>

  <?php if (!empty($wishlist_items)): ?>
    <?php foreach ($wishlist_items as $item): ?>
      <div class="wishlist-item">
        <div class="movie-poster">
          <img src="<?php echo htmlspecialchars($item['Poster_Path']); ?>" alt="<?php echo htmlspecialchars($item['Movie_Name']); ?>">
        </div>
        <div class="movie-details">
          <h2><?php echo htmlspecialchars($item['Movie_Name']); ?></h2>
          <p><?php echo htmlspecialchars($item['Description']); ?></p>
          <p><strong>Price:</strong> $<?php echo htmlspecialchars($item['Price']); ?></p>
          <div class="actions">
            <button class="add-to-cart" onclick="addToCart(<?php echo $item['Movie_ID']; ?>)">Add to Cart</button>
            <button class="remove-from-wishlist" onclick="removeFromWishlist(<?php echo $item['Movie_ID']; ?>)">Remove</button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p><span style="color: white;">Your wishlist is empty.</span> <a href="mainpage.php">Browse movies</a><span style="color: white;"> to add items.</span></p>

  <?php endif; ?>
</div>

<script>
    function addToCart(movieId) {
      fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ movieId }),
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Movie added to cart!');
          } else {
            alert(`Error: ${data.message}`);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while adding the movie to the cart.');
        });
    }

    function removeFromWishlist(movieId) {
      fetch('remove_from_wishlist.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ movieId }),
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Movie removed from wishlist!');
            location.reload(); // Refresh the page to reflect changes
          } else {
            alert(`Error: ${data.message}`);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while removing the movie from the wishlist.');
        });
    }
</script>
</body>

</html>
