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

<div class="container" style="max-width: 600px; margin: 40px auto;">

    <h2>Hello, <?php echo htmlspecialchars($fName); ?>!</h2>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($eMail); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($phoneNum); ?></p>

    <!-- Profile Image -->
    <div style="margin: 20px 0;">
        <img src="<?php echo $profilePic ? htmlspecialchars($profilePic) : 'default-profile.png'; ?>" 
             alt="Profile Picture" 
             style="width: 180px; height: 180px; border-radius: 50%; object-fit: cover;">
    </div>

    <!-- Upload Form -->
    <form method="post" enctype="multipart/form-data" style="margin-bottom: 20px;">
        <label for="profilePic">Upload new profile photo:</label><br>
        <input type="file" name="profilePic" id="profilePic" required>
        <button type="submit">Upload</button>
    </form>

    <?php if ($uploadMessage): ?>
        <p style="color: green;"><?php echo $uploadMessage; ?></p>
    <?php endif; ?>

    <!-- Buttons -->
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <form action="edit_profile.php" method="get">
            <button class="accBtn">Edit Profile</button>
        </form>

        <form action="change_password.php" method="get">
            <button class="accBtn">Change Password</button>
        </form>

        <form action="logout.php" method="post">
            <button class="accBtn">Log Out</button>
        </form>
    </div>
</div>

</body>
</html>
