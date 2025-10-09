// Central place for all expense API calls

// expenseApi.js
export async function checkFieldAvailability(field, value) {
  if (value.length < 3) return { exists: false, skip: true };

  try {
    const response = await fetch(
      `actions/checkRegfields.php?field=${field}&value=${encodeURIComponent(value)}`
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
