document.addEventListener('DOMContentLoaded', function() {
    const stickyBar = document.getElementById('stickyBar');
    const header = document.querySelector('.profile-header');
    const roleOptions = document.querySelector('.role-options');

    const getOffset = () => {
        const headerHeight = header ? header.offsetHeight : 0;
        const optionsHeight = roleOptions ? roleOptions.offsetHeight : 0;
        return headerHeight + optionsHeight;
    };

    window.addEventListener('scroll', () => {
        const offset = getOffset();
        if (window.scrollY > offset - 50) {
            stickyBar.classList.add('visible');
        } else {
            stickyBar.classList.remove('visible');
        }
    });
});