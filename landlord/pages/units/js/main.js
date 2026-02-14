document.getElementById("buttonContainer").addEventListener("click", function (e) {
  if (e.target.tagName === "BUTTON") {
    const id = e.target.dataset.attributeId;
    myFunction(id);
  }
});

function myFunction(id) {
  console.log("Button clicked:", id);
}