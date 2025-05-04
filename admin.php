<?php
require_once 'config.php';

$message = '';
$editUser = null;

// Handle POST requests for update and delete
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'update') {
            // Update user
            $user_id = intval($_POST['user_id']);
            $username = trim($_POST['username']);
            $age = intval($_POST['age']);
            $email = trim($_POST['email']);

            if ($username === '') {
                $message = "Username cannot be empty.";
            } else {
                $sql = "UPDATE users SET username = ?, age = ?, email = ? WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("sisi", $username, $age, $email, $user_id);
                    if ($stmt->execute()) {
                        $message = "User updated successfully.";
                    } else {
                        $message = "Error updating user: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $message = "Error preparing statement: " . $conn->error;
                }
            }
        } elseif ($action === 'delete') {
            // Delete user
            $user_id = intval($_POST['user_id']);
            $sql = "DELETE FROM users WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $user_id);
                if ($stmt->execute()) {
                    $message = "User deleted successfully.";
                } else {
                    $message = "Error deleting user: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = "Error preparing statement: " . $conn->error;
            }
        }
    }
}

// Handle GET request to load user data for editing
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['edit'])) {
    $user_id = intval($_GET['edit']);
    $sql = "SELECT user_id, username, age, email FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $editUser = $result->fetch_assoc();
        }
        $stmt->close();
    }
}

// Fetch all users for display
$sql = "SELECT user_id, username, age, email FROM users ORDER BY user_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin - Manage Users</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        .message {
            text-align: center;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2e7d32;
        }
        .error {
            color: #d93025;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 0 auto 30px auto;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            border-bottom: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        form.inline {
            display: inline;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 6px 12px;
            margin: 0 2px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn.delete {
            background-color: #d93025;
        }
        .btn.delete:hover {
            background-color: #b1271b;
        }
        .edit-form {
            width: 400px;
            margin: 0 auto 30px auto;
            background-color: white;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .edit-form h2 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        .edit-form label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
        }
        .edit-form input[type="text"],
        .edit-form input[type="number"],
        .edit-form input[type="email"] {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            margin-bottom: 16px;
            transition: border-color 0.3s ease;
        }
        .edit-form input[type="text"]:focus,
        .edit-form input[type="number"]:focus,
        .edit-form input[type="email"]:focus {
            border-color: #4CAF50;
            outline: none;
        }
        .edit-form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 0;
            width: 100%;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .edit-form input[type="submit"]:hover {
            background-color: #45a049;
        }
        .cancel-link {
            display: block;
            text-align: center;
            margin-top: 12px;
            color: #d93025;
            text-decoration: none;
            font-weight: 600;
        }
        .cancel-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Admin - Manage Users</h1>

    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : ''; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if ($editUser): ?>
        <div class="edit-form">
            <h2>Edit User</h2>
            <form action="admin.php" method="post" novalidate>
                <input type="hidden" name="action" value="update" />
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($editUser['user_id']); ?>" />
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($editUser['username']); ?>" required />
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" min="0" max="120" value="<?php echo htmlspecialchars($editUser['age']); ?>" />
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($editUser['email']); ?>" />
                <input type="submit" value="Update User" />
            </form>
            <a href="admin.php" class="cancel-link">Cancel</a>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Age</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['age']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <form action="admin.php" method="get" class="inline" style="display:inline;">
                                <input type="hidden" name="edit" value="<?php echo htmlspecialchars($row['user_id']); ?>" />
                                <input type="submit" value="Edit" class="btn" />
                            </form>
                            <form action="admin.php" method="post" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display:inline;">
                                <input type="hidden" name="action" value="delete" />
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['user_id']); ?>" />
                                <input type="submit" value="Delete" class="btn delete" />
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center;">No users found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php
$conn->close();
?>
