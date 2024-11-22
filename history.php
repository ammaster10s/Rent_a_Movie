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
    die("Query preparation failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if (!$result) {
    die("Query execution failed: " . $stmt->error);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Borrow History</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f9;
      color: #333;
    }
    .container {
      max-width: 800px;
      margin: 2rem auto;
      padding: 1rem;
      background: white;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }
    h1 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 1rem 0;
    }
    th, td {
      padding: 0.75rem;
      text-align: left;
      border: 1px solid #ddd;
    }
    th {
      background-color: #f8f8f8;
      font-weight: bold;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .no-data {
      text-align: center;
      padding: 1rem;
      font-style: italic;
      color: #777;
    }
  </style>
</head>

<body>
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
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['Movie_Name'] ?? 'N/A'); ?></td>
              <td><?php echo htmlspecialchars($row['Issue_Date'] ?? 'N/A'); ?></td>
              <td><?php echo htmlspecialchars($row['Due_Date'] ?? 'N/A'); ?></td>
              <td><?php echo htmlspecialchars($row['Period'] ?? '0') . ' days'; ?></td>
              <td><?php echo htmlspecialchars(number_format($row['Price'] ?? 0, 2)) . ' $'; ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="no-data">No borrow history found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>

</html>
<?php
$stmt->close();
$conn->close();
?>
