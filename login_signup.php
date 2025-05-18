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

        $stmt = $conn->prepare("SELECT userID, fName, password FROM users WHERE eMail = ?");
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
<header class="page-header">
    <h1>Welcome to <span class="highlight">Task-List</span></h1>
    <p class="subtitle">Get things done like a pro with the #1 task management platform!</p>
  </header>

    <?php if (!empty($message)): ?>
        <p style="color: darkred;"><strong><?= $message ?></strong></p>
    <?php endif; ?>

    <h2>Sign <span class="highlight">Up</span></h2>
    <form action="login_signup.php" method="POST">
        <input type="text" name="name" placeholder="Preferred Name" required> <br>
        <input type="email" name="email" placeholder="Email" required> <br>
        <input type="password" name="password" placeholder="Password" required> <br>
        <button type="submit" name="signup">Sign Up</button>
    </form>
<br>
    <h2>Log<span class="highlight">in</span></h2>
    <form action="login_signup.php" method="POST">
        <input type="email" name="email" placeholder="Email" required> <br>
        <input type="password" name="password" placeholder="Password" required> <br>
        <button type="submit" name="login">Login</button>
    </form>
</div>
</body>
</html>
