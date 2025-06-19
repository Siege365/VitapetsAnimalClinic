document.addEventListener('DOMContentLoaded', function () {
    const profileToggle = document.getElementById('profileToggle');
    const profileDropdown = document.getElementById('profileDropdown');
    const arrowIcon = document.getElementById('arrowIcon');
    const profilePic = document.querySelector('.profilepic');

    if (profileToggle && profileDropdown) {
        let isProfileDropdownHovered = false;

        // Mouse enter profile button
        profileToggle.addEventListener('mouseenter', function() {
            profileDropdown.classList.add('show');
            if (arrowIcon) arrowIcon.classList.add('rotate');
            if (profileToggle) profileToggle.classList.add('active');
            if (profilePic) profilePic.classList.add('active');
            if (arrowIcon) arrowIcon.classList.add('active');
        });

        // Mouse leave profile button
        profileToggle.addEventListener('mouseleave', function() {
            setTimeout(() => {
                if (!isProfileDropdownHovered) {
                    profileDropdown.classList.remove('show');
                    if (arrowIcon) arrowIcon.classList.remove('rotate', 'active');
                    if (profileToggle) profileToggle.classList.remove('active');
                    if (profilePic) profilePic.classList.remove('active');
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
            if (arrowIcon) arrowIcon.classList.remove('rotate', 'active');
            if (profileToggle) profileToggle.classList.remove('active');
            if (profilePic) profilePic.classList.remove('active');
        });
    }
});