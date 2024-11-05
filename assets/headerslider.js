$(document).ready(function() {
    const $slides = $('.slider-item');
    const $dots = $('.slider-dot');
    let currentIndex = 0;
    const slideCount = $slides.length;
    const slideInterval = 5000;

    function showSlide(index) {
        $slides.removeClass('slider-item-active').eq(index).addClass('slider-item-active');
        $dots.removeClass('active').eq(index).addClass('active');
        $('.slider').animate({ 'margin-left': `-${(index * 100)}%` }, 500);
    }

    $dots.click(function() {
        currentIndex = $(this).index();
        showSlide(currentIndex);
        resetInterval();
    });

    function autoSlide() {
        currentIndex = (currentIndex + 1) % slideCount;
        showSlide(currentIndex);
    }

    let autoSlideInterval = setInterval(autoSlide, slideInterval);

    function resetInterval() {
        clearInterval(autoSlideInterval);
        autoSlideInterval = setInterval(autoSlide, slideInterval);
    }

    showSlide(currentIndex);
});
