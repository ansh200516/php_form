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
    $status = $conn->real_escape_string($_POST['status'] ?? '');
    $departmentalCore = isset($_POST['departmentalCore']) ? 1 : 0;
    $minorElective = isset($_POST['minorElective']) ? 1 : 0;
    $prerequisites = $conn->real_escape_string($_POST['prerequisites'] ?? '');
    $frequency = $conn->real_escape_string($_POST['frequency'] ?? '');
    $faculty = $conn->real_escape_string($_POST['faculty'] ?? '');

    // Check if record exists
    $checkSql = "SELECT * FROM `form` WHERE `Course Code` = '$code'";
    $checkResult = $conn->query($checkSql);
    $recordExists = ($checkResult->num_rows > 0);

    // Prepare SQL query based on existence
    if ($recordExists) {
        // Update existing record
        $sql = "UPDATE `form` SET 
                `Academic Unit` = '$au', 
                `Course Name` = '$course', 
                `Resources` = '$resources', 
                `Course Status` = '$status',
                `Departmental Core` = '$departmentalCore',
                `Minor Area Elective` = '$minorElective',
                `Pre-requisites` = '$prerequisites',
                `Frequency of Offering` = '$frequency',
                `Visiting Faculty` = '$faculty',
                `dt` = current_timestamp()
                WHERE `Course Code` = '$code'";
    } else {
        // Insert new record
        $sql = "INSERT INTO `form` 
                (`Academic Unit`, `Course Code`, `Course Name`, `Resources`, `Course Status`, 
                `Departmental Core`, `Minor Area Elective`, `Pre-requisites`, `Frequency of Offering`, 
                `Visiting Faculty`, `dt`) 
                VALUES 
                ('$au', '$code', '$course', '$resources', '$status', 
                '$departmentalCore', '$minorElective', '$prerequisites', '$frequency', 
                '$faculty', current_timestamp())";
    }

    // Execute query
    $result = $conn->query($sql);

    // Check if query was successful
    if ($result) {
        $response['success'] = true;
        $response['message'] = $recordExists ? 'Form updated' : 'Form saved';
    } else {
        $response['message'] = "Query Error: " . $conn->error;
    }

    // Close connection
    $conn->close();

} catch (Exception $e) {
    $response['message'] = "Exception: " . $e->getMessage();
}

// Output detailed response
echo json_encode($response);
exit;
?>
