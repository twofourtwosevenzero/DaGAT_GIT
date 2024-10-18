 // Add Document
  // Get modal element
  var modal = document.getElementById("myModal");

  // Get open modal button
  var openModalBtn = document.getElementById("openModalBtn");

  // Get close button
  var closeModalBtn = document.getElementById("closeModalBtn");

  // Listen for open click
  openModalBtn.addEventListener("click", function() {
    modal.style.display = "block";
  });

  // Listen for close click
  closeModalBtn.addEventListener("click", function() {
    modal.style.display = "none";
  });

function toggleDropdown() {
    var frame1 = document.getElementById("frame1");
    if (frame1.style.display === "block") {
        frame1.style.display = "none";
    } else {
        frame1.style.display = "block";
    }
}

function remove() {
    // Your remove logic here
    alert("Remove function called");
}

function openModal(button) {
    // Get data attributes from the button
    const documentId = button.getAttribute("data-id");
    const description = button.getAttribute("data-description");
    const documentType = button.getAttribute("data-document-type");
    const signatories = JSON.parse(button.getAttribute("data-signatories"));

    // Find the modal element
    const modal = document.getElementById("editModal");

    // Set modal fields with the provided data
    modal.querySelector("#description").value = description;
    modal.querySelector("#document_type").value = documentType;

    // Clear and set signatories checkboxes for College Offices
    const collegeSignatoriesDiv = modal.querySelector("#college-signatories");
    collegeSignatoriesDiv.querySelectorAll(".form-check-input").forEach((checkbox) => {
        checkbox.checked = signatories.includes(parseInt(checkbox.value, 10)); // Ensure value is an integer
    });

    // Clear and set signatories checkboxes for Admin Offices
    const adminSignatoriesDiv = modal.querySelector("#admin-signatories");
    adminSignatoriesDiv.querySelectorAll(".form-check-input").forEach((checkbox) => {
        checkbox.checked = signatories.includes(parseInt(checkbox.value, 10)); // Ensure value is an integer
    });

    // Clear and set signatories checkboxes for Services Offices
    const servicesSignatoriesDiv = modal.querySelector("#services-signatories");
    servicesSignatoriesDiv.querySelectorAll(".form-check-input").forEach((checkbox) => {
        checkbox.checked = signatories.includes(parseInt(checkbox.value, 10)); // Ensure value is an integer
    });

    // Set form action URL dynamically
    const editForm = document.getElementById("edit-form");
    editForm.action = `/documents/${documentId}`;

    // Initialize and show the Bootstrap modal
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
}

function closeModal() {
    document.getElementById("editModal").style.display = "none";
}

// Close the dropdown if the user clicks outside of it
window.onclick = function (event) {
    if (!event.target.matches(".group-dot") && !event.target.matches(".dot")) {
        var dropdowns = document.getElementsByClassName("frame1");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.style.display === "block") {
                openDropdown.style.display = "none";
            }
        }
    }

    // Close the modal if the user clicks outside of it
    if (event.target == document.getElementById("editModal")) {
        document.getElementById("editModal").style.display = "none";
    }
};
