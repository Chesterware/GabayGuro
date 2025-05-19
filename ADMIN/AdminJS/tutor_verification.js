document.querySelectorAll('.status-buttons a').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();

            document.querySelectorAll('.status-buttons a').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const status = btn.getAttribute('data-status');

            document.querySelectorAll('.tutor-section').forEach(section => {
                if (section.getAttribute('data-status') === status) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        });
    });

    window.addEventListener('DOMContentLoaded', () => {
        const activeBtn = document.querySelector('.status-buttons a.active');
        if (activeBtn) {
            const status = activeBtn.getAttribute('data-status');
            document.querySelectorAll('.tutor-section').forEach(section => {
                section.style.display = (section.getAttribute('data-status') === status) ? 'block' : 'none';
            });
        }
    });

    document.querySelectorAll('.verify-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const form = this.closest('.verify-form');
            const tutorId = form.querySelector('input[name="tutor_id"]').value;
            const newStatus = this.getAttribute('data-status');

            fetch('../AdminPHP/upd_tutor_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `tutor_id=${tutorId}&new_status=${newStatus}`
            })
            .then(res => res.text())
            .then(response => {
                if (response === 'success') {
                    alert(`Tutor status updated to ${newStatus}.`);
                    location.reload();
                } else {
                    alert('Error updating status.');
                }
            });
        });
    });