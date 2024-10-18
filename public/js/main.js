$(document).ready(function () {
    $('.associated-brand-logo').hover(function () {
        $(this).css('transform', 'scale(0.9)');
        $(this).css('transition', ' 0.2s ease-in');
        $(this).removeClass('associated-brand-logo');
    }, function () {
        $(this).addClass('associated-brand-logo');
        $(this).css('transform', 'scale(1)');
    });
});