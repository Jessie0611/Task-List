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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_list'])) {
    $list_name = trim($_POST['list_name']);
    if (!empty($list_name)) {
        $stmt = $conn->prepare("INSERT INTO task_lists (userID, list_name) VALUES (?, ?)");
        $stmt->bind_param("is", $userID, $list_name);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: task.php");
    exit();
}
// Add task to selected list
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    $task = trim($_POST['task']);
    $list_id = $_POST['list_id'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];

    if (!empty($task) && !empty($list_id)) {
        $stmt = $conn->prepare("INSERT INTO tasks (userID, list_id, task, due_date, priority) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $userID, $list_id, $task, $due_date, $priority);
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
    $conn->query("DELETE FROM tasks WHERE list_id = $listID AND userID = $userID");
    $conn->query("DELETE FROM task_lists WHERE list_id = $listID AND userID = $userID");
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
$selected_list_id = isset($_POST['list_id']) ? $_POST['list_id'] : null;

// Fetch tasks from selected list
$task_query = $conn->prepare("SELECT * FROM tasks WHERE userID = ? AND list_id = ? ORDER BY due_date ASC, priority DESC");
$task_query->bind_param("ii", $userID, $selected_list_id);
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
        <input type="text" name="list_name" placeholder="New list name" required>
        <button type="submit" name="add_list">+ Add List</button>
    </form>

    <!-- Select a List -->
    <form method="POST" class="list-select">
        <select name="list_id" onchange="this.form.submit()">
            <option value="">Select a list</option>
            <?php while ($list = $lists_result->fetch_assoc()): ?>
                <option value="<?= $list['list_id'] ?>" <?= $selected_list_id == $list['list_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($list['list_name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <?php if ($selected_list_id): ?>
        <!-- Add Task Form -->
        <form method="POST" class="task-form">
            <input type="hidden" name="list_id" value="<?= $selected_list_id ?>">
            <input type="text" name="task" placeholder="Enter a task" required>
            <input type="date" name="due_date">
            <select name="priority">
                <option value="Low">Low</option>
                <option value="Medium" selected>Medium</option>
                <option value="High">High</option>
            </select>
            <button type="submit" name="add_task">+ Add Task</button>
        </form>

        <!-- Task List -->
        <div class="task-list">
            <?php if ($tasks_result->num_rows > 0): ?>
                <ul>
                    <?php while ($task = $tasks_result->fetch_assoc()): ?>
                        <li class="task-item">
                            <span><strong><?= htmlspecialchars($task['task']) ?></strong> — Due: <?= $task['due_date'] ?> | Priority: <?= $task['priority'] ?></span>
                            <a href="?delete_task=<?= $task['taskID'] ?>" class="delete-btn">❌</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No tasks in this list.</p>
            <?php endif; ?>

            <!-- Delete List -->
            <a href="?delete_list=<?= $selected_list_id ?>" class="delete-list-btn">🗑️ Delete This List</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
