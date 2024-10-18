// Initialize Bootstrap Modals
const myModal = new bootstrap.Modal(document.getElementById('myModal'), {});
const editModal = new bootstrap.Modal(document.getElementById('editModal'), {});

// Get Modal Buttons and Elements
const openModalBtn = document.getElementById("openModalBtn");
const closeModalBtn = document.getElementById("closeModalBtn");

// Open Modal on Button Click
openModalBtn.addEventListener("click", function () {
    console.log('Open Modal Button Clicked');
    myModal.show();
});

// Close Modal on Button Click
closeModalBtn.addEventListener("click", function () {
    console.log('Close Modal Button Clicked');
    myModal.hide();
});

// Handle Document Type Change and Fetch Predefined Signatories
document.getElementById('document_type').addEventListener('change', function () {
    const documentTypeId = this.value;
    const predefinedSignatories = document.getElementById('predefined-signatories');

    // Clear previous signatories
    predefinedSignatories.innerHTML = '<p class="text-muted">Loading predefined signatories...</p>';

    // Fetch predefined signatories for the selected document type
    fetch(`/document-types/${documentTypeId}/signatories`)
        .then(response => response.json())
        .then(data => {
            predefinedSignatories.innerHTML = ''; // Clear loading message

            if (data.length > 0) {
                data.forEach(office => {
                    predefinedSignatories.innerHTML += `
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="office_${office.id}" 
                                   name="signatories[]" value="${office.id}" checked disabled>
                            <label class="form-check-label" for="office_${office.id}">
                                ${office.Office_Name}
                            </label>
                        </div>`;
                });
            } else {
                predefinedSignatories.innerHTML = '<p class="text-muted">No predefined signatories available for this type.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching predefined signatories:', error);
            predefinedSignatories.innerHTML = '<p class="text-danger">Failed to load signatories. Please try again.</p>';
        });
});

// Open Edit Modal with Populated Data
function openModal(button) {
    const documentId = button.getAttribute("data-id");
    const description = button.getAttribute("data-description");
    const documentType = button.getAttribute("data-document-type");
    const signatories = JSON.parse(button.getAttribute("data-signatories"));

    // Populate Modal Fields
    const modal = document.getElementById("editModal");
    modal.querySelector("#description").value = description;
    modal.querySelector("#document_type").value = documentType;

    // Reset and Set Signatories in the Modal
    const signatorySections = ['college-signatories', 'admin-signatories', 'services-signatories'];
    signatorySections.forEach(sectionId => {
        const sectionDiv = modal.querySelector(`#${sectionId}`);
        sectionDiv.querySelectorAll(".form-check-input").forEach(checkbox => {
            checkbox.checked = signatories.includes(parseInt(checkbox.value, 10));
        });
    });

    // Set Form Action URL Dynamically
    const editForm = document.getElementById("edit-form");
    editForm.action = `/documents/${documentId}`;

    // Show the Edit Modal
    editModal.show();
}

// Close Edit Modal
function closeModal() {
    editModal.hide();
}

// Close Dropdown or Modal When Clicking Outside
window.onclick = function (event) {
    if (!event.target.matches(".group-dot") && !event.target.matches(".dot")) {
        document.querySelectorAll(".frame1").forEach(dropdown => {
            if (dropdown.style.display === "block") {
                dropdown.style.display = "none";
            }
        });
    }

    if (event.target === document.getElementById("editModal")) {
        closeModal();
    }
};
