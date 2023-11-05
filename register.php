<?php
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
    $sql = "INSERT INTO end_user (end_firstname, end_lastname, end_username, end_password) VALUES (?, ?, ?, ?)";
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
    <!-- Add your CSS and other meta tags here -->
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
    <input type="text" id="first_name" name="first_name" required>

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" required>

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit" class="register-button">Register</button>
</form>


    <!-- Add a link to go to the Login page -->
    <a href="login.php">Go to Login</a>
</body>
</html>
