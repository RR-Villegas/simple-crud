<?php
require_once 'config.php';

// Pagination settings
$limit = 10; // Number of entries per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search filter
$search = isset($_GET['search']) ? $conn->real_escape_string(trim($_GET['search'])) : '';

// Count total records for pagination
$countSql = "SELECT COUNT(*) as total FROM users";
if ($search !== '') {
    $countSql .= " WHERE username LIKE '%$search%' OR email LIKE '%$search%'";
}
$countResult = $conn->query($countSql);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch data with search and pagination
$sql = "SELECT user_id, username, age, email FROM users";
if ($search !== '') {
    $sql .= " WHERE username LIKE '%$search%' OR email LIKE '%$search%'";
}
$sql .= " ORDER BY user_id DESC LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Users List</title>
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
        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-container input[type="text"] {
            width: 300px;
            padding: 8px 12px;
            border: 1.5px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        .search-container input[type="text"]:focus {
            border-color: #4CAF50;
            outline: none;
        }
        table {
            border-collapse: collapse;
            width: 80%;
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
        .pagination {
            text-align: center;
            margin-bottom: 40px;
        }
        .pagination a {
            color: #4CAF50;
            padding: 8px 14px;
            margin: 0 4px;
            text-decoration: none;
            border: 1.5px solid #4CAF50;
            border-radius: 4px;
            font-weight: 600;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .pagination a.active,
        .pagination a:hover {
            background-color: #4CAF50;
            color: white;
        }
        .no-results {
            text-align: center;
            color: #d93025;
            font-weight: 600;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <h1>Users List</h1>
    <div class="search-container">
        <form method="get" action="fetch.php" id="searchForm">
            <input type="text" name="search" placeholder="Search by username or email" value="<?php echo htmlspecialchars($search); ?>" />
            <input type="submit" value="Search" />
        </form>
    </div>
    <?php if ($result && $result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Age</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['age']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=1<?php echo $search !== '' ? '&search=' . urlencode($search) : ''; ?>">&laquo; First</a>
            <a href="?page=<?php echo $page - 1; ?><?php echo $search !== '' ? '&search=' . urlencode($search) : ''; ?>">< Prev</a>
        <?php endif; ?>
        <?php
        // Display page links around current page
        $range = 2;
        for ($i = max(1, $page - $range); $i <= min($totalPages, $page + $range); $i++): ?>
            <a href="?page=<?php echo $i; ?><?php echo $search !== '' ? '&search=' . urlencode($search) : ''; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?><?php echo $search !== '' ? '&search=' . urlencode($search) : ''; ?>">Next ></a>
            <a href="?page=<?php echo $totalPages; ?><?php echo $search !== '' ? '&search=' . urlencode($search) : ''; ?>">Last &raquo;</a>
        <?php endif; ?>
    </div>
    <?php else: ?>
        <div class="no-results">No users found<?php echo $search !== '' ? ' matching your search' : ''; ?>.</div>
    <?php endif; ?>
</body>
</html>
<?php
$conn->close();
?>
