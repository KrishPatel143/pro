<?php
session_start();

include("connection.php");
include("functions.php");

if (isset($_POST['submit'])) {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if (!empty($user_name) && !empty($password)) {
        // Use prepared statement to prevent SQL injection
        $query = "SELECT * FROM users WHERE user_name = ? LIMIT 1";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $user_name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            // Verify hashed password
            if (password_verify($password, $user_data['password'])) {
                $_SESSION['user_id'] = $user_data['user_id'];
                header("Location: index.php");
                exit;
            } else {
                echo "Wrong password!";
            }
        } else {
            echo "User not found!";
        }
    } else {
        echo "Please enter both username and password!";
    }
}
?>
<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <form action="login_submit.php" method="POST" class="user-form">
            <div class="form-row">
                <label for="login-email">Email:</label>
                <input type="email" id="login-email" name="email" required>
            </div>
            <div class="form-row">
                <label for="login-password">Password:</label>
                <input type="password" id="login-password" name="password" required>
            </div>
            
            <div class="form-row">
                <button type="submit" class="submit-btn">Log In</button>
            </div>
        </form>
        <p>Don't have an account? <a href="signup.html">Sign Up</a></p>
    </div>
</body>
</html> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <form  method="post" class="user-form">
            <div class="form-row">
                <label for="login-email">Email:</label>
                <input type="text" id="login-email" name="user_name" required>
            </div>
            <div class="form-row">
                <label for="login-password">Password:</label>
                <input type="password" id="login-password" name="password" required>
            </div>
            
            <div class="form-row">
                <button type="submit" class="submit-btn" name="submit">Log In</button>
            </div>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</body>
</html> 

