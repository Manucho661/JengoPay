// make payment
function makePayment() {
  const maintenanceRequestModalEl = document.getElementById('maintenanceRequestModal');
  const maintenanceRequestModalInstance = bootstrap.Modal.getInstance(maintenanceRequestModalEl);
  if (maintenanceRequestModalInstance) {
    maintenanceRequestModalInstance.hide();
  }

  // Open the "Pay Provider"
  const payProviderModalEl = document.getElementById('payProviderModal');
  let payProviderModalInstance = bootstrap.Modal.getInstance(payProviderModalEl);

  if (!payProviderModalInstance) {
    payProviderModalInstance = new bootstrap.Modal(payProviderModalEl);
  }
  payProviderModalInstance.show();

 function handleOpenRecordPaymentModal() {
    const payProviderInstance = bootstrap.Modal.getInstance(payProviderModalEl);
    payProviderInstance.hide();

    const recordPaymentModalEl = document.getElementById('recordPaymentModal');
    let recordPaymentModalInstance = bootstrap.Modal.getInstance(recordPaymentModalEl);
    recordPaymentModalInstance = new bootstrap.Modal(recordPaymentModalEl);
    recordPaymentModalInstance.show();
    nextStepBtn
}

// in system payment
function inSystemPayment(){
  const step1Div = document.getElementById('step-1');
  step1Div.style.display = 'none';

  const step2Div = document.getElementById('step-2');
  step2Div.style.display = 'block';
}
  const nextStepBtn = document.getElementById('nextStepBtn');
  nextStepBtn.addEventListener('click', inSystemPayment);
  const openRecordPaymentModalBtn = document.getElementById('openRecordPaymentModalBtn');
  openRecordPaymentModalBtn.removeEventListener('click', handleOpenRecordPaymentModal);
  openRecordPaymentModalBtn.addEventListener('click', handleOpenRecordPaymentModal);

 // Mpesa/bank payment
  const paymentMethod = document.getElementById('paymentMethod'); // ✅ The <select>
  const mpesaPhoneSection = document.getElementById('mpesaPhoneSection');
  const bankSection= document.getElementById('bankTransferSection');
  paymentMethod.addEventListener('change', function () {
    if (this.value === 'mpesa') {
      mpesaPhoneSection.style.display = 'block';
    } else if(this.value === 'bank'){
      mpesaPhoneSection.style.display = 'none';
      bankSection.style.display = 'block';
    }
    else{
      mpesaPhoneSection.style.display = 'none';
      bankSection.style.display = 'block';
    }
  });
}