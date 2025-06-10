<?php
require_once __DIR__ . '/../../../vendor/autoload.php'; // adjust path if needed

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml('<h1>Sample PDF Report</h1>');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("report.pdf", ["Attachment" => true]);
exit;
