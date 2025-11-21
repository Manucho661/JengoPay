import { fetchRequests} from "./api/getRequests.js";
document.addEventListener("DOMContentLoaded", () => {
  fetchRequests();       // Load data into table
});

