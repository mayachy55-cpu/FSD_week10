<?php
require 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$email) {
        $message = "Invalid email format";
    } elseif (empty($password) || strlen($password) < 6) {
        $message = "Password must be at least 6 characters";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hash);

        if ($stmt->execute()) {
            $message = "Signup successful. You can login now.";
        } else {
            $message = "Error creating account";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<body>
<h2>Signup</h2>
<form method="post">
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Signup</button>
</form>
<p><?= htmlspecialchars($message) ?></p>
<a href="login.php">Go to Login</a>
</body>
</html>
