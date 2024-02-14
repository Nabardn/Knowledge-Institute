<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $password = $_POST["password"];

    // Define an array of department tables to check
    $departments = [
        'administration_department',
        'computer_department',
        'oil_department'
        // Add more departments as needed
    ];

    // Initialize a flag to check if login is successful
    $login_successful = false;

    // Variable to store the redirect location
    $redirect_location = "login-form.html";

    // Check credentials against each department's table
    foreach ($departments as $department) {
        $sql = "SELECT * FROM $department WHERE student_id = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $student_id, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Login successful for this department
            $_SESSION['student_id'] = $student_id;
            $login_successful = true;
            $redirect_location = "view_grades.php";
            break; // Exit the loop once login is successful
        }
    }

    $stmt->close();

    // Redirect after the loop
    header("Location: $redirect_location");

    if (!$login_successful) {
        // Login failed for all departments
        echo "Invalid credentials";
    }
}

$conn->close();
?>
