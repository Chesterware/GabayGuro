function toggleReviews(button) {
    const reviewsContainer = button.nextElementSibling;
    const isExpanded = reviewsContainer.style.display === 'block';

    reviewsContainer.style.display = isExpanded ? 'none' : 'block';
    button.classList.toggle('expanded', !isExpanded);

    const icon = button.querySelector('i');
    const reviewCount = reviewsContainer.querySelectorAll('.review-item').length || 0;
    icon.className = isExpanded ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
    button.innerHTML = `<i class="${icon.className}"></i> ${isExpanded ? 'View' : 'Hide'} Reviews (${reviewCount})`;
}

function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const header = document.querySelector('.header-title');
    const filterTop = document.querySelector('.filter-top');

    sidebar.classList.toggle('closed');
    header.classList.toggle('full-width');
    filterTop.classList.toggle('full-width');

    document.querySelectorAll('.tutor-card-wrapper').forEach(card => {
        card.classList.toggle('full-width');
    });
}

function updateDateTime() {
    const now = new Date();
    const date = now.toLocaleDateString();
    const time = now.toLocaleTimeString();
    document.getElementById('datetime').innerHTML = `${date} ${time}`;
}

setInterval(updateDateTime, 1000);
updateDateTime();

document.querySelectorAll('.choose-rate-btn').forEach(button => {
    button.addEventListener('click', function () {
        document.getElementById('rate-type-input').value = this.dataset.value;

        document.querySelectorAll('.choose-rate-btn').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('form').addEventListener('submit', function (event) {
        validateSpecializations(event);
    });

    document.querySelector('.apply-all').addEventListener('click', function (event) {
        validateSpecializations(event);
    });

    function validateSpecializations(event) {
        const specializationCheckboxes = document.querySelectorAll('input[name="specialization[]"]:checked');
        const specializationsContainer = document.getElementById('specializations-container');
        const errorMessage = document.getElementById('error-message');
        const minRate = document.querySelector('input[name="min_rate"]').value.trim();
        const maxRate = document.querySelector('input[name="max_rate"]').value.trim();
        const rateType = document.getElementById('rate-type-input').value;

        let hasError = false;
        let errorMessages = [];

        if (specializationCheckboxes.length === 0) {
            specializationsContainer.style.border = '2px solid red';
            specializationsContainer.style.padding = '10px';
            errorMessages.push("Please select at least one specialization.");
            hasError = true;
        } else {
            specializationsContainer.style.border = '';
        }

        const hasMinRate = minRate !== '';
        const hasMaxRate = maxRate !== '';
        const hasRateType = rateType !== '';
        const anyRateFieldFilled = hasMinRate || hasMaxRate || hasRateType;

        if (anyRateFieldFilled) {
            if (!hasMinRate || !hasMaxRate || !hasRateType) {
                errorMessages.push("If you want to filter by rate, please fill in the minimum rate and maximum rate text field, and select 'SESSION' or 'HOUR'.");
                hasError = true;
            } else if (parseFloat(minRate) > parseFloat(maxRate)) {
                errorMessages.push("Please enter a valid minimum and/or maximum amount");
                hasError = true;
            }
        }

        if (hasError) {
            errorMessage.style.display = 'block';
            errorMessage.innerHTML = errorMessages.join("<br>");
            event.preventDefault();
        } else {
            errorMessage.style.display = 'none';
        }
    }

    const modal = document.getElementById("bookingFormModal");

    function openBookingModal(tutorId) {
        document.getElementById("modal-tutor-id").value = tutorId;
        modal.style.display = "flex";
    }

    function closeModal() {
        modal.style.display = "none";
    }

    document.querySelectorAll(".tutor-add-button").forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const tutorId = this.getAttribute("onclick").match(/\d+/)[0];
            openBookingModal(tutorId);
        });
    });

    const cancelButton = document.querySelector(".cancel-booking");
    cancelButton.addEventListener("click", function (event) {
        event.preventDefault();
        closeModal();
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });
});

if (performance.getEntriesByType("navigation")[0].type === "reload") {
    setTimeout(() => {
        window.location.href = window.location.pathname;
    }, 100);
}
