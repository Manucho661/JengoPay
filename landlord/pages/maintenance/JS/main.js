import { otherRequest} from "./uiControl.js";
import { submitRequest } from "./api/submitRequest.js";

import { fetchRequests} from "./api/getRequests.js";
document.addEventListener("DOMContentLoaded", () => {
  fetchRequests();       // Load data into table


  // handle create request
  document.getElementById('otherRequestBtn').addEventListener('click', otherRequest);
   
  document.getElementById('submitBtn').addEventListener('click', submitRequest);
});

