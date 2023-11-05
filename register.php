<?php
session_start();   
require_once('config.php');

// Initialize the registration status variable
$registrationSuccessful = false;
$registrationErrorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Perform input validation here (e.g., minimum password length, character requirements)

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute SQL query to insert user data
    $sql = "INSERT INTO user (end_firstname, end_lastname, end_username, end_password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ssss', $first_name, $last_name, $username, $hashedPassword);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $registrationSuccessful = true;
            } else {
                $registrationErrorMessage = "Registration failed. Please try again.";
            }
        } else {
            $registrationErrorMessage = "Database error during registration.";
        }

        $stmt->close();
    } else {
        $registrationErrorMessage = "Database error during registration preparation.";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
    body {
      background-image: url("<?php echo validate_image($_settings->info('cover')) ?>");
      background-size: cover;
      background-repeat: no-repeat;
      backdrop-filter: contrast(1);
      font-family: sans-serif;
      text-align: center;
    }

    /* Add styles for the login box */
    .login-box {
      width: 300px;
      margin: auto;
      padding: 20px;
      background-color: #fff;
      border: 1px solid #ccc;
    }

    /* Add styles for the login form */
    form {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    label {
      display: block;
      margin-bottom: 10px;
    }

    input {
      padding: 5px;
      border: 1px solid #ccc;
      width: 100%; /* Adjust the width to your preferred size, e.g., width: 250px; */
    }

    button {
      padding: 10px 20px;
      border: none;
      background-color: #007bff;
      color: white;
      cursor: pointer;
      margin-top: 10px;
    }

    /* Add styles for the error message */
    .login-error {
      color: red;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
    <h1>End User Registration</h1>

    <?php if ($registrationSuccessful): ?>
        <p>Registration successful!</p>
    <?php else: ?>
        <p style="color: red;"><?php echo $registrationErrorMessage; ?></p>
    <?php endif; ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" required style="width: 250px;">

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" required style="width: 250px;">

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required style="width: 250px;">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required style="width: 250px;">
    
    <button type="submit" class="register-button" onclick="window.location.href='login.php'">Register</button>


</form>

    <!-- Add a link to go to the Login page -->
    <a href="login.php">Go to Login</a>
</body>
</html>