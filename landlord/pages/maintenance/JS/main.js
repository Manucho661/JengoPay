import { otherRequest } from "./uiControl.js";
import { submitRequest } from "./api/submitRequest.js";

import { fetchRequests} from "./api/getRequests.js";
document.addEventListener("DOMContentLoaded", () => {

  // Load Requests
  let page = 1;

  fetchRequests(page);

  const modalEl = document.getElementById('requestModal');
  const modal = new bootstrap.Modal(modalEl);

  // open modal when button clicked
  document.querySelector('.seTAvailable').addEventListener('click', () => {
    modal.show();
  });

  // handle create request
  document.getElementById('otherRequestBtn').addEventListener('click', otherRequest);

  document.getElementById('submitBtn').addEventListener('click', (e) => {
    submitRequest(e, modal);  // pass modal instance
  });



});

