<?php
require_once '../../db/connect.php';
$formType = $_POST['form_type'] ?? '';

switch ($formType) {
  case 'addPaymentForm':
    handlePayment($pdo);
    break;

    default:
    echo "Unknown form type.";
    break;
  }


//   addpayment
function handlePayment($pdo){
     try {
    $maintenance_request_id = $_POST['request_id'];
    $amount_paid = $_POST['amountPaid'];
    $payment_method = $_POST['paymentMethod'];
    $cheque_number= $_POST['chequeNumber'];
    $invoice_number=$_POST['invoiceNumber'];
    $payment_notes=$_POST['paymentNotes'];
    $payment_date = $_POST['datePaid'];
    if (empty($maintenance_request_id) || empty($amount_paid)|| empty($payment_date) || empty($amount_paid)) {
      throw new Exception("Missing tenant ID or amount.");
    }

    $stmt = $pdo->prepare("INSERT INTO maintenance_payments (maintenance_request_id, amount_paid, payment_method, cheque_number, invoice_number, payment_notes, payment_date) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([$maintenance_request_id, $amount_paid, $payment_method,$cheque_number,$invoice_number,$payment_date,$payment_notes ]);
    // update payment
    updatePaymentStatus($pdo,$maintenance_request_id);
    echo "✅ Payment recorded.";
  } catch (Exception $e) {
    http_response_code(400); // Bad Request
    echo "❌ Error: " . $e->getMessage();
  }
}

// update payment status
function updatePaymentStatus($pdo, $maintenance_request_id){
  try
  {
  $sql = "UPDATE maintenance_requests SET Payment_status = ? WHERE id = ?";
  $stmt = $pdo->prepare($sql);
  $success = $stmt->execute(['Paid',$maintenance_request_id]);
  if ($success) {
      echo "✅ Payment status updated successfully.";
    } else {
      echo "⚠️ Failed to update payment status.";
    }
  }
  catch(Exception $e){
        echo "❌ Error: " . $e->getMessage();
  }
}
?>