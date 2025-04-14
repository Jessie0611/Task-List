<?php
session_start();
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            header("Location: account.php");
            exit();
        } else {
            // Redirect back with error
            header("Location: index.php?error=wrongpassword");
            exit();
        }
    } else {
        header("Location: signuplogin.php?error=noaccount");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
