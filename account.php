<?php
session_start();
require 'database.php';

$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';
?>

<?php include("header.php"); ?>

<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($name); ?></h1>
</div>
