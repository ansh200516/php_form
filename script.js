document.addEventListener('DOMContentLoaded', () => {
    const createNewTemplateBtn = document.getElementById('createNewTemplate');
    const formsTable = document.getElementById('formsTable');
    const formModal = document.getElementById('formModal');
    const closeModalBtn = document.querySelector('.close');
    const viewFormBtn = document.getElementById('viewFormBtn');
    const updateFormBtn = document.getElementById('updateFormBtn');

    // Landing Page Interactions
    if (createNewTemplateBtn) {
        createNewTemplateBtn.addEventListener('click', () => {
            window.location.href = 'form.php';
        });
    }

    if (formsTable) {
        const viewUpdateBtns = document.querySelectorAll('.view-btn');
        viewUpdateBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const courseCode = btn.dataset.code;
                formModal.style.display = 'block';

                viewFormBtn.onclick = () => {
                    window.location.href = `form.php?code=${courseCode}&mode=view`;
                };

                updateFormBtn.onclick = () => {
                    window.location.href = `form.php?code=${courseCode}&mode=update`;
                };
            });
        });
    }

    // Form Page Interactions
    const saveBtn = document.getElementById('saveBtn');
    const submitBtn = document.getElementById('submitBtn');
    const downloadBtn = document.getElementById('downloadBtn');
    const courseForm = document.getElementById('courseForm');

    if (saveBtn) {
        saveBtn.addEventListener('click', () => {
            const formData = new FormData(courseForm);
            fetch('save_form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                }
            });
        });
    }

    if (submitBtn) {
        submitBtn.addEventListener('click', () => {
            // Add validation logic
            const requiredFields = courseForm.querySelectorAll('[required]');
            const allFieldsFilled = Array.from(requiredFields).every(field => field.value.trim() !== '');

            if (allFieldsFilled) {
                const formData = new FormData(courseForm);
                formData.set('mode', 'submit');  // Change mode to submit
                
                // Log form data for debugging
                console.log("Submitting form with data:");
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }
                
                fetch('save_form.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Full response data:', data);
                    
                    // Log debug information if available
                    if (data.debug) {
                        console.log('Debug Information:', data.debug);
                    }
                    
                    if (data.success) {
                        alert(data.message);
                        downloadBtn.style.display = 'block';
                    } else {
                        // If success is false, show error message
                        alert(data.message || 'An unknown error occurred');
                    }
                })
                .catch(error => {
                    // Log the full error for debugging
                    console.error('Full error:', error);
                    
                    // Show a user-friendly error message
                    alert('Failed to submit the form. Please check the console for details.');
                });
            } else {
                alert('Please fill all required fields');
            }
        });
    }

    if (downloadBtn) {
        downloadBtn.addEventListener('click', () => {
            const courseCode = document.getElementById('code').value;
            window.location.href = `generate_pdf.php?code=${courseCode}`;
        });
    }

    // Close Modal
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', () => {
            formModal.style.display = 'none';
        });
    }
});
