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