<?php
session_start();

include ("connection.php");
include ("functions.php");

if (isset($_POST['submit'])) {
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!empty($user_name) && !empty($user_email) && !empty($password) && !empty($role) && !is_numeric($user_name)) {

        $user_id = random_num(20);

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (user_id, user_name, user_email, password, role) VALUES ('$user_id', '$user_name', '$user_email', '$hashed_password', '$role')";
        
        mysqli_query($con, $query);

        header("Location: login.php");
        die;
    } else {
        echo "Please enter valid information in all fields!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sign Up</title>
</head>

<body>
    <div class="form-container">
        <h2>Sign Up</h2>
        <form  method="post" class="user-form">
            <div class="form-row">
                <label for="signup-name">Name:</label>
                <input type="text" id="signup-name" name="user_name" required>
            </div>
            <div class="form-row">
                <label for="signup-email">Email:</label>
                <input type="email" id="signup-email" name="user_email" required>
            </div>
            <div class="form-row">
                <label for="signup-password">Password:</label>
                <input type="password" id="signup-password" name="password" required>
            </div>

            <!-- Existing form fields -->

            <div class="form-row">
                <label for="signup-role">Role:</label>
                <select id="signup-role" name="role" required>
                    <option value="">Select your role</option>
                    <option value="organizer">Organizer</option>
                    <option value="participant">Participant</option>
                </select>
            </div>

            <!-- Remaining form fields and submit button -->

            <div class="form-row">
                <button class="submit-btn" type="submit" name="submit">Sign Up</button>

            </div>
        </form>
        <p>Already have an account? <a href="login.php">Log In</a></p>
    </div>
</body>

</html>
