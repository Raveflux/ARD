function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.querySelector('.content');

    if (sidebar.style.left === '0px') {
        sidebar.style.left = '-250px';
        content.classList.remove('shift');
    } else {
        sidebar.style.left = '0px';
        content.classList.add('shift');
    }
}
