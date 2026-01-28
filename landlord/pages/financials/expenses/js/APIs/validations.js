

// check payAmount
export async function checkPaymentAPI(methodaccId, amount) {
  try {
    const response = await fetch(`actions/checkAccountBalance.php?account_id=${methodaccId}&amount=${amount}`);
    return await response.json();
  } catch (err) {
    return { error: err.message };
  }
}
