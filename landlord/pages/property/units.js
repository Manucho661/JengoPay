function handleDelete(event, id, type) {
  event.stopPropagation(); // Prevents event bubbling

  if (confirm("Are you sure?")) {
    fetch('../actions/delete_record.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'id=' + encodeURIComponent(id) + '&type=' + encodeURIComponent(type)
    })
    .then(res => res.text())
    .then(data => {
      alert(data);
      location.reload();
    })
    .catch(err => console.error('Delete error:', err));
  }
}



// data tables
$(document).ready(function () {
  $('#myTableOne').DataTable();
});
$(document).ready(function () {
  $('#myTableThree').DataTable();
});
$(document).ready(function () {
  $('#myTableFour').DataTable();
});


$(document).ready(function() {
$('#myTable').DataTable({
"paging": true,
"searching": true,
"info": true,
"lengthMenu": [5, 10, 25, 50],
"language": {
 "search": "Filter records:",
 "lengthMenu": "Show _MENU_ entries"
}
});
});

    // data tables

  //   $(document).ready(function () {
  //     $('#myTableOne').DataTable();
  // });
  // $(document).ready(function () {
  //     $('#myTableThree').DataTable();
  // });
  // $(document).ready(function () {
  //     $('#myTableFour').DataTable();
  // });


//   $(document).ready(function() {
// $('#myTable').DataTable({
//  "paging": true,
//  "searching": true,
//  "info": true,
//  "lengthMenu": [5, 10, 25, 50],
//  "language": {
//      "search": "Filter records:",
//      "lengthMenu": "Show _MENU_ entries"
//  }
// });
// });

// units popup
// Function to open the units popup
function openunitsPopup() {
  document.getElementById("unitsPopup").style.display = "flex";
}

// Function to close the units popup
function closeunitsPopup() {
  document.getElementById("unitsPopup").style.display = "none";
}

// modal Overlay
// Show the modal
function openModal() {
  document.getElementById("modalOverlay").style.display = "flex";
}

// Close the modal
function closeModal() {
  document.getElementById("modalOverlay").style.display = "none";
}

// Trigger the modal to show when the button is clicked
document.querySelector('.edit-btn').addEventListener('click', openModal);

// slides
const slides = document.querySelector('.slides');
const prevBtn = document.getElementById('prev');
const nextBtn = document.getElementById('next');

let index = 0;
const totalSlides = document.querySelectorAll('.slide').length;

function updateSlide() {
    slides.style.transform = `translateX(-${index * 100}%)`;
}

// Next button
nextBtn.addEventListener('click', () => {
    index = (index + 1) % totalSlides;
    updateSlide();
});

// Previous button
prevBtn.addEventListener('click', () => {
    index = (index - 1 + totalSlides) % totalSlides;
    updateSlide();
});

// Auto-slide every 3 seconds
setInterval(() => {
    index = (index + 1) % totalSlides;
    updateSlide();
}, 3000);


// side wrapper

const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });


// step

      document.addEventListener("DOMContentLoaded", function () {
        const steps = document.querySelectorAll(".form-step");
        const progressSteps = document.querySelectorAll(".step");
        const nextBtns = document.querySelectorAll(".next-btn");
        const prevBtns = document.querySelectorAll(".prev-btn");
        const form = document.getElementById("property-form");

        let currentStep = 0;

        function showStep(stepIndex) {
            // Hide all steps and reset progress
            steps.forEach((step, index) => {
                step.classList.remove("active");
                if (index === stepIndex) {
                    step.classList.add("active");
                }
            });

            // Update progress bar (show tick for completed steps)
            progressSteps.forEach((step, index) => {
                step.classList.remove("active", "completed");
                if (index < stepIndex) {
                    step.classList.add("completed"); // Mark previous steps as completed (✔️)
                }
                if (index === stepIndex) {
                    step.classList.add("active"); // Highlight current step
                }
            });
        }

        function validateStep() {
            const inputs = steps[currentStep].querySelectorAll("input");
            for (let input of inputs) {
                if (input.value.trim() === "") {
                    alert("Please fill in all fields before proceeding.");
                    return false;
                }
            }
            return true;
        }

        nextBtns.forEach((button) => {
            button.addEventListener("click", () => {
                if (validateStep()) {
                    if (currentStep < steps.length - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                }
            });
        });

        prevBtns.forEach((button) => {
            button.addEventListener("click", () => {
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        });

        form.addEventListener("submit", function (event) {
            event.preventDefault();
            alert("Property Registered Successfully!");
        });

        // Show first step on load
        showStep(currentStep);
    });



