<?php
include 'database.php';
include 'auth_check.php';

if (!isset($_SESSION['user_id'])) {
  echo "Please log in to view your borrow history.";
  exit;
}

$user_id = $_SESSION['user_id'];

$query = "
SELECT 
    o.Order_ID,
    m.Movie_Name,
    m.Price,
    p.Payment_Date AS Issue_Date,
    DATE_ADD(p.Payment_Date, INTERVAL 7 DAY) AS Due_Date,
    DATEDIFF(DATE_ADD(p.Payment_Date, INTERVAL 7 DAY), p.Payment_Date) AS Period
FROM Orders o
INNER JOIN Place_Order po ON o.Order_ID = po.Order_ID
INNER JOIN Order_Contain oc ON o.Order_ID = oc.Order_ID
INNER JOIN Movie m ON oc.Movie_ID = m.Movie_ID
INNER JOIN Payment p ON o.Payment_ID = p.Payment_ID
WHERE po.User_ID = ?
ORDER BY m.Movie_Name ASC;
";

$stmt = $conn->prepare($query);

if (!$stmt) {
  die("Query preparation failed: {$conn->error}");
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if (!$result) {
  die("Query execution failed: {$stmt->error}");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Borrow History</title>
  <link rel="stylesheet" href="style.css"> <!-- Link external stylesheet -->
</head>

<body>
  <?php include 'navigate.php'; ?> <!-- Include your navigation -->

  <!-- Borrow History Section -->
  <div class="history">
    <div class="container">
      <h1>Borrow History</h1>
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
          <?php
          include 'database.php';
          include 'auth_check.php';

          if (!isset($_SESSION['user_id'])) {
            echo '<tr><td colspan="5" class="no-data">Please log in to view your borrow history.</td></tr>';
            exit;
          }

          $user_id = $_SESSION['user_id'];
          $query = "
          SELECT 
              o.Order_ID,
              m.Movie_Name,
              m.Price,
              p.Payment_Date AS Issue_Date,
              DATE_ADD(p.Payment_Date, INTERVAL 7 DAY) AS Due_Date,
              DATEDIFF(DATE_ADD(p.Payment_Date, INTERVAL 7 DAY), p.Payment_Date) AS Period
          FROM Orders o
          INNER JOIN Place_Order po ON o.Order_ID = po.Order_ID
          INNER JOIN Order_Contain oc ON o.Order_ID = oc.Order_ID
          INNER JOIN Movie m ON oc.Movie_ID = m.Movie_ID
          INNER JOIN Payment p ON o.Payment_ID = p.Payment_ID
          WHERE po.User_ID = ?
          ORDER BY Due_Date ASC; 
          ";


          $stmt = $conn->prepare($query);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['Movie_Name'] ?? 'N/A') . "</td>";
              echo "<td>" . htmlspecialchars($row['Issue_Date'] ?? 'N/A') . "</td>";
              echo "<td>" . htmlspecialchars($row['Due_Date'] ?? 'N/A') . "</td>";
              echo "<td>" . htmlspecialchars($row['Period'] ?? '0') . " days</td>";
              echo "<td>" . htmlspecialchars(number_format($row['Price'] ?? 0, 2)) . " $</td>";
              echo "</tr>";
            }
          } else {
            echo '<tr><td colspan="5" class="no-data">No borrow history found.</td></tr>';
          }

          if ($stmt) {
            $stmt->close();
          }
          $conn->close();
          ?>
        </tbody>
      </table>

      <!-- Export Button -->
      <button onclick="exportHistory()">Export History</button>
    </div>
  </div>

  <script>
    function exportHistory() {
      alert('Export functionality coming soon!');
    }
  </script>
</body>

</html>

<?php
// $stmt->close();
// $conn->close();
?>