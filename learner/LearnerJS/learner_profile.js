const sidebar = document.querySelector('.sidebar');
const header = document.querySelector('.header-title');
const profileContainer = document.querySelector('.profile-container');

const editProfileBtn = document.getElementById('editProfileInfo');
const cancelProfileBtn = document.getElementById('cancelProfileEdit');
const saveProfileBtn = document.getElementById('saveProfileInfo');
const profileCancelSave = cancelProfileBtn.parentElement;

const profileFields = [
    document.getElementById('first-name'),
    document.getElementById('middle-name'),
    document.getElementById('last-name'),
    document.getElementById('birthdate'),
    document.getElementById('school-affiliation'),
    document.getElementById('grade-level'),
    document.getElementById('strand')
];

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

let profileBackup = {};
let passwordBackup = {
    email: '',
    password: ''
};

let isEditMode = false;

function toggleSidebar() {
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

function handleGradeChange() {
    const gradeLevel = document.getElementById('grade-level').value;
    const strandField = document.getElementById('strand');
    
    if (!isEditMode) {
        strandField.disabled = true;
        strandField.style.backgroundColor = '#f0f0f0';
        strandField.style.pointerEvents = 'none';
        return;
    }
    
    if (['G7', 'G8', 'G9', 'G10', 'N/A'].includes(gradeLevel)) {
        strandField.value = 'N/A';
        strandField.disabled = true;
        strandField.style.backgroundColor = '#f0f0f0';
        strandField.style.pointerEvents = 'none';
    } else {
        strandField.disabled = false;
        strandField.style.backgroundColor = 'transparent';
        strandField.style.pointerEvents = 'auto';
    }
}

function toggleFields(fields, enable) {
    isEditMode = enable;
    
    fields.forEach(field => {
        if (field.tagName === 'SELECT') {
            field.disabled = !enable;
            field.style.backgroundColor = enable ? 'transparent' : '#f0f0f0';
            field.style.pointerEvents = enable ? 'auto' : 'none';
        } else {
            field.readOnly = !enable;
        }
    });
    
    handleGradeChange();
}

function togglePasswordVisibility(field, icon) {
    const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
    field.setAttribute('type', type);
    
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
}

editProfileBtn.addEventListener('click', () => {
    toggleFields(profileFields, true);
    profileCancelSave.style.display = 'flex';
    editProfileBtn.style.display = 'none';

    profileFields.forEach(field => profileBackup[field.id] = field.value);
});

document.getElementById('grade-level').addEventListener('change', handleGradeChange);

cancelProfileBtn.addEventListener('click', () => {
    toggleFields(profileFields, false);
    profileCancelSave.style.display = 'none';
    editProfileBtn.style.display = 'block';

    profileFields.forEach(field => field.value = profileBackup[field.id]);
});

saveProfileBtn.addEventListener('click', () => {
    const firstName = document.getElementById('first-name').value.trim();
    const middleInitial = document.getElementById('middle-name').value.trim();
    const lastName = document.getElementById('last-name').value.trim();
    const birthdate = document.getElementById('birthdate').value;
    const schoolAffiliation = document.getElementById('school-affiliation').value.trim();
    const gradeLevel = document.getElementById('grade-level').value;
    const strand = document.getElementById('strand').value;
    
    if (!firstName || !lastName || !birthdate) {
        alert('Please fill in all required fields');
        return;
    }
    
    if (middleInitial.length > 1) {
        alert('Middle initial must be a single character');
        return;
    }
    
    const formData = new FormData();
    formData.append('first_name', firstName);
    formData.append('middle_initial', middleInitial);
    formData.append('last_name', lastName);
    formData.append('birthdate', birthdate);
    formData.append('school_affiliation', schoolAffiliation);
    formData.append('grade_level', gradeLevel);
    formData.append('strand', strand);
    
    saveProfileBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> SAVING...';
    saveProfileBtn.disabled = true;
    
    fetch('../LearnerPHP/update_profile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Response is not JSON. The server might have encountered an error.');
        }
        
        if (!response.ok) {
            throw new Error(`Network response was not ok (${response.status})`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message || 'Profile updated successfully');
            
            document.querySelector('.learner-name').textContent = 
                `${firstName} ${middleInitial ? middleInitial + '.' : ''} ${lastName}`;
                
            toggleFields(profileFields, false);
            profileCancelSave.style.display = 'none';
            editProfileBtn.style.display = 'block';
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

    passwordBackup.email = emailField.value;
    passwordBackup.password = '••••••••';
});

cancelPasswordBtn.addEventListener('click', () => {
    emailField.readOnly = true;
    currentPasswordWrapper.style.display = 'none';
    passwordField.readOnly = true;
    confirmPasswordWrapper.style.display = 'none';
    passwordCancelSave.style.display = 'none';
    editPasswordBtn.style.display = 'block';

    emailField.value = passwordBackup.email;
    passwordField.value = passwordBackup.password;
    confirmPasswordField.value = '';

    toggleCurrentPassword.classList.add('hidden');
    togglePassword.classList.add('hidden');
    toggleConfirmPassword.classList.add('hidden');

    passwordField.type = 'password';
    confirmPasswordField.type = 'password';
    currentPasswordField.type = 'password';
    
    toggleCurrentPassword.classList.remove('fa-eye');
    toggleCurrentPassword.classList.add('fa-eye-slash');
    togglePassword.classList.remove('fa-eye');
    togglePassword.classList.add('fa-eye-slash');
    toggleConfirmPassword.classList.remove('fa-eye');
    toggleConfirmPassword.classList.add('fa-eye-slash');
});

savePasswordBtn.addEventListener('click', () => {
    const currentPassword = currentPasswordField.value;
    const newPassword = passwordField.value;
    const confirmPassword = confirmPasswordField.value;
    
    if (!currentPassword) {
        alert('Please enter your current password');
        return;
    }
    
    if (!newPassword || !confirmPassword) {
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
    
    const hasUppercase = /[A-Z]/.test(newPassword);
    const hasLowercase = /[a-z]/.test(newPassword);
    const hasNumber = /[0-9]/.test(newPassword);
    
    if (!hasUppercase || !hasLowercase || !hasNumber) {
        alert('Password must contain at least one uppercase letter, one lowercase letter, and one number');
        return;
    }
    
    const formData = new FormData();
    formData.append('current_password', currentPassword);
    formData.append('new_password', newPassword);
    
    savePasswordBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> SAVING...';
    savePasswordBtn.disabled = true;
    
    fetch('../LearnerPHP/update_password.php', {
    method: 'POST',
    body: formData
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Response is not JSON. The server might have encountered an error.');
        }
        
        if (!response.ok) {
            throw new Error(`Network response was not ok (${response.status})`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message || 'Password updated successfully');
            
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

toggleCurrentPassword.addEventListener('click', function() {
    togglePasswordVisibility(currentPasswordField, toggleCurrentPassword);
});

togglePassword.addEventListener('click', function() {
    togglePasswordVisibility(passwordField, togglePassword);
});

toggleConfirmPassword.addEventListener('click', function() {
    togglePasswordVisibility(confirmPasswordField, toggleConfirmPassword);
});

setInterval(updateDateTime, 1000);
updateDateTime();

document.addEventListener('DOMContentLoaded', function() {
    isEditMode = false;
    
    document.querySelectorAll('select').forEach(select => {
        select.style.pointerEvents = 'none';
    });
    
    handleGradeChange();
});