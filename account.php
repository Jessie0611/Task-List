<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    // If not, redirect to login page
    header("Location: login_signup.php");
    exit();
}

require 'database.php';

// Get user info from the database
$userID = $_SESSION['userID'];

$stmt = $conn->prepare("SELECT fName, eMail FROM users WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($fName, $eMail);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<?php include("header.php"); ?>

<div class="container">
    <h2>Hello, <?php echo htmlspecialchars($fName); ?>!</h2>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($eMail); ?></p>

    <form action="logout.php" method="post">
        <button type="submit">Log Out</button>
    </form>
</div>
</body>
</html>
