function toggleSidebar() {
    document.body.classList.toggle("sidebar-collapsed");
}

function enableEdit(index) {
    const select = document.getElementById('status-' + index);
    const editBtn = document.getElementById('edit-btn-' + index);
    const cancelBtn = document.getElementById('cancel-btn-' + index);
    const updateBtn = document.getElementById('update-btn-' + index);
    const deleteBtn = document.getElementById('delete-btn-' + index);

    select.disabled = false;
    editBtn.style.display = 'none';
    cancelBtn.style.display = 'inline-block';
    updateBtn.style.display = 'inline-block';
    deleteBtn.style.display = 'none';
}

function cancelEdit(index) {
    const select = document.getElementById('status-' + index);
    const editBtn = document.getElementById('edit-btn-' + index);
    const cancelBtn = document.getElementById('cancel-btn-' + index);
    const updateBtn = document.getElementById('update-btn-' + index);
    const deleteBtn = document.getElementById('delete-btn-' + index);

    select.disabled = true;

    const originalValue = select.getAttribute('data-original');
    if (originalValue) {
        select.value = originalValue;
    }

    editBtn.style.display = 'inline-block';
    cancelBtn.style.display = 'none';
    updateBtn.style.display = 'none';
    deleteBtn.style.display = 'inline-block';
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('select[id^="status-"]').forEach(select => {
        select.setAttribute('data-original', select.value);
    });
});

const modal = document.getElementById("adminModal");
const btn = document.querySelector(".add-btn");
const close = document.querySelector(".modal .close");

btn.onclick = function() {
    modal.style.display = "block";
}

close.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

document.getElementById('adminForm').addEventListener('submit', function(e) {
    const emailInput = this.email.value.trim().toLowerCase();
    const password = this.password.value;
    const confirmPassword = this.confirm_password.value;

    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

    if (!passwordRegex.test(password)) {
        alert('Password must be at least 8 characters and include at least 1 uppercase letter, 1 lowercase letter, and 1 number.');
        e.preventDefault();
        return;
    }

    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        e.preventDefault();
        return;
    }

    this.email.value = emailInput;
});