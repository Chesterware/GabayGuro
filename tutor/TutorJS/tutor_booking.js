function selectStatus(event, status) {
    event.preventDefault();

    const clickedBtn = event.target.closest('.status-btn');
    if (!clickedBtn) return;

    document.querySelectorAll('.status-btn').forEach(btn =>
        btn.classList.remove('status-selected')
    );

    clickedBtn.classList.add('status-selected');

    document.querySelectorAll('.learner-booking-entry').forEach(entry => {
        const entryStatus = entry.getAttribute('data-status')?.toLowerCase();
        entry.style.display = (entryStatus === status) ? 'flex' : 'none';
    });
}

function updateBookingStatus(bookingId, status) {
    fetch('../TutorPHP/update_booking_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `booking_id=${bookingId}&status=${status}`
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === 'success') {
            location.reload();
        } else {
            alert("`Failed` to update booking status.");
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.accept-btn').forEach(button => {
        button.addEventListener('click', function () {
            const bookingEntry = this.closest('.learner-booking-entry');
            const bookingId = bookingEntry.dataset.bookingId;
            updateBookingStatus(bookingId, 'ongoing');
        });
    });

    document.querySelectorAll('.decline-btn').forEach(button => {
        button.addEventListener('click', function () {
            const bookingEntry = this.closest('.learner-booking-entry');
            const bookingId = bookingEntry.dataset.bookingId;
            updateBookingStatus(bookingId, 'rejected');
        });
    });

    document.querySelector('.status-btn.booking-pending')?.dispatchEvent(
        new MouseEvent("click", { bubbles: true, cancelable: true })
    );
});