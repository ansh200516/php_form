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

$courseCode = $_GET['code'] ?? '';
$mode = $_GET['mode'] ?? 'create';
$formData = null;

if (($mode == 'update' || $mode == 'view') && $courseCode) {
    $sql = "SELECT * FROM `form` WHERE `Course Code` = '$courseCode'";
    $result = mysqli_query($conn, $sql);
    $formData = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Form - <?php echo ucfirst($mode); ?> Mode</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Course Form - <?php echo ucfirst($mode); ?> Mode</h1>
        <form id="courseForm" action="save_form.php" method="post">
            <input type="hidden" name="mode" value="<?php echo $mode; ?>">
            
            <div class="form-group">
                <label for="au">Academic Unit</label>
                <input type="text" name="au" id="au" 
                       <?php echo $mode == 'view' ? 'readonly' : 'required'; ?> 
                       value="<?php echo $formData ? htmlspecialchars($formData['Academic Unit']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="code">Course Code</label>
                <input type="text" name="code" id="code" 
                       <?php echo $mode == 'view' || $mode == 'update' ? 'readonly' : 'required'; ?> 
                       value="<?php echo htmlspecialchars($courseCode); ?>">
            </div>
            
            <div class="form-group">
                <label for="course">Course Name</label>
                <input type="text" name="course" id="course" 
                       <?php echo $mode == 'view' ? 'readonly' : 'required'; ?> 
                       value="<?php echo $formData ? htmlspecialchars($formData['Course Name']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="resources">Resources Required</label>
                <textarea name="resources" id="resources" 
                          <?php echo $mode == 'view' ? 'readonly' : 'required'; ?> 
                          cols="30" rows="10"><?php echo $formData ? htmlspecialchars($formData['Resources']) : ''; ?></textarea>
            </div>

            <div class="form-actions">
                <?php if ($mode != 'view'): ?>
                    <button type="button" id="saveBtn" class="btn">Save</button>
                    <button type="button" id="submitBtn" class="btn">Submit</button>
                    <button type="button" id="downloadBtn" class="btn" style="display:none;">Download PDF</button>
                <?php else: ?>
                    <a href="index.php" class="btn">Back to List</a>
                    <button type="button" id="downloadBtn" class="btn">Download PDF</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>