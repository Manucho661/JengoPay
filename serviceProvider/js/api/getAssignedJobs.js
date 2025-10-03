// Function to fetch requests and call the rendering function
export async function getAssignedRequests() {
    try {
        const response = await fetch('./actions/getAssignedJobs.php');

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json(); // parse JSON body
        console.log(data); // logs the parsed JSON from PHP

        // Get the list group element
        const listGroup = document.getElementById('list-group');
        listGroup.innerHTML = '';  // Clear any existing content

        // Check if we have data
        if (data.success && data.data && data.data.length > 0) {

            let htmlContent = ''; // Declare the variable outside the loop

            data.data.forEach(job => {
                // Append job details to the HTML string
                htmlContent += `
                   <div class="list-group-item p-3 rounded shadow-sm">
                    <!-- Job header -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div style="font-weight: bold; color: #00192D;">
                        ${job.category}
                        </div>
                        <span class="badge bg-success">Active</span>
                    </div>

                    <!-- Job location -->
                    <div class="text-muted mb-2">
                        ${job.residence} · <span>${job.unit}</span>
                    </div>

                    <!-- Job description -->
                    <div class="mb-3">
                        ${job.description}
                    </div>

                    <!-- Job details row -->
                    <div class="row text-muted small mb-3">
                        <div class="col-md-6">
                        <strong>Assigned:</strong> ${job.request_date}
                        </div>
                        <div class="col-md-6 text-md-end">
                        <strong>Due:</strong> ${job.request_date}
                        </div>
                        <div class="col-md-6">
                        <strong>Duration:</strong> 4 hrs
                        </div>
                        <div class="col-md-6 text-md-end">
                        <strong>Amount:</strong> <span class="text-dark fw-bold">KES 5000</span>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="text-end">
                        <button class="btn btn-outline-success btn-sm">Accept</button>
                        <button class="btn btn-outline-danger btn-sm">Decline</button>
                    </div>
                    </div>
                `;
            });

            // Insert the dynamically generated HTML into the list group
            listGroup.innerHTML = htmlContent;
        } else {
            // If no jobs, display a default message
            listGroup.innerHTML = '<li class="list-group-item text-danger">No jobs assigned to you.</li>';
        }

        return data; // Return the data so other code can use it (if needed)
    } catch (err) {
        console.error("Error fetching requests:", err);
        // If there's an error, pass an error message to fillRequests
        return { success: false, error: err.message };
    }
}
