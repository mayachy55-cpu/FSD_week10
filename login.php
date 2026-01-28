<?php
require 'session.php';
require 'db.php';

$error = "";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Invalid email or password";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard.php");
                exit;
            }
        }
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<body>
<h2>Login</h2>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    Email: <input type="email" name="email"><br><br>
    Password: <input type="password" name="password"><br><br>
    <button type="submit">Login</button>
</form>

<p><?= htmlspecialchars($error) ?></p>
<a href="signup.php">Signup</a>
</body>
</html>
