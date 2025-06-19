document.querySelector('.addinput').addEventListener('click', function(e) {
    e.preventDefault();
    const numPets = parseInt(document.querySelector('.number').value, 10);
    const petsFields = document.getElementById('petsFields');
    petsFields.innerHTML = '';

    
    for (let i = 2; i <= numPets; i++) {
        petsFields.innerHTML += `
            <div class="ThirdQ">
                <div class="petsname"> 
                    <p class="lahiernss">Pet's Name ${i}</p>
                    <input type="text" placeholder=" " class="name" name="pet_name_${i}">
                </div>
                <div class="species"> 
                    <p class="lahiernss">Pet's Species ${i}</p>
                    <select id="species_${i}" name="species_${i}">
                        <option value="dog">Dog</option>
                        <option value="cat">Cat</option>
                        <option value="bird">Bird</option>
                        <option value="rabbit">Rabbit</option>
                        <option value="goat">Goats</option>
                        <option value="rat/mouse">Rats/Mice</option>
                        <option value="fish">Fishes</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="gender">
                    <p class="lahiernss">Pet's Gender ${i}</p>
                    <select id="pet_gender_${i}" name="pet_gender_${i}">
                            <option value="" disabled selected>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                </div>
            </div>
            <div class="FourthQ">
                <div class="age"> 
                    <p class="lahiernss">Pet's Age ${i}</p>
                    <input type="text" placeholder="" class="name" name="pet_age_${i}">
                </div>
                <div class="bday"> 
                    <p class="lahiernss">Pet's Birth Date ${i}</p>
                    <input type="date" placeholder="" class="name" name="pet_birthdate_${i}">
                </div>
                <div class="marking"> 
                    <p class="lahiernss">Pet's Color Marking ${i}</p>
                    <input type="text" placeholder="" class="name" name="pet_color_${i}">
                </div>
            </div>
            <hr>
        `;
    }
    adjustFilloutHeight();
});

function adjustFilloutHeight() {
    const fillout = document.querySelector('.fillout');
    const filloutContainer = document.querySelector('.fillout-Container');
    fillout.style.height = 'auto';
    fillout.style.height = filloutContainer.scrollHeight + 50 + 'px';
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.popup-container').forEach(function(popup) {
        popup.addEventListener('click', function() {
            popup.style.display = 'none';
        });
    });
    
    // Profile dropdown toggle
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