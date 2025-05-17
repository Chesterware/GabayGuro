let selectedNotificationId = null;

function showDeleteNotificationModal(bookingId) {
    selectedNotificationId = bookingId;
    const deleteNotificationModal = document.getElementById('deleteNotificationModal');
    deleteNotificationModal.style.display = 'flex';
}

function closeNotificationModal() {
    const deleteNotificationModal = document.getElementById('deleteNotificationModal');
    deleteNotificationModal.style.display = 'none';
}

function deleteNotification() {
    if (selectedNotificationId) {
        fetch('../LearnerPHP/delete_notification.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ booking_id: selectedNotificationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Notification deleted!');
            } else {
                alert('Error deleting notification!');
            }
        });

        closeNotificationModal();
        setTimeout(() => {
            window.location.href = window.location.pathname;
        }, 100);
    }
}

function showDeleteAllModal() {
    const deleteAllModal = document.getElementById('deleteAllModal');
    deleteAllModal.style.display = 'flex';
}

function closeAllModal() {
    const deleteAllModal = document.getElementById('deleteAllModal');
    deleteAllModal.style.display = 'none';
}

function deleteAllNotifications() {
    fetch('../LearnerPHP/delete_all_notifications.php', {
        method: 'POST',
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('All notifications deleted!');
        } else {
            alert('Error deleting all notifications!');
        }
    });

    closeAllModal();
    setTimeout(() => {
        window.location.href = window.location.pathname;
    }, 100);
}

function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const header = document.querySelector('.header-title');
    const notificationsContainer = document.querySelector('.notifications-container');

    sidebar.classList.toggle('closed');
    header.classList.toggle('full-width');
    notificationsContainer.classList.toggle('full-width');
}

function updateDateTime() {
    const now = new Date();
    const date = now.toLocaleDateString();
    const time = now.toLocaleTimeString();
    document.getElementById('datetime').innerHTML = `${date} ${time}`;
}

setInterval(updateDateTime, 1000);
updateDateTime();