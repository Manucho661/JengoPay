<?php
require_once('libs/fpdf.php'); // adjust path as needed
include '../db/connect.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);

// Table header
$pdf->Cell(40,10,'Tenant Name',1);
$pdf->Cell(30,10,'Paid Amount',1);
$pdf->Cell(30,10,'Penalty',1);
$pdf->Cell(30,10,'Days Late',1);
$pdf->Cell(30,10,'Arrears',1);
$pdf->Cell(30,10,'Overpayment',1);
$pdf->Ln();

$stmt = $pdo->query("SELECT * FROM tenant_rent_summary");

$pdf->SetFont('Arial','',10);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(40,10,substr($row['tenant_name'], 0, 18),1);
    $pdf->Cell(30,10,$row['amount_paid'],1);
    $pdf->Cell(30,10,$row['penalty'],1);
    $pdf->Cell(30,10,$row['penalty_days'],1);
    $pdf->Cell(30,10,$row['arrears'],1);
    $pdf->Cell(30,10,$row['overpayment'],1);
    $pdf->Ln();
}

$pdf->Output('D', 'tenant_rent_summary.pdf');
exit;
