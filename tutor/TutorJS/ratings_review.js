function updateDateTime() {
    const now = new Date();
    const date = now.toLocaleDateString();
    const time = now.toLocaleTimeString();
    document.getElementById('datetime').innerHTML = `${date} ${time}`;
}

setInterval(updateDateTime, 1000);
updateDateTime();

function toggleSidebar() {
const sidebar = document.querySelector('.sidebar');
const header = document.querySelector('.header-title');
const tutorRating= document.querySelector('.tutor-rating');
const learnerReview = document.querySelector('.learner-review');

sidebar.classList.toggle('closed');
header.classList.toggle('full-width');
tutorRating.classList.toggle('sidebar-open');
learnerReview.classList.toggle('sidebar-open');
}