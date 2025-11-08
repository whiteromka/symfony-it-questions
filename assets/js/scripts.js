$(document).ready(function() {

    //  Кнопка бургер меню. Разворачивает\сворачивает сайт
    let sidebarToggle = $('#sidebarToggle');
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

    // Кнопка поиска в главном меню
    $('#js-form-search').on('submit', function(e) {
        e.preventDefault();
        let query = $('#js-input-search').val()
        console.log(query)
        let url = '/search/index?query=' + query
        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log(data)
            })
            .catch()
    });
});


