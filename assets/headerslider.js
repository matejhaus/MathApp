$(document).ready(function(){
    $('.slider').hover(
        function() {
            $(this).css('animation-play-state', 'paused');
        },
        function() {
            $(this).css('animation-play-state', 'running');
        }
    );
});
