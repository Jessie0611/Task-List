<?php
session_start();
require 'database.php';

// Fetch tasks for the logged-in user
$userID = $_SESSION['userID'];
$query = "SELECT * FROM tasks WHERE userID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Add a new task if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    $task = $_POST['task'];
    if (!empty($task)) {
        $stmt = $conn->prepare("INSERT INTO tasks (userID, task) VALUES (?, ?)");
        $stmt->bind_param("is", $userID, $task);
        $stmt->execute();
        header("Location: task.php");  // Reload the page to show new task
        exit();
    }
}

// Delete task
if (isset($_GET['delete'])) {
    $taskID = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE taskID = ?");
    $stmt->bind_param("i", $taskID);
    $stmt->execute();
    header("Location: task.php");  // Reload the page after deletion
    exit();
}

$stmt->close();
$conn->close();
?>
<?php include("header.php"); ?>
<div class="container"><h2>Task List</h2>
    
    <!-- Task List Form -->
    <form action="task.php" method="POST" class="task-form">
        <input type="text" name="task" placeholder="Enter a new task" required>
        <button type="submit" name="add_task">+Task</button>
    </form>
    
    <div class="task-list">
        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="task-item">
                        <?php echo htmlspecialchars($row['task']); ?>
                        <a href="task.php?delete=<?php echo $row['taskID']; ?>" class="delete-btn">❌</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No tasks added yet. Start by adding one!</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
