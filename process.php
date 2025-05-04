<?php
require_once 'config.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $age = mysqli_real_escape_string($conn, $_POST["age"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, age, email) VALUES (?, ?, ?)";

    // Create a prepared statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters to the prepared statement
        $stmt->bind_param("sis", $username, $age, $email); // s: string, i: integer

        // Execute the statement
        if ($stmt->execute()) {
            echo "Person added successfully!";
            // You can redirect the user to a success page here if needed
            // header("Location: success.php");
            // exit();
        } else {
            echo "Error adding person: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    // If the script is accessed directly without a POST request
    echo "Invalid request.";
}
?>