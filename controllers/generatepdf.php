<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header('Location: /'); // Redirect to login if not authenticated
    exit();
}

// Fetch the student's result from session
if (!isset($_SESSION['report_result']) || empty($_SESSION['report_result'])) {
    echo 'No result data found to generate the PDF.';
    exit();
}

// Dompdf setup
$options = new Options();
$options->set('isRemoteEnabled', true); // Enable remote content like images
$dompdf = new Dompdf($options);

// Capture the HTML for the report
ob_start();
//include 'printresult.view.php'; // Include the HTML view
include 'views/students/test.php';
$html = ob_get_clean();

// Load HTML into Dompdf
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the PDF
$dompdf->render();

// Output the PDF (download or view in browser)
$dompdf->stream('student_result.pdf', ['Attachment' => 0]); // Change Attachment to 1 for download
?>
