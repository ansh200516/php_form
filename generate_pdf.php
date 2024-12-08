<?php
// Include the main TCPDF library
require_once('vendor/autoload.php');

// Database connection
$server = "localhost";
$username = "root";
$password = "";
$database = "course_form";

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get course code from GET parameter
$courseCode = $_GET['code'] ?? '';

// Fetch form data
$sql = "SELECT * FROM `form` WHERE `Course Code` = '$courseCode'";
$result = mysqli_query($conn, $sql);
$formData = mysqli_fetch_assoc($result);

if (!$formData) {
    die("No form data found for course code: " . $courseCode);
}

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Course Floatation System');
$pdf->SetTitle('Course Form - ' . $formData['Course Code']);
$pdf->SetSubject('Course Floatation Details');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Title
$pdf->Cell(0, 10, 'Course Floatation Form', 0, 1, 'C');
$pdf->Ln(10);

// Form Details
$details = [
    'Academic Unit' => $formData['Academic Unit'],
    'Course Code' => $formData['Course Code'],
    'Course Name' => $formData['Course Name'],
    'Resources' => $formData['Resources'],
    'Submission Date' => $formData['dt'],
    'Status' => $formData['status']
];

// Print form details
foreach ($details as $label => $value) {
    $pdf->Cell(50, 10, $label . ':', 0, 0, 'L');
    $pdf->Cell(0, 10, $value, 0, 1, 'L');
}

// Output PDF
$pdf->Output($formData['Course Code'] . '_course_form.pdf', 'D');

// Close database connection
mysqli_close($conn);
exit;