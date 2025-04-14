<?php if (isset($_GET['error'])): ?>
    <p style="color: red;">
        <?php
        if ($_GET['error'] == 'wrongpassword') echo "Incorrect password.";
        if ($_GET['error'] == 'noaccount') echo "No account found with that email.";
        ?>
    </p>
<?php endif; ?>

<?php include("header.php"); ?>

        <div class="container">
        <h2>Welcome to Task-List</h2>
            <h2>Sign Up</h2>
            <form action="signup.php" method="POST">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Sign Up</button>
            </form>
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        </div>
</body>
</html>
