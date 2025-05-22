let selectedStars = 0;
let currentBookingId = null;

function selectStatus(event, status) {
    event.preventDefault();

    const buttons = document.querySelectorAll('.status-btn');
    buttons.forEach(button => button.classList.remove('status-selected'));

    const selectedButton = event.target;
    selectedButton.classList.add('status-selected');

    window.location.href = window.location.pathname + '?status=' + status;
}

document.querySelectorAll('.cancel-btn').forEach(button => {
    button.addEventListener('click', function () {
        const bookingId = this.id.replace('cancelBtn', '');
        updateBookingStatus(bookingId, 'cancelled');
    });
});

document.querySelectorAll('.finish-btn').forEach(button => {
    button.addEventListener('click', function () {
        const bookingId = this.id.replace('finishBtn', '');
        updateBookingStatus(bookingId, 'for review');
    });
});

function updateBookingStatus(bookingId, newStatus) {
    fetch('../LearnerPHP/update_booking_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `booking_id=${bookingId}&new_status=${encodeURIComponent(newStatus)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert("Failed to update booking status: " + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("An unexpected error occurred.");
    });
}

function openReviewModal(bookingId) {
    currentBookingId = bookingId;
    document.getElementById('comment').value = '';
    selectedStars = 0;
    document.querySelectorAll('.stars i').forEach(star => star.classList.remove('selected'));
    document.getElementById('reviewModal').style.display = 'flex';
}

function closeReviewModal() {
    document.getElementById('reviewModal').style.display = 'none';
}

function selectStar(starNumber) {
    selectedStars = starNumber;
    const stars = document.querySelectorAll('.stars i');
    stars.forEach((star, index) => {
        star.classList.toggle('selected', index < starNumber);
    });
}

document.querySelectorAll('.stars i').forEach((star, index) => {
    star.addEventListener('mouseover', () => {
        document.querySelectorAll('.stars i').forEach((s, i) => {
            s.classList.toggle('hover', i <= index);
        });
    });
    star.addEventListener('mouseout', () => {
        document.querySelectorAll('.stars i').forEach(s => s.classList.remove('hover'));
    });
});

document.querySelectorAll('.write-review-btn').forEach(button => {
    button.addEventListener('click', function () {
        const bookingId = this.id.replace('writeReviewBtn', '');
        openReviewModal(bookingId);
    });
});

document.getElementById('cancelButton').addEventListener('click', function() {
    closeReviewModal();
});

document.querySelector('.confirm-button').addEventListener('click', function () {
    const comment = document.getElementById('comment').value.trim();

    if (!selectedStars || !comment) {
        alert('Please provide a rating and a comment.');
        return;
    }

    const confirmSubmission = confirm('Are you sure you want to submit your review?');
    if (confirmSubmission) {
        fetch('../LearnerPHP/rate_review.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `booking_id=${currentBookingId}&rating=${selectedStars}&comment=${encodeURIComponent(comment)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Review submitted successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        });

        closeReviewModal();
    }
});

window.addEventListener('click', function (e) {
    const modal = document.getElementById('reviewModal');
    if (e.target === modal) {
        closeReviewModal();
    }
});