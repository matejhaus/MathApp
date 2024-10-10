$(document).ready(function() {
    $(window).scroll(function() {
        if ($(this).scrollTop() > 10) {
            $('#toTopBtn').fadeIn();
        } else {
            $('#toTopBtn').fadeOut();
        }
    });

    $('#toTopBtn').click(function() {
        $('html, body').animate({ scrollTop: 0 }, 600);
        return false;
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const burgerMenu = document.getElementById("burger-menu");
    const navMenu = document.getElementById("nav-menu");

    burgerMenu.addEventListener("click", function() {
        navMenu.classList.toggle("active");
    });
});


