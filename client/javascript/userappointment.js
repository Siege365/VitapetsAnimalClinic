document.addEventListener('DOMContentLoaded', function() {
    // Profile dropdown toggle
    const profileToggle = document.getElementById('profileToggle');
    const profileDropdown = document.getElementById('profileDropdown');
    const arrowIcon = document.getElementById('arrowIcon');
    
    if (profileToggle && profileDropdown) {
        let isProfileDropdownHovered = false;

        // Mouse enter profile button
        profileToggle.addEventListener('mouseenter', function() {
            profileDropdown.classList.add('show');
            if (arrowIcon) arrowIcon.classList.add('rotate');
        });

        // Mouse leave profile button
        profileToggle.addEventListener('mouseleave', function() {
            setTimeout(() => {
                if (!isProfileDropdownHovered) {
                    profileDropdown.classList.remove('show');
                    if (arrowIcon) arrowIcon.classList.remove('rotate');
                }
            }, 100);
        });

        // Mouse enter dropdown
        profileDropdown.addEventListener('mouseenter', function() {
            isProfileDropdownHovered = true;
        });

        // Mouse leave dropdown
        profileDropdown.addEventListener('mouseleave', function() {
            isProfileDropdownHovered = false;
            profileDropdown.classList.remove('show');
            if (arrowIcon) arrowIcon.classList.remove('rotate');
        });
    }
    
    // Reason of visit popup functionality
    const reasonTextarea = document.getElementById('visitReason');
    document.querySelectorAll('.view-reason-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            reasonTextarea.value = btn.getAttribute('data-reason');
            reasonTextarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });

    // Table slider functionality
    const tableSlider = document.getElementById('tableSlider');
    // Bottom slider buttons
    document.getElementById('slideLeftBottom').onclick = function() {
        tableSlider.scrollBy({ left: -500, behavior: 'smooth' });
    };
    document.getElementById('slideRightBottom').onclick = function() {
        tableSlider.scrollBy({ left: 500, behavior: 'smooth' });
    };

    // Delete confirmation popup functionality
    const popup = document.getElementById('deletePopup');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const cancelBtn = document.getElementById('cancelDeleteBtn');
    let currentDeleteId = null;

    // Attach event listeners to all delete buttons
    document.querySelectorAll('.deletebtn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent form submission
            
            // Store the booking ID to delete
            const form = this.closest('form');
            currentDeleteId = form.querySelector('input[name="delete_id"]').value;
            
            // Show the confirmation popup
            popup.style.display = 'flex';
        });
    });

    // Confirm delete button
    confirmBtn.addEventListener('click', function() {
        if (currentDeleteId) {
            // Create and submit a form programmatically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = window.location.href; // Current page
            form.style.display = 'none';
            
            // Create hidden input for delete_id
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'delete_id';
            idInput.value = currentDeleteId;
            
            // Create hidden input for delete_appointment
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'delete_appointment';
            actionInput.value = '1';
            
            // Add inputs to form
            form.appendChild(idInput);
            form.appendChild(actionInput);
            
            // Add form to document and submit
            document.body.appendChild(form);
            form.submit();
        }
        
        // Hide popup
        popup.style.display = 'none';
    });

    // Cancel delete button
    cancelBtn.addEventListener('click', function() {
        popup.style.display = 'none';
        currentDeleteId = null;
    });
});