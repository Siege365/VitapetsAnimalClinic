document.addEventListener('DOMContentLoaded', function () {
    // Quote transition
    const quoteElement = document.querySelector('.quote');
    if (quoteElement) {
        quoteElement.classList.add('transition-in');
    }

    // Main profile dropdown
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

    // Sticky navbar
    const stickyNav = document.getElementById('stickyNav');
    const headerSection = document.querySelector('.head');
    const stickyProfileToggle = document.getElementById('stickyProfileToggle');
    const stickyProfileDropdown = document.getElementById('stickyProfileDropdown');

    function handleStickyNav() {
        if (!stickyNav || !headerSection) return;
        const headerBottom = headerSection.offsetTop + headerSection.offsetHeight;
        if (window.pageYOffset > headerBottom - 600) { //adjust sa sticky nav bar height
            stickyNav.classList.add('visible');
        } else {
            stickyNav.classList.remove('visible');
        }
    }

    window.addEventListener('scroll', handleStickyNav);
    handleStickyNav();    // Sticky profile dropdown - hover behavior
    if (stickyProfileToggle && stickyProfileDropdown) {
        let isStickyDropdownHovered = false;
        const stickyArrow = stickyProfileToggle.querySelector('.arrow');

        // Mouse enter sticky profile button
        stickyProfileToggle.addEventListener('mouseenter', function() {
            stickyProfileDropdown.classList.add('show');
            if (stickyArrow) stickyArrow.classList.add('rotate');
        });

        // Mouse leave sticky profile button
        stickyProfileToggle.addEventListener('mouseleave', function() {
            setTimeout(() => {
                if (!isStickyDropdownHovered) {
                    stickyProfileDropdown.classList.remove('show');
                    if (stickyArrow) stickyArrow.classList.remove('rotate');
                }
            }, 100);
        });

        // Mouse enter sticky dropdown
        stickyProfileDropdown.addEventListener('mouseenter', function() {
            isStickyDropdownHovered = true;
        });

        // Mouse leave sticky dropdown
        stickyProfileDropdown.addEventListener('mouseleave', function() {
            isStickyDropdownHovered = false;
            stickyProfileDropdown.classList.remove('show');
            if (stickyArrow) stickyArrow.classList.remove('rotate');
        });
    }
});
