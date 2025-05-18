<?php include("database.php"); 
session_start();
?>
<?php include("header.php"); ?>

<div class="container">
  <header class="page-header">
    <h1>Welcome to <span class="highlight">Task-List</span></h1>
    <p class="subtitle">Get things done like a pro with the #1 task management platform!</p>
  </header>
<br><br>
  <section class="features">
    <ul class="feature-list">
      <li>
        <input type="checkbox" id="plan" name="features" value="Plan">
        <label for="plan">Plan</label>
      </li>
      <li>
        <input type="checkbox" id="organize" name="features" value="Organize">
        <label for="organize">Organize</label>
      </li>
      <li>
        <input type="checkbox" id="track" name="features" value="Track">
        <label for="track">Track</label>
      </li>
      <li>
        <input type="checkbox" id="task" name="features" value="Task">
        <label for="task">Task & To-do's</label>
      </li>
      <li>
        <input type="checkbox" id="goal" name="features" value="Goal">
        <label for="goal">Goals</label>
      </li>
    </ul>
    
    <p class="description">
      Your tasks, goals, and lists—effortlessly organized in one super-flexible platform.
      <br>
    <div class="sign"><a href="login_signup.php">☑️Check <br>
       Sign up <span class="lowlight">for free!</span></a></div>
    </p>