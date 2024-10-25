$(document).ready(function () {
    $("#tour_overview").on('click', function () {
        $('html, body').animate({
            scrollTop: $("#tour_overview_wrapper").offset().top - $("#policy_wrapper").height() / 2
        }, 100);
    });
    $("#itinerary").on('click', function () {
        $('html, body').animate({
            scrollTop: $("#itinerary_wrapper").offset().top - $("#policy_wrapper").height() / 2
        }, 100);
    });
    $("#policy").on('click', function () {
        $('html, body').animate({
            scrollTop: $("#policy_wrapper").offset().top - $("#policy_wrapper").height() / 2
        }, 100);
    });
});