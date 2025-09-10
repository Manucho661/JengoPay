// Central place for all expense API calls
export async function createExpense(formElement) {
  const formData = new FormData(formElement);
  const response = await fetch("./actions/createExpense.php", {
    method: "POST",
    body: formData,
  });
  return response.text();
}

export async function payExpense(formElement) {
  const formData = new FormData(formElement);
  const response = await fetch("actions/payExpense.php", {
    method: "POST",
    body: formData,
  });
  return response.text();
}

export async function registerSupplier(formElement){
  const formData = new FormData(formElement);
  const response = await fetch("actions/registerSupplier.php",{
    method: "POST",
    body: formData,
  });
  return response.text();
}

export async function editSupplier(formElement){
  const formData = new FormData(formElement);
  const response = await fetch("actions/editSupplier.php",{
    method: "POST",
    body: formData,
  });
  return response.text();
}

// expenseApi.js
export async function checkFieldAvailability(field, value) {
  if (value.length < 3) return { exists: false, skip: true };

  try {
    const response = await fetch(
      `actions/check_Regfields.php?field=${field}&value=${encodeURIComponent(value)}`
    );

    return await response.json(); 

  } catch (error) {
    return { error: "Network error: " + error.message };
  }
}

// check payAmount
export async function checkPaymentAPI(methodaccId, amount) {
  try {
    const response = await fetch(`actions/checkAccountBalance.php?account_id=${methodaccId}&amount=${amount}`);
    return await response.json();
  } catch (err) {
    return { error: err.message };
  }
}
