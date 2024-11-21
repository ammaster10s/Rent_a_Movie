<?php
// session_start();
include 'database.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

include 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Borrow History</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
    include 'navigate.php';
    ?>
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
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>No Time To Die</td>
            <td>1/10/2004</td>
            <td>8/10/2004</td>
            <td>7 days</td>
          </tr>
          <tr>
            <td>A Quiet Place</td>
            <td>8/10/2004</td>
            <td>15/10/2004</td>
            <td>7 days</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>