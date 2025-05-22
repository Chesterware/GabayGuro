function toggleSidebar() {
    document.body.classList.toggle("sidebar-collapsed");
}

function updateDateTime() {
    const now = new Date();
    const date = now.toLocaleDateString();
    const time = now.toLocaleTimeString();
    document.getElementById('datetime').innerHTML = `${date} ${time}`;
}

setInterval(updateDateTime, 1000);
updateDateTime();