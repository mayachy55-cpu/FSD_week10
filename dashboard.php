<?php
require 'session.php';
require 'db.php';

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo '<a href="login.php">Login</a>';
    exit;
}

$stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<body>
<h2>Dashboard</h2>

<p>Welcome, <?= htmlspecialchars($user['email']) ?></p>

<form method="post">
    <button type="submit" name="logout">Logout</button>
</form>
</body>
</html>
