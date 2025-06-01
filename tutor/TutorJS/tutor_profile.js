       function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const header = document.querySelector('.header-title');
        const profileContainer = document.querySelector('.profile-container');

        sidebar.classList.toggle('closed');
        header.classList.toggle('full-width');
        profileContainer.classList.toggle('sidebar-open');
    }

    function updateDateTime() {
        const now = new Date();
        const date = now.toLocaleDateString();
        const time = now.toLocaleTimeString();
        document.getElementById('datetime').innerHTML = `${date} ${time}`;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();

    function toggleFields(fields, enable) {
        fields.forEach(field => {
            if (field.tagName === 'SELECT') {
                field.disabled = !enable;
                field.style.backgroundColor = enable ? 'transparent' : '#f0f0f0';
            } else {
                field.readOnly = !enable;
            }
        });
    }

    // Profile Update Functionality
    const editProfileBtn = document.getElementById('editProfileInfo');
    const cancelProfileBtn = document.getElementById('cancelProfileEdit');
    const saveProfileBtn = document.getElementById('saveProfileInfo');
    const profileCancelSave = cancelProfileBtn.parentElement;

    const profileFields = [
        document.getElementById('first-name'),
        document.getElementById('middle-name'),
        document.getElementById('last-name'),
        document.getElementById('birthdate'),
        document.getElementById('educational-attainment'),
        document.getElementById('years-experience')
    ];

    let profileBackup = {};

    editProfileBtn.addEventListener('click', () => {
        toggleFields(profileFields, true);
        profileCancelSave.style.display = 'flex';
        editProfileBtn.style.display = 'none';

        profileFields.forEach(field => profileBackup[field.id] = field.value);
    });

    cancelProfileBtn.addEventListener('click', () => {
        toggleFields(profileFields, false);
        profileCancelSave.style.display = 'none';
        editProfileBtn.style.display = 'block';

        profileFields.forEach(field => field.value = profileBackup[field.id]);
    });

    saveProfileBtn.addEventListener('click', () => {
        const firstName = document.getElementById('first-name').value.trim();
        const lastName = document.getElementById('last-name').value.trim();
        const birthdate = document.getElementById('birthdate').value;
        const middleInitial = document.getElementById('middle-name').value.trim();
        
        if (!firstName || !lastName || !birthdate) {
            alert('Please fill in all required fields');
            return;
        }

        if (middleInitial.length > 1) {
            alert('Middle initial must be 1 character or less');
            return;
        }

        const formData = new FormData();
        formData.append('first_name', firstName);
        formData.append('middle_initial', middleInitial);
        formData.append('last_name', lastName);
        formData.append('birthdate', birthdate);
        formData.append('educational_attainment', document.getElementById('educational-attainment').value);
        formData.append('years_of_experience', document.getElementById('years-experience').value);

        saveProfileBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> SAVING...';
        saveProfileBtn.disabled = true;

        fetch('../TutorPHP/update_tutor_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Profile updated successfully!');
                toggleFields(profileFields, false);
                profileCancelSave.style.display = 'none';
                editProfileBtn.style.display = 'block';
                
                // Update displayed name
                document.querySelector('.logged-in-tutor').textContent = 
                    `${firstName} ${middleInitial ? middleInitial + '. ' : ''}${lastName}`;
            } else {
                throw new Error(data.message || 'Update failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: ' + error.message);
            profileFields.forEach(field => field.value = profileBackup[field.id]);
        })
        .finally(() => {
            saveProfileBtn.innerHTML = 'SAVE';
            saveProfileBtn.disabled = false;
        });
    });

// Password Update Functionality
const editPasswordBtn = document.getElementById('editPassword');
const cancelPasswordBtn = document.getElementById('cancelPasswordEdit');
const savePasswordBtn = document.getElementById('savePassword');
const passwordCancelSave = cancelPasswordBtn.parentElement;

const emailField = document.getElementById('email');
const currentPasswordWrapper = document.getElementById('current-password-wrapper');
const currentPasswordField = document.getElementById('current-password');
const passwordField = document.getElementById('password');
const confirmPasswordWrapper = document.querySelector('.confirmPassword');
const confirmPasswordField = document.getElementById('confirmPassword');

const toggleCurrentPassword = document.getElementById('toggleCurrentPassword');
const togglePassword = document.getElementById('togglePassword');
const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

editPasswordBtn.addEventListener('click', () => {
    emailField.readOnly = true;
    currentPasswordWrapper.style.display = 'block';
    passwordField.readOnly = false;
    confirmPasswordWrapper.style.display = 'block';
    passwordCancelSave.style.display = 'flex';
    editPasswordBtn.style.display = 'none';

    currentPasswordField.value = '';
    passwordField.value = '';
    confirmPasswordField.value = '';

    toggleCurrentPassword.classList.remove('hidden');
    togglePassword.classList.remove('hidden');
    toggleConfirmPassword.classList.remove('hidden');
});

cancelPasswordBtn.addEventListener('click', () => {
    emailField.readOnly = true;
    currentPasswordWrapper.style.display = 'none';
    passwordField.readOnly = true;
    confirmPasswordWrapper.style.display = 'none';
    passwordCancelSave.style.display = 'none';
    editPasswordBtn.style.display = 'block';

    currentPasswordField.value = '';
    passwordField.value = '••••••••';
    confirmPasswordField.value = '';

    toggleCurrentPassword.classList.add('hidden');
    togglePassword.classList.add('hidden');
    toggleConfirmPassword.classList.add('hidden');

    currentPasswordField.type = 'password';
    passwordField.type = 'password';
    confirmPasswordField.type = 'password';
});

savePasswordBtn.addEventListener('click', () => {
    const currentPassword = currentPasswordField.value;
    const newPassword = passwordField.value;
    const confirmPassword = confirmPasswordField.value;

    if (!currentPassword || !newPassword || !confirmPassword) {
        alert('Please fill in all password fields');
        return;
    }

    if (newPassword !== confirmPassword) {
        alert('New passwords do not match');
        return;
    }

    if (newPassword.length < 8) {
        alert('Password must be at least 8 characters long');
        return;
    }
    
    const hasUpperCase = /[A-Z]/.test(newPassword);
    const hasLowerCase = /[a-z]/.test(newPassword);
    const hasNumber = /[0-9]/.test(newPassword);
    
    if (!hasUpperCase || !hasLowerCase || !hasNumber) {
        alert('Password must contain at least one uppercase letter, one lowercase letter, and one number');
        return;
    }

    const formData = new FormData();
    formData.append('current_password', currentPassword);
    formData.append('new_password', newPassword);

    savePasswordBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> SAVING...';
    savePasswordBtn.disabled = true;

    fetch('../TutorPHP/update_tutor_password.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Network error');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Password updated successfully!');
            
            emailField.readOnly = true;
            currentPasswordWrapper.style.display = 'none';
            passwordField.readOnly = true;
            confirmPasswordWrapper.style.display = 'none';
            passwordCancelSave.style.display = 'none';
            editPasswordBtn.style.display = 'block';

            currentPasswordField.value = '';
            passwordField.value = '••••••••';
            confirmPasswordField.value = '';
            
            currentPasswordField.type = 'password';
            passwordField.type = 'password';
            confirmPasswordField.type = 'password';
            
            toggleCurrentPassword.classList.add('hidden');
            togglePassword.classList.add('hidden');
            toggleConfirmPassword.classList.add('hidden');
            
            toggleCurrentPassword.classList.remove('fa-eye');
            toggleCurrentPassword.classList.add('fa-eye-slash');
            
            togglePassword.classList.remove('fa-eye');
            togglePassword.classList.add('fa-eye-slash');
            
            toggleConfirmPassword.classList.remove('fa-eye');
            toggleConfirmPassword.classList.add('fa-eye-slash');
        } else {
            throw new Error(data.message || 'Password update failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    })
    .finally(() => {
        savePasswordBtn.innerHTML = 'SAVE';
        savePasswordBtn.disabled = false;
    });
});

function togglePasswordVisibility(field, icon) {
    const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
    field.setAttribute('type', type);
    
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
}

toggleCurrentPassword.addEventListener('click', function() {
    togglePasswordVisibility(currentPasswordField, toggleCurrentPassword);
});

togglePassword.addEventListener('click', function() {
    togglePasswordVisibility(passwordField, togglePassword);
});

toggleConfirmPassword.addEventListener('click', function() {
    togglePasswordVisibility(confirmPasswordField, toggleConfirmPassword);
});