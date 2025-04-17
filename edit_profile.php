<?php
session_start();
require 'database.php';

// Redirect if not logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login_signup.php");
    exit();
}

$userID = $_SESSION['userID'];
$success = "";
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newFName = trim($_POST["fName"]);
    $newEmail = trim($_POST["eMail"]);
    $newPhone = trim($_POST["phoneNum"]);

    if (empty($newFName) || empty($newEmail)) {
        $error = "First name and email are required.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET fName = ?, eMail = ?, phoneNum = ? WHERE userID = ?");
        $stmt->bind_param("sssi", $newFName, $newEmail, $newPhone, $userID);

        if ($stmt->execute()) {
            $success = "Profile updated successfully.";
        } else {
            $error = "Error updating profile.";
        }

        $stmt->close();
    }
}

// Get current user data
$stmt = $conn->prepare("SELECT fName, eMail, phoneNum FROM users WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($fName, $eMail, $phoneNum);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<?php include("header.php"); ?>

<div class="container" style="max-width: 600px; margin: 40px auto;">
    <h2>Edit Profile</h2>

    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php elseif ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" action="edit_profile.php">
        <label for="fName">First Name:</label>
        <input type="text" id="fName" name="fName" value="<?php echo htmlspecialchars($fName); ?>" required>

        <label for="eMail">Email:</label>
        <input type="email" id="eMail" name="eMail" value="<?php echo htmlspecialchars($eMail); ?>" required>

        <label for="phoneNum">Phone Number:</label>
        <input type="text" id="phoneNum" name="phoneNum" value="<?php echo htmlspecialchars($phoneNum); ?>">

        <button type="submit">Save Changes</button>
    </form>

    <br>
    <a href="account.php">Back to Account</a>
</div>

</body>
</html>
