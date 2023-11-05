<?php
require_once 'config.php';
// Check if the user is already logged in
if (isset($_SESSION['logged_in_user'])) {
    header('Location: index.php');
    exit;
}

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Prepare SQL query to select user data based on the provided username
    $sql = "SELECT * FROM user WHERE end_username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();

    // Get result from the query
    $result = $stmt->get_result();

    // Check if the user exists and verify the password
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['end_password'];

        if (password_verify($password, $hashedPassword)) {
            // Successful login
            $_SESSION['logged_in_user'] = $username;
            header('Location: index.php');
            exit;
        } else {
            $loginErrorMessage = "Invalid password. Please try again.";
        }
    } else {
        $loginErrorMessage = "User not found. Please check your username and password.";
    }

    // Close database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>End User Login</title>
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
      width: 100%;
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
  <div class="login-box">
    <h1>End User Login</h1>

    <?php if (isset($loginErrorMessage)): ?>
      <p class="login-error"><?php echo $loginErrorMessage; ?></p>
    <?php endif; ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Login</button>
      <a href="register.php"> Dont have an account? </a>
    </form>
  </div>
</body>
</html>

