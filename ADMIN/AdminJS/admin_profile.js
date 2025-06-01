function toggleSidebar() {
    document.body.classList.toggle("sidebar-collapsed");
}

function clearUrlParams() {
    const url = window.location.href.split('?')[0];
    window.history.replaceState({}, document.title, url);
}

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(clearUrlParams, 100);
    
    const editProfileBtn = document.getElementById('edit-profile-btn');
    const cancelProfileBtn = document.getElementById('cancel-profile-btn');
    const updateProfileBtn = document.getElementById('update-profile-btn');
    const profileForm = document.getElementById('profile-form');
    
    const originalValues = {
        first_name: document.getElementById('first_name').value,
        middle_initial: document.getElementById('middle_initial').value,
        last_name: document.getElementById('last_name').value
    };
    
    editProfileBtn.addEventListener('click', function() {
        document.getElementById('first_name').disabled = false;
        document.getElementById('middle_initial').disabled = false;
        document.getElementById('last_name').disabled = false;
        
        editProfileBtn.style.display = 'none';
        cancelProfileBtn.style.display = 'inline-block';
        updateProfileBtn.style.display = 'inline-block';
    });
    
    cancelProfileBtn.addEventListener('click', function() {
        document.getElementById('first_name').disabled = true;
        document.getElementById('middle_initial').disabled = true;
        document.getElementById('last_name').disabled = true;
        
        document.getElementById('first_name').value = originalValues.first_name;
        document.getElementById('middle_initial').value = originalValues.middle_initial;
        document.getElementById('last_name').value = originalValues.last_name;
        
        editProfileBtn.style.display = 'inline-block';
        cancelProfileBtn.style.display = 'none';
        updateProfileBtn.style.display = 'none';
    });
    
    const passwordForm = document.getElementById('password-form');
    passwordForm.addEventListener('submit', function(e) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        
        if (!passwordRegex.test(newPassword)) {
            alert('Password must be at least 8 characters and include at least 1 uppercase letter, 1 lowercase letter, and 1 number.');
            e.preventDefault();
            return;
        }
        
        if (newPassword !== confirmPassword) {
            alert('New passwords do not match.');
            e.preventDefault();
            return;
        }
    });
    
    const errorAlert = document.getElementById('errorAlert');
    const successAlert = document.getElementById('successAlert');
    
    if (errorAlert) {
        setTimeout(function() {
            errorAlert.classList.add('hide');
        }, 3000);
    }
    
    if (successAlert) {
        setTimeout(function() {
            successAlert.classList.add('hide');
        }, 3000);
    }

    if (document.getElementById('current_password')) {
        document.getElementById('current_password').value = '';
    }
    if (document.getElementById('new_password')) {
        document.getElementById('new_password').value = '';
    }
    if (document.getElementById('confirm_password')) {
        document.getElementById('confirm_password').value = '';
    }
});