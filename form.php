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
                <label for="ltp">L-T-P Structure</label>
                <input type="number" name="lecture" id="lecture" 
                       <?php echo $mode == 'view' ? 'readonly' : 'required'; ?> 
                       value="<?php echo $formData ? htmlspecialchars($formData['L-T-P Structure']['lecture']) : ''; ?>">
                <input type="number" name="tutorial" id="tutorial" 
                       <?php echo $mode == 'view' ? 'readonly' : 'required'; ?> 
                       value="<?php echo $formData ? htmlspecialchars($formData['L-T-P Structure']['tutorial']) : ''; ?>">
                <input type="number" name="practical" id="practical" 
                       <?php echo $mode == 'view' ? 'readonly' : 'required'; ?> 
                       value="<?php echo $formData ? htmlspecialchars($formData['L-T-P Structure']['practical']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="totalCredits">Total Credits</label>
                <input type="text" name="totalCredits" id="totalCredits" disabled 
                       value="<?php echo $formData ? htmlspecialchars($formData['Total Credits']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="status">Course Status</label>
                <select name="status" id="status" required>
                    <option value="BS" <?php echo $formData['Course Status'] == 'BS' ? 'selected' : ''; ?>>BS</option>
                    <option value="GE" <?php echo $formData['Course Status'] == 'GE' ? 'selected' : ''; ?>>GE</option>
                    <option value="HU" <?php echo $formData['Course Status'] == 'HU' ? 'selected' : ''; ?>>HU</option>
                </select>
            </div>

            <div class="form-group">
                <label for="departmentalCore">Departmental Core</label>
                <input type="checkbox" name="departmentalCore" id="departmentalCore"
                       <?php echo $formData && $formData['Departmental Core'] ? 'checked' : ''; ?>>
            </div>

            <div class="form-group">
                <label for="minorElective">Minor Area Elective</label>
                <input type="checkbox" name="minorElective" id="minorElective"
                       <?php echo $formData && $formData['Minor Area Elective'] ? 'checked' : ''; ?>>
            </div>

            <div class="form-group">
                <label for="prerequisites">Pre-requisites</label>
                <input type="text" name="prerequisites" id="prerequisites" 
                       value="<?php echo $formData ? htmlspecialchars($formData['Pre-requisites']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="frequency">Frequency of Offering</label>
                <input type="text" name="frequency" id="frequency" 
                       value="<?php echo $formData ? htmlspecialchars($formData['Frequency of Offering']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="faculty">Visiting Faculty</label>
                <input type="text" name="faculty" id="faculty" 
                       value="<?php echo $formData ? htmlspecialchars($formData['Visiting Faculty']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="resources">Resources</label>
                <textarea name="resources" id="resources" required><?php echo $formData ? htmlspecialchars($formData['Resources']) : ''; ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Save</button>
            </div>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>
