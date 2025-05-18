<?php include("header.php"); ?>
  <header class="page-header">
    <h1>Task-<span class="highlight">List</span></h1>
    <p class="subtitle">Getting things done like a pro!</p>
  </header>
<div class="container" style="max-width: 600px; margin: 40px auto; font-size: 1.1em; ">
    <br><br>
    <h1>Change <span class="highlight">Password</span></h1>
<br>
<form method="post" action="change_password.php">
        <label for="fName">Enter New Password: &nbsp;</label>
 <input type="password" name="password" placeholder="Password" required><br>
        <label for="fName">Re-Enter New Password: &nbsp;</label>
 <input type="password" name="password" placeholder="Password" required><br>

 <br><button type="submit">Save Changes</button><br><br>        

     <div class="sign"><a href="account.php">☑️ <u> Go Back to <span class="lowlight">Account Page!</u></span></a></div>
