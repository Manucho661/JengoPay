import { otherRequest } from "./uiControl.js";

// import { fetchRequests} from "./api/getRequests.js";
document.addEventListener("DOMContentLoaded", () => {

  const modalEl = document.getElementById('requestModal');
  const modal = new bootstrap.Modal(modalEl);

  // open modal when button clicked
  document.querySelector('.seTAvailable').addEventListener('click', () => {
    modal.show();
  });

  // handle create request
   document.getElementById('otherRequestBtn').addEventListener('click', otherRequest);



});

