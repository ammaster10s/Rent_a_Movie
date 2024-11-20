<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <!-- Navigation Bar -->
    <?php
    include 'navigate.php';
    ?>

    <!-- Login Form Section -->
    <div class="LOGIN">
        <div class="form-container">
            <form action="handle_request.php" method="post">
                <h2 class="form-title">Login</h2>

                <!-- Display Errors -->
                <?php if (!empty($errors)): ?>
                    <div class="error-messages">
                        <?php foreach ($errors as $error): ?>
                            <p style="color: red;"><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Email Address -->
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email address" required />

                <!-- Password -->
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="input-field" placeholder="Enter your password" required />

                <!-- Submit Button -->
                <button type="submit" name="login" class="submit-button">LOG IN</button>
            </form>
        </div>
    </div>
</body>

</html>