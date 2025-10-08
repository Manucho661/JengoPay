import html2pdf from "https://esm.sh/html2pdf.js";

export function downloadExpPDF() {
  const expenseCard = document.querySelector("#expenseModal .modal-body");

  if (!expenseCard) {
    console.error("❌ No expense card found in modal.");
    return;
  }
  expenseCard.style.backgroundColor = "black";
  // Clone card
  const clonedCard = expenseCard.cloneNode(true);

  // ✅ Add custom padding only for PDF
  clonedCard.style.padding = "20px"; // you can adjust this

  // Wrapper (optional styling)
  const wrapper = document.createElement("div");
  wrapper.style.background = "white"; // ensures clean background
  wrapper.style.padding = "40px";     // outer padding around the card
  wrapper.appendChild(clonedCard);

  const options = {
    margin: 0,
    filename: "expense-card.pdf",
    image: { type: "jpeg", quality: 0.98 },
    html2canvas: { scale: 2, useCORS: true, scrollY: 0 },
    jsPDF: { unit: "mm", format: [210, 297], orientation: "portrait" }
  };

  html2pdf()
    .set(options)
    .from(wrapper)
    .toPdf()
    .get("pdf")
    .then(function (pdf) {
      const pageCount = pdf.internal.getNumberOfPages();
      if (pageCount > 1) {
        for (let i = pageCount; i > 1; i--) {
          pdf.deletePage(i);
        }
      }
    })
    .save();
}
