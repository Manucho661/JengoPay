{/* <script> */}
    // Adding event listener to the file input for file size and preview
    document.getElementById('fileInput').addEventListener('change', function (event) {
      const file = event.target.files[0];  // Access the first selected file
      if (file) {
        // Calculate and display file size in MB
        const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
        document.getElementById('fileSize').textContent = `File size: ${sizeInMB} MB`;

        // Handle the file based on its type (image, PDF, etc.)
        if (file.type.startsWith('image/')) {
          // If it's an image, show preview
          loadFileToAttach(event);
          document.getElementById('imageOutput').style.display = 'block'; // Show image preview
          document.getElementById('pdfOutput').style.display = 'none'; // Hide PDF preview
        } else if (file.type === 'application/pdf') {
          // If it's a PDF, show preview
          loadPdfToAttach(event);
          document.getElementById('pdfOutput').style.display = 'block'; // Show PDF preview
          document.getElementById('imageOutput').style.display = 'none'; // Hide image preview
        } else {
          // For other file types, hide both previews
          document.getElementById('imageOutput').style.display = 'none';
          document.getElementById('pdfOutput').style.display = 'none';
        }
      } else {
        // Clear preview and size text if no file is selected
        document.getElementById('fileSize').textContent = '';
        document.getElementById('imageOutput').style.display = 'none';
        document.getElementById('pdfOutput').style.display = 'none';
      }
    });

    // Function to handle the image preview
    var loadFileToAttach = function(event) {
      var output = document.getElementById('imageOutput'); // Get the <img> element
      output.src = URL.createObjectURL(event.target.files[0]); // Set the image source

      // Clean up the memory after the image is loaded
      output.onload = function() {
        URL.revokeObjectURL(output.src); // Free memory
      };
    };

    // Function to handle the PDF preview
    var loadPdfToAttach = function(event) {
      var output = document.getElementById('pdfOutput'); // Get the <embed> element
      output.src = URL.createObjectURL(event.target.files[0]); // Set the PDF source
    };
