//  Кнопка бургер меню. Разворачивает\сворачивает сайт
$(document).ready(function() {
    const sidebarToggle = $('#sidebarToggle');
    if (sidebarToggle.length > 0) {

        if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
            $('body').addClass('sb-sidenav-toggled');
        }

        sidebarToggle.on('click', function(event) {
            event.preventDefault();
            $('body').toggleClass('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', $('body').hasClass('sb-sidenav-toggled'));
        });
    }
});
