<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liberia Style Collective - Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <img src="logo.png" alt="Liberia Style Collective Logo">
        <form action="login" method="POST" class="login-form">
            <input type="text" name="username_email" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username_email"]) && isset($_POST["password"]) && !empty(trim($_POST["username_email"])) && !empty(trim($_POST["password"]))) {
        include "db_connection.php";

        $stmt = $conn->prepare("SELECT * FROM Users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username_email, $username_email);

        $username_email = trim($_POST["username_email"]);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($_POST["password"], $user["password"])) {
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"] = $user["role"];

                header("Location: dashboard");
                exit();
            } else {
                $error_message = "Invalid username/email or password.";
            }
        } else {
            $error_message = "Invalid username/email or password.";
        }

        $stmt->close();
        $conn->close();
    } else {
        $error_message = "Please enter your username/email and password.";
    }
}
?>
