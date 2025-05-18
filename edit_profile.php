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
  <header class="page-header">
    <h1>Task-<span class="highlight">List</span></h1>
    <p class="subtitle">Getting things done like a pro!</p>
  </header>
<div class="container" style="max-width: 600px; margin: 40px auto;">
    <br> <br>
    <h1>Edit <span class="highlight">Profile</span></h1>
    <br>

    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php elseif ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

<div class="editP" style=" text-align: left; margin-left: 100px; font-size: 1.1em; ">
    <form method="post" action="edit_profile.php">
        <label for="fName">Name: &nbsp;</label>
        <input type="text" id="fName" name="fName" value="<?php echo htmlspecialchars($fName); ?>" required> <br>

        <label for="eMail">E-Mail: &nbsp;</label>
        <input type="email" id="eMail" name="eMail" value="<?php echo htmlspecialchars($eMail); ?>" required> <br>

        <label for="phoneNum">Phone: &nbsp;</label>
        <input type="text" id="phoneNum" name="phoneNum" value="<?php echo htmlspecialchars($phoneNum); ?>"> <br>
</div> <br>
        <button type="submit">Save Changes</button>
    </form>
<br>
    <br>
    <div class="sign"><a href="account.php">☑️ <u> Go Back to <span class="lowlight">Account Page!</u></span></a></div>
   </div>

</body>
</html>
