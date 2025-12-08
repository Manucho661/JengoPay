// Script for dataTables.

$(document).ready(function () {
  $('#Texts').DataTable({
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


// SEND MESSAGE

function sendMessage() {
  const inputBox = document.getElementById("inputBox");
  const messages = document.getElementById("messages");
  const text = inputBox.innerText.trim();
  if (text !== "") {
    const messageDiv = document.createElement("div");
    messageDiv.classList.add("message", "outgoing");
    messageDiv.textContent = text;
    messages.appendChild(messageDiv);
    inputBox.innerText = "";
    messages.scrollTop = messages.scrollHeight;
  }
}



// SHOWING THE CHATBOT.

const filter_section = document.getElementById('filter-section');
const go_back = document.getElementById('go-back');

document.querySelectorAll('.view').forEach(item => {
  item.addEventListener('click', function () {
    const all_messages_summary = document.getElementById('all-messages-summary');
    const individual_message_summary = document.getElementById('individual-message-summmary');

    const row = this.closest('tr'); // This refers to the clicked button
    const cells = row.querySelectorAll('td');

    const secondLast = cells[cells.length - 2];
    const senderDiv = secondLast.querySelector('.sender');
    const sender = senderDiv ? senderDiv.textContent.split(' ')[0] : 'Unknown Sender'; // Safe fallback
    const thirdLast = cells[cells.length - 3].textContent;
    console.log("Third Last:", thirdLast);
    // console.log("Second Last:", secondLast);

    // Find the `.individual-name.body` and update it (name of the tenant)
    let targetName = document.querySelector('.individual-name.body');
    if (targetName) {
      targetName.textContent = sender;
    }

    // Profile Initials
    let targetName3 = document.getElementById('profile-initials');
    const words = senderDiv.textContent.trim().split(' ');
    const firstInitial = words[0] ? words[0][0] : '';
    const secondInitial = words[1] ? words[1][0] : '';
    const full_initials = firstInitial + secondInitial || 'Unknown Sender';
    if (targetName3) {
      targetName3.textContent = full_initials;
    }

    let targetName2 = document.querySelector('.individual-topic.body');
    if (targetName2) {
      targetName2.textContent = thirdLast;
    }

    all_messages_summary.style.display = "none";
    filter_section.style.display = "none";
    individual_message_summary.style.display = "flex";
    go_back.style.display = "flex";


  });
});

// Back to all messages summary.
function myBack() {

  const individual_message_summary = document.getElementById('individual-message-summmary').style.display = "none";
  const all_messages_summary = document.getElementById('all-messages-summary').style.display = "flex";
  const fliter_section = document.getElementById('filter-section').style.display = "flex";
  const go_back = document.getElementById('go-back').style.display = "none";
}


// Hover and active effect

// Select all message elements
document.querySelectorAll('.individual-topic-profiles').forEach(item => {
  item.addEventListener('click', () => {
    // Remove 'active' class from all and add to clicked one
    document.querySelectorAll('.individual-topic-profiles').forEach(el => el.classList.remove('active'));
    item.classList.add('active');

    // Get the clicked individual's name
    let clickedName = item.querySelector('.individual-topic').textContent;

    // Random residence names


    // Find the `.individual-topic.body` and update it
    let targetName = document.querySelector('.individual-topic.body');
    if (targetName) {
      targetName.textContent = clickedName;
    }

    // Find the `.residence` element and update it with a random residence

  });
});



// EFFECT ON SMALLER SCREENS

// const recentChats = document.getElementById("RecentChats");
const messageProfiles = document.getElementById("message-profiles");
const messageBody = document.getElementById("messageBody");
const individualProfiles = document.querySelectorAll(".individual-topic-profiles");
const profile_initials_initials_topic = document.getElementById("profile-initials-initials-topic");
const initial_topic_separator = document.getElementById("initial-topic-separator");


// Function to handle resizing
function updateLayout() {
  if (window.innerWidth > 768) {
    messageProfiles.style.display = "block";

    messageBody.style.display = "block";
    profile_initials_initials_topic.style.display = "none";
    initial_topic_separator.style.display = "none";
  } else {
    messageProfiles.style.display = "none";

    messageBody.style.display = "block";
    profile_initials_initials_topic.style.display = "flex";
    initial_topic_separator.style.display = "flex";
  }
}


// Initial check when the page loads
updateLayout();

// Listen for window resize events
window.addEventListener("resize", updateLayout);

// Add click event to recentChats (only for small screens)
recentChats.addEventListener("click", showMessageProfiles);



// APP-WRAPPER

// Major Variables.
const all_messages_summary = document.getElementById('all-messages-summary');
const individual_message_summary = document.getElementById('individual-message-summmary');


// const   next_text = document.getElementById('respond_btn');
const respond_window = document.getElementById('respond');
const close_text_overlay = document.getElementById("closeTextWindow");

next_text.addEventListener('click', () => {

  respond_window.style.display = "flex";
  document.querySelector('.app-wrapper').style.opacity = '0.3'; // Reduce opacity of main content
  const now = new Date();
  const formattedTime = now.toLocaleString(); // Format the date and time
  timestamp.textContent = `Sent on: ${formattedTime}`;


});

close_text_overlay.addEventListener('click', () => {

  respond_window.style.display = "none";
  document.querySelector('.app-wrapper').style.opacity = '1';
});



//  IMAGE UPLOAD
document.getElementById('imageUpload').addEventListener('change', function (event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      const preview = document.getElementById('imagePreview');
      preview.src = e.target.result;
      preview.style.display = 'block';
    }
    reader.readAsDataURL(file);
  }
});


// Function to open the complaint popup
function opennewtextPopup() {
  document.getElementById("newtextPopup").style.display = "flex";
}

// Function to close the complaint popup
function closenewtextPopup() {
  document.getElementById("newtextPopup").style.display = "none";
}

function toggleShrink() {
  let recipientBox = document.getElementById("recipient");
  let first_field_group = document.getElementById("field-group-first");
  let field_group_third = document.getElementById("field-group-third");
  let field_group_second = document.getElementById("field-group-second");

  if (recipientBox.value === "all") {
    first_field_group.classList.remove("shrink"); // shrink if the option is not all
    field_group_second.style.display = "none";
    field_group_third.style.display = "none";

  } else {
    first_field_group.classList.add("shrink"); // Do not shrink is the option is all
    field_group_second.style.display = "block";
  }

}


function toggleShrink1() {
  let recipientBox_units = document.getElementById("recipient-units");

  let field_group_second = document.getElementById("field-group-second");
  let field_group_third = document.getElementById("field-group-third");

  if (recipientBox_units.value === "all") {
    field_group_second.classList.remove("shrink"); // shrink if the option is not all
    field_group_third.style.display = "none";

  } else {
    field_group_second.classList.add("shrink"); // Do not shrink is the option is all
    field_group_third.style.display = "block";
  }

}



// Function to handle multiple files selection
function handleFiles(event) {
  const files = event.target.files;  // Get all selected files
  const previewContainer = document.getElementById('filePreviews');
  previewContainer.innerHTML = '';  // Clear previous previews

  let imageCount = 0; // Keep track of how many images we preview

  Array.from(files).forEach(file => {
    const fileSizeInMB = (file.size / (1024 * 1024)).toFixed(2);  // Convert to MB
    const fileType = file.type;

    // Create a container for each file's preview and size
    const fileContainer = document.createElement('div');
    fileContainer.style.marginBottom = '30px';

    // Display the file size
    const fileSizeElement = document.createElement('p');
    fileSizeElement.textContent = `File size: ${fileSizeInMB} MB`;
    fileContainer.appendChild(fileSizeElement);

    // Preview the file based on type
    if (fileType.startsWith('image/')) {
      if (imageCount >= 3) {
        const warning = document.createElement('p');
        warning.style.color = 'red';
        warning.textContent = 'You can only upload 3 images at a time.';
        previewContainer.appendChild(warning);
        return;
      }

      const img = document.createElement('img');
      img.style.width = '70%';
      img.style.display = 'flex';
      img.src = URL.createObjectURL(file);
      img.onload = function () {
        URL.revokeObjectURL(img.src); // Free memory
      };

      fileContainer.appendChild(img);
      imageCount++;

    } else if (fileType === 'application/pdf') {
      const pdfEmbed = document.createElement('embed');
      pdfEmbed.style.width = '100%';
      pdfEmbed.style.height = '100%';
      pdfEmbed.src = URL.createObjectURL(file);
      fileContainer.appendChild(pdfEmbed);

    } else {
      const fileName = document.createElement('p');
      fileName.textContent = `File: ${file.name}`;
      fileContainer.appendChild(fileName);
    }

    // Append the file container to the previews section
    previewContainer.appendChild(fileContainer);
  });
}