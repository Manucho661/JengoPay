
// show add new schedule
window.closeInspectionMDL = function () {
  document.getElementById("add-inspection").style.display = "none";
};

window.addNewSchedule = function () {
  document.getElementById("add-inspection").style.display = "flex";
};


// custom select container
document.querySelectorAll('.select-option-container').forEach(container => {

         const select = container.querySelector('.custom-select');
        const optionsContainer = container.querySelector('.select-options');
        const options = optionsContainer.querySelectorAll("div");
        select.addEventListener("click", () => {
          const isOpen = optionsContainer.style.display === "block";

              optionsContainer.style.display = isOpen ? "none" : "block";
              select.style.borderRadius = isOpen ? "5px" : "5px 5px 0 0";

              // This line is key: it adds or removes the "open" class
              select.classList.toggle("open", !isOpen);

        });

        options.forEach(option => {
           option.addEventListener("click", () => {
            select.textContent = option.textContent;
            select.setAttribute("data-value", option.getAttribute("data-value"));
            options.forEach(opt => opt.classList.remove("selected"));
            option.classList.add("selected");
            optionsContainer.style.display = "none";
            select.style.borderRadius = "5px";
            select.classList.remove("open");

      });
    });
    document.addEventListener("click", (e) => {
      if (!e.target.closest(".select-option-container")) {
        optionsContainer.style.display = "none";
        select.style.borderRadius = "5px";
        select.classList.remove("open");
      }

    })
});


// New inspection
function submitInspectionForm(event) {
  event.preventDefault(); // Prevent the form from submitting normally

  // Create FormData object from the form
  const formData = new FormData(document.getElementById("form_new_inspection"));
  formData.append("type", "inspections"); // Add the type for tenant

  // Send data via fetch
  fetch("../actions/inspections/add_record.php", {
    method: "POST",
    body: new URLSearchParams(formData)
  })
  .then(res => res.text())
  .then(data => {
    alert(data); // Display success message or error from server
    location.reload(); // Reload the page to reflect changes (optional)
  })
  .catch(err => console.error(err));
}