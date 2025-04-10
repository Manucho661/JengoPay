// Function to handle the file input and display the selected file
var loadFileToAttach = function(event) {
  var output = document.getElementById('fileOutput');
  var file = event.target.files[0];

  if (file) {
    // Create a temporary URL for the file and set it as the source for the image
    output.src = URL.createObjectURL(file);

    // Show the file size in MB
    var sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
    document.getElementById('fileSize').textContent = `File size: ${sizeInMB} MB`;

    // Free memory after the file is loaded
    output.onload = function() {
      URL.revokeObjectURL(output.src);
    };
  }
};

// Submit the form or handle form submission here
var submitForm = function() {
  // This is just a placeholder for whatever you want to do with the form submission.
  console.log("Form submitted!");
  // You can add actual form submission logic here if needed
};