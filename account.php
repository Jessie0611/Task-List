<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login_signup.php");
    exit();
}
require 'database.php';
$userID = $_SESSION['userID'];
$uploadMessage = "";

// Handle photo upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profilePic"])) {
    $targetDir = "uploads/";
    $fileName = basename($_FILES["profilePic"]["name"]);
    $targetFile = $targetDir . time() . "_" . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $validTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $validTypes)) {
        if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $targetFile)) {
            // Save path in database
            $stmt = $conn->prepare("UPDATE users SET profilePic = ? WHERE userID = ?");
            $stmt->bind_param("si", $targetFile, $userID);
            $stmt->execute();
            $stmt->close();
            $uploadMessage = "Profile photo uploaded successfully!";
        } else {
            $uploadMessage = "Error uploading file.";
        }
    } else {
        $uploadMessage = "Only JPG, JPEG, PNG & GIF files are allowed.";
    }
}

// Get user info
$stmt = $conn->prepare("SELECT fName, eMail, phoneNum, profilePic FROM users WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($fName, $eMail, $phoneNum, $profilePic);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<?php include("header.php"); ?>
  <header class="page-header">
    <h1>Task-<span class="highlight">List</span></h1>
    <p class="subtitle">Getting things done like a pro!</p>
  </header>
<div class="container" style="max-width: 600px; margin: 40px auto; font-size: 1.1em; ">
    <h1>Hello,<span class="highlight"> <?php echo htmlspecialchars($fName); ?>!</span></h1>

    <!-- Profile Image -->
    <div style="margin: 20px 0;">
        <img src="<?php echo $profilePic ? htmlspecialchars($profilePic) : 'default-profile.png'; ?>" 
             alt="Profile Picture" 
             style="width: 280px; height: 280px; border-radius: 10%; object-fit: cover;">
    </div>

    <!-- Upload Form -->
    <form method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
        <label for="profilePic">Upload new profile photo:</label><br>
        <input type="file" name="profilePic" id="profilePic" required>
        <button type="upload">Upload</button>
    </form>

    <?php if ($uploadMessage): ?>
        <p style="color: green;"><?php echo $uploadMessage; ?></p>
    <?php endif; ?>

 <div class="userInfo">
    <strong>E-Mail:&nbsp;</strong> <?php echo htmlspecialchars($eMail); ?> <br>
    <strong>Phone:&nbsp;</strong> <?php echo htmlspecialchars($phoneNum); ?></p>
 </div>

    <!-- Buttons -->
    <div class="accBtns">
        <form action="edit_profile.php" method="get">
            <button class="accBtn">Edit Info</button>
        </form>

        <form action="change_password.php" method="get">
            <button class="accBtn">Change Password</button>
        </form>
    </div>
</div>

</body>
</html>
