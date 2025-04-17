<?php
session_start();
require 'database.php';
// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login_signup.php");
    exit();
}
$userID = $_SESSION['userID'];
$fName = $_SESSION['fName'];
// Add new list
if (!empty($task) && !empty($listID)) {
    $stmt = $conn->prepare("INSERT INTO tasks (userID, listID, task) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $userID, $listID, $task);
    $stmt->execute();
    $stmt->close();
}

    header("Location: task.php");
    exit();
// Add task to selected list
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    $task = trim($_POST['task']);
    $listID = $_POST['listID'];


    if (!empty($task) && !empty($listID)) {
        $stmt = $conn->prepare("INSERT INTO tasks (userID, listID, task) VALUES (?, ?, ?)");
        $stmt->bind_param("iisss", $userID, $listID, $task);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: task.php");
    exit();
}
// Delete task
if (isset($_GET['delete_task'])) {
    $taskID = $_GET['delete_task'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE taskID = ? AND userID = ?");
    $stmt->bind_param("ii", $taskID, $userID);
    $stmt->execute();
    $stmt->close();
    header("Location: task.php");
    exit();
}

// Delete list and its tasks
if (isset($_GET['delete_list'])) {
    $listID = $_GET['delete_list'];
    $conn->begin_transaction();
    $conn->query("DELETE FROM tasks WHERE listID = $listID AND userID = $userID");
    $conn->query("DELETE FROM task_lists WHERE listID = $listID AND userID = $userID");
    $conn->commit();
    header("Location: task.php");
    exit();
}

// Fetch lists
$list_query = $conn->prepare("SELECT * FROM task_lists WHERE userID = ?");
$list_query->bind_param("i", $userID);
$list_query->execute();
$lists_result = $list_query->get_result();

// Default selected list
$selected_listID = isset($_POST['listID']) ? $_POST['listID'] : null;

// Fetch tasks from selected list
$task_query = $conn->prepare("SELECT * FROM tasks WHERE userID = ? AND listID = ?");
$task_query->bind_param("ii", $userID, $selected_listID);
$task_query->execute();
$tasks_result = $task_query->get_result();

$list_query->close();
$task_query->close();
$conn->close();
?>
<?php include("header.php"); ?>
<style>
    .container { max-width: 700px; margin: auto; padding: 20px; }
    .task-form, .list-form { margin-bottom: 20px; }
    .task-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; padding: 10px; background: #f9f9f9; border-radius: 5px; }
    .delete-btn, .delete-list-btn { color: red; text-decoration: none; margin-left: 10px; }
    .list-select { margin-bottom: 15px; }
</style>
<div class="container">
    <h2><?= htmlspecialchars($fName) ?>'s Task Lists</h2>

    <!-- Add New List -->
    <form method="POST" class="list-form">
        <input type="text" name="listName" placeholder="New list name" required>
        <button type="submit" name="add_list">+ Add List</button>
    </form>

    <!-- Select a List -->
    <form method="POST" class="list-select">
        <select name="listID" onchange="this.form.submit()">
            <option value="">Select a list</option>
            <?php while ($list = $lists_result->fetch_assoc()): ?>
                <option value="<?= $list['listID'] ?>" <?= $selected_listID == $list['listID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($list['listName']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <?php if ($selected_listID): ?>
        <!-- Add Task Form -->
        <form method="POST" class="task-form">
            <input type="hidden" name="listID" value="<?= $selected_listID ?>">
            <input type="text" name="task" placeholder="+Add to List" required>

            <button type="submit" name="add_task"> + </button>
        </form>

        <!-- Task List -->
        <div class="task-list">
            <?php if ($tasks_result->num_rows > 0): ?>
                <ul>
                    <?php while ($task = $tasks_result->fetch_assoc()): ?>
                        <li class="task-item">
                            <span><strong><?= htmlspecialchars($task['task']) ?></strong> </span>
                            <a href="?delete_task=<?= $task['taskID'] ?>" class="delete-btn">❌</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No tasks in this list.</p>
            <?php endif; ?>

            <!-- Delete List -->
            <a href="?delete_list=<?= $selected_listID ?>" class="delete-list-btn">🗑️ Delete This List</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
