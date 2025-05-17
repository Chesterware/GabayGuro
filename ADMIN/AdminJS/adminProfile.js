document.getElementById('file-input').addEventListener('change', function(event) {
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            const photoPlaceholder = document.getElementById('photo-placeholder');
            photoPlaceholder.style.backgroundImage = `url(${e.target.result})`;
            photoPlaceholder.innerHTML = '';

            document.getElementById('confirm-buttons').style.display = 'block';
        };

        reader.readAsDataURL(file);
    }
});

document.getElementById('cancelPhoto').addEventListener('click', function() {
    const photoPlaceholder = document.getElementById('photo-placeholder');
    photoPlaceholder.style.backgroundImage = '';
    photoPlaceholder.innerHTML = '<i class="fas fa-square"></i>';

    document.getElementById('file-input').value = '';
    document.getElementById('confirm-buttons').style.display = 'none';
});

document.getElementById('savePhoto').addEventListener('click', function() {
    const form = document.querySelector('form.profile-form');
    const formData = new FormData(form);
    const fileInput = document.getElementById('file-input');

    if (fileInput.files.length > 0) {
        formData.append('profile_photo', fileInput.files[0]);

        fetch('api/uploadProfile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Photo uploaded successfully!');
                const photoPlaceholder = document.getElementById('photo-placeholder');
                photoPlaceholder.style.backgroundImage = `url(${data.photo_url})`;
            } else {
                alert(`Error uploading photo: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Error uploading photo:', error);
            alert('There was an error uploading the photo.');
        });
    }
});

function toggleElementVisibility(elementId, isVisible) {
    const element = document.getElementById(elementId);
    element.style.display = isVisible ? "inline-block" : "none";
}
let originalValues = {};

document.getElementById("editProfile").addEventListener("click", function() {
    originalValues.first_name = document.getElementById("first_name").value;
    originalValues.middle_initial = document.getElementById("middle_initial").value;
    originalValues.last_name = document.getElementById("last_name").value;
    originalValues.email = document.getElementById("email").value;

    const fieldsToEnable = ["first_name", "middle_initial", "last_name"];
    fieldsToEnable.forEach(field => {
        const fieldElement = document.getElementById(field);
        fieldElement.readOnly = false; 
        fieldElement.required = true; 
    });

    document.getElementById("email").disabled = true;

    const passwordField = document.getElementById("password");
    passwordField.value = "";
    passwordField.disabled = false; 
    
    const confirmPasswordField = document.getElementById("confirmPassword");
    confirmPasswordField.disabled = false;
    confirmPasswordField.style.display = "block";

    document.querySelector("label[for='confirmPassword']").style.display = "inline-block";
on
    toggleElementVisibility("saveProfile", true);
    toggleElementVisibility("cancelEdit", true);
    toggleElementVisibility("editProfile", false);
});

document.getElementById("cancelEdit").addEventListener("click", function() {
    document.getElementById("first_name").value = originalValues.first_name;
    document.getElementById("middle_initial").value = originalValues.middle_initial;
    document.getElementById("last_name").value = originalValues.last_name;
    document.getElementById("email").value = originalValues.email;

    document.getElementById("password").disabled = true;
    document.getElementById("confirmPassword").disabled = true;

    document.getElementById("password").value = "********";
    document.getElementById("confirmPassword").value = "";

    document.querySelector("label[for='confirmPassword']").style.display = "none";
    document.getElementById("confirmPassword").style.display = "none"; 

    toggleElementVisibility("saveProfile", false);
    toggleElementVisibility("cancelEdit", false);
    toggleElementVisibility("editProfile", true);
});

function validatePassword(password) {
    const minLength = 8;
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);

    if (password.length < minLength) {
        alert("Password must be at least 8 characters long.");
        return false;
    }
    if (!hasUpperCase) {
        alert("Password must contain at least one uppercase letter.");
        return false;
    }
    if (!hasLowerCase) {
        alert("Password must contain at least one lowercase letter.");
        return false;
    }
    if (!hasNumber) {
        alert("Password must contain at least one number.");
        return false;
    }
    return true;
}

document.getElementById("saveProfile").addEventListener("click", function() {
    const firstName = document.getElementById("first_name").value;
    const middleInitial = document.getElementById("middle_initial").value;
    const lastName = document.getElementById("last_name").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    if (!firstName || !middleInitial || !lastName) {
            alert("All fields are required to be filled to proceed.");
            return;
        }

    if (!password || !confirmPassword) {
        alert("Both password and confirm password must be filled out.");
        return;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match. Please re-enter your passwords.");
        return;
    }

    if (!validatePassword(password)) {
        return;
    }

    const form = document.querySelector('form.profile-form');
    const formData = new FormData(form);

    const email = document.getElementById("email").value;
    formData.append("email", email);

    fetch('api/updateProfile.php', { 
        method: 'POST',
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        console.log('Server Response:', data); 

        if (data.success) {
            alert("Profile updated successfully!");

            const fieldsToReadonly = ["first_name", "middle_initial", "last_name"];
            fieldsToReadonly.forEach(field => {
                const fieldElement = document.getElementById(field);
                fieldElement.readOnly = true;  
                fieldElement.required = true; 
            });

            const fieldsToDisable = ["email", "password", "confirmPassword"];
            fieldsToDisable.forEach(field => {
                const fieldElement = document.getElementById(field);
                fieldElement.disabled = true;
            });
            document.getElementById("password").value = "********";
            document.getElementById("confirmPassword").value = "";

            document.querySelector("label[for='confirmPassword']").style.display = "none";
            document.getElementById("confirmPassword").style.display = "none";

            toggleElementVisibility("saveProfile", false);
            toggleElementVisibility("cancelEdit", false);
            toggleElementVisibility("editProfile", true);
        } else {
            alert(`Error updating profile: ${data.message || 'Unknown error'}`);
        }
    })
    .catch(error => {
        console.error("Error saving profile:", error);
        alert("There was an error saving your profile. Please try again.");
    });
});