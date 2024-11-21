<?php
include 'database.php';
include 'auth_check.php';

// Fetch borrow history for the logged-in user
$user_id =   $_SESSION['user_id']; // Assuming the user's ID is stored in the session
$query = "
SELECT 
    o.Order_ID,
    m.Movie_Name,
    m.Price
FROM Orders o
INNER JOIN Place_Order po ON o.Order_ID = po.Order_ID
INNER JOIN Order_Contain oc ON o.Order_ID = oc.Order_ID
INNER JOIN Movie m ON oc.Movie_ID = m.Movie_ID
WHERE po.User_ID = ?
ORDER BY m.Movie_Name ASC;


";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Borrow History</title>
  <link rel="stylesheet" href="globals.css">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <?php include 'navigate.php'; ?>
  <div class="container">
    <h1>Your Borrow History</h1>
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Movie Name</th>
            <th>Issue Date</th>
            <th>Due Date</th>
            <th>Period</th>
            <th>Price</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['Movie_Name']); ?></td>
                <td><?php echo htmlspecialchars($row['Issue_Date']); ?></td>
                <td><?php echo htmlspecialchars($row['Due_Date']); ?></td>
                <td><?php echo htmlspecialchars($row['Period']) . ' days'; ?></td>
                <td><?php echo htmlspecialchars($row['Price']) . ' $'; ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="5">No borrow history found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>
<?php
$stmt->close();
$conn->close();
?>