<?php
// config.php
define('DB_SERVER', 'localhost'); // e.g., localhost
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'addperson');

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>