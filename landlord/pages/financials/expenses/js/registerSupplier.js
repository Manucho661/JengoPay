export async function registerSupplier(form) {
  if (!form) return;

  const submitBtn = form.querySelector("[name='register_supplier']");
  const fields = ["supplierName", "supplierKra"];

  const setBtnState = (loading, text = "Save") => {
    if (!submitBtn) return;
    submitBtn.disabled = loading;
    submitBtn.textContent = loading ? text : "Save";
  };

  const setMsg = (field, text, color = "red") => {
    const box = document.getElementById(field + "Msg");
    if (!box) return;
    box.textContent = text;
    box.style.color = color;
  };

  const clearMsgs = () => fields.forEach((f) => setMsg(f, ""));

  // ✅ Let browser show built-in validation messages
  if (!form.checkValidity()) {
    form.reportValidity();
    return;
  }

  clearMsgs();
  setBtnState(true, "Validating...");

  let hasError = false;

  // ✅ Uniqueness checks
  for (const field of fields) {
    const input = form.querySelector(`#${field}`);
    const value = input ? input.value.trim() : "";

    try {
      const res = await fetch(
        `actions/checkRegfields.php?field=${encodeURIComponent(field)}&value=${encodeURIComponent(value)}&t=${Date.now()}`
      );

      if (!res.ok) {
        setMsg(field, "Unable to validate right now.", "orange");
        hasError = true;
        continue;
      }

      const data = await res.json();

      if (data.error) {
        setMsg(field, "Unable to validate right now.", "orange");
        hasError = true;
      } else if (data.exists) {
        setMsg(field, data.Message || "Already exists.");
        hasError = true;
      }
    } catch (err) {
      console.error(err);
      setMsg(field, "Unable to validate right now.", "orange");
      hasError = true;
    }
  }

  if (hasError) {
    setBtnState(false);
    return;
  }

  // ✅ Submit
  try {
    setBtnState(true, "Saving...");

    const formData = new FormData(form);

    const response = await fetch("actions/registerSupplier.php", {
      method: "POST",
      body: formData,
      headers: { "X-Requested-With": "XMLHttpRequest" },
    });

    const result = await response.json();

    // failure
    if (!response.ok || !result.success) {
      if (result.redirect) {
        window.location.href = result.redirect;
        return;
      }
      throw new Error(result.error || "Failed to register supplier");
    }

    // success redirect (toast comes from session on next page)
    if (result.redirect) {
      window.location.href = result.redirect;
      return;
    }

    // fallback: stay
    form.reset();
    setBtnState(false);
  } catch (err) {
    console.error("Supplier registration failed:", err.message);
    setBtnState(false);
  }
}
