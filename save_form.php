<?php
// Enable full error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Unknown error', 'debug' => []];

try {
    // Database connection details
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "course_form";

    // Attempt database connection with more detailed error checking
    $conn = new mysqli($server, $username, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        $response['message'] = "Connection Failed: " . $conn->connect_error;
        $response['debug']['connect_errno'] = $conn->connect_errno;
        echo json_encode($response);
        exit;
    }

    // Log received POST data
    $response['debug']['received_data'] = $_POST;

    // Validate and sanitize inputs
    $mode = $conn->real_escape_string($_POST['mode'] ?? '');
    $au = $conn->real_escape_string($_POST['au'] ?? '');
    $code = $conn->real_escape_string($_POST['code'] ?? '');
    $course = $conn->real_escape_string($_POST['course'] ?? '');
    $resources = $conn->real_escape_string($_POST['resources'] ?? '');

    // Determine status
    $status = ($mode == 'submit') ? 'submitted' : 'draft';

    // Check if record exists
    $checkSql = "SELECT * FROM `form` WHERE `Course Code` = '$code'";
    $checkResult = $conn->query($checkSql);
    $recordExists = ($checkResult->num_rows > 0);

    $response['debug']['record_exists'] = $recordExists;

    // Prepare SQL query based on existence
    if ($recordExists) {
        // Update existing record
        $sql = "UPDATE `form` SET 
                `Academic Unit` = '$au', 
                `Course Name` = '$course', 
                `Resources` = '$resources',
                `dt` = current_timestamp(),
                `status` = '$status'
                WHERE `Course Code` = '$code'";
    } else {
        // Insert new record
        $sql = "INSERT INTO `form` 
                (`Academic Unit`, `Course Code`, `Course Name`, `Resources`, `dt`, `status`) 
                VALUES 
                ('$au', '$code', '$course', '$resources', current_timestamp(), '$status')";
    }

    // Execute query
    $result = $conn->query($sql);

    // Check if query was successful
    if ($result) {
        $response['success'] = true;
        $response['message'] = $recordExists ? 'Form updated' : 'Form saved';
        $response['debug']['affected_rows'] = $conn->affected_rows;
    } else {
        $response['message'] = "Query Error: " . $conn->error;
        $response['debug']['query_error'] = $conn->error;
    }

    // Close connection
    $conn->close();

} catch (Exception $e) {
    $response['message'] = "Exception: " . $e->getMessage();
    $response['debug']['exception'] = $e->getMessage();
}

// Output detailed response
echo json_encode($response);
exit;
?>