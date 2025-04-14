<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task-List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
    <a href="index.php"><img src="img/logo.png" alt="Task List Logo" class="logo"></a>
        <nav>
            <a href="index.php"><button>Home</button></a>
            <a href="account.php"><button>Task</button></a>
            <a href="weather.php"><button>Weather</button></a>

            <?php if (isset($_SESSION['name'])): ?>
                <a href="logout.php"><button>Logout</button></a>
            <?php else: ?>
                <a href="account.php"><button>Login</button></a>
            <?php endif; ?>
        </nav>
    </header>
