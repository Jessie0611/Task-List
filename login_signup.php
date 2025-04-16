<?php
session_start();
require 'database.php';

$message = ""; // For success/error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {  //SIGNUP
  
    if (isset($_POST['signup'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $raw_password = $_POST['password'];
        $password = password_hash($raw_password, PASSWORD_DEFAULT);

        $check = $conn->prepare("SELECT * FROM users WHERE eMail = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "⚠️ Email already registered. Try logging in.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (fName, eMail, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password);

            if ($stmt->execute()) {
                $message = "✅ Signup successful! Please log in.";
            } else {
                $message = "❌ Sign up failed. Try again.";
            }

            $stmt->close();
        }
        $check->close();
    }

    if (isset($_POST['login'])) {    //LOGIN
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT userID, fName, password FROM tasks WHERE eMail = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($userID, $fName, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['userID'] = $userID;
                $_SESSION['fName'] = $fName;
                header("Location: account.php");
                exit();
            } else {
                $message = "❌ Invalid password.";
            }
        } else {
            $message = "❌ No account found with that email.";
        }

        $stmt->close();
    }

    $conn->close();
}
?>
<?php include("header.php"); ?>
<div class="container">
    <h2>Welcome to Task-List</h2>

    <?php if (!empty($message)): ?>
        <p style="color: darkred;"><strong><?= $message ?></strong></p>
    <?php endif; ?>

    <h2>Sign Up</h2>
    <form action="login_signup.php" method="POST">
        <input type="text" name="name" placeholder="Preferred Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="signup">Sign Up</button>
    </form>

    <h2>Login</h2>
    <form action="login_signup.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</div>
</body>
</html>
