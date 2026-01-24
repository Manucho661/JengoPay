// edit payments
export async function edit_submittedPayments(form) {
  console.log("🔄 Submitting payment update...");

  const formData = new FormData(form);

  try {
    const response = await fetch("./actions/editExpensePayment.php", {
      method: "POST",
      body: formData
    });

    const result = await response.json();
    console.log("📥 Server response:", result);

    if (result.success) {
      // Optional visual feedback
      form.style.backgroundColor = "#e6ffe6"; // light green
      setTimeout(() => form.style.backgroundColor = "", 1000); // revert after 1s
    } else {
      console.error("❌ Update failed:", result.message || "Unknown error");
    }
  } catch (err) {
    console.error("⚠️ Fetch error:", err);
  }
}