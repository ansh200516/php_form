<?php
session_start();
$server = "localhost";
$username = "root";
$password = "";
$database = "course_form";

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch existing course codes
$sql = "SELECT `Course Code` FROM `form`";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Floatation System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- <img class = "bg" src="bg.jpg" alt="iitd"> -->
     <img class = "logo" src="iitd_red.png" alt="_">
     <img class = "bg" src="iitd.jpg" alt="_">
    <div class="container">
        <h1>Course Floatation Form</h1>
        
        <button id="createNewTemplate" class="btn">Create New Template</button>

        <div class="existing-forms">
            <h2>Existing Forms</h2>
            <table id="formsTable">
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['Course Code']; ?></td>
                            <td>
                                <button class="view-btn" data-code="<?php echo $row['Course Code']; ?>">View/Update</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- View/Update Modal -->
    <div id="formModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="modal-actions">
                <button class = "btn" id="viewFormBtn">View</button>
                <button class = "btn" id="updateFormBtn">Update</button>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>