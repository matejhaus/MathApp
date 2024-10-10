$(document).ready(function() {
    $('.stats').on('click', function() {
        var $item = $(this).find('.content');
        var $title = $(this).find('.title');

        if ($item.is(':hidden')) {
            $item.slideDown();
            $title.addClass("opened");
        } else {
            $item.slideUp();
            $title.removeClass("opened");
        }
    });
});
