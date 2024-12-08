$(document).ready(function () {
    $('.add-image-wrapper').on('click', function () {
        let fileInput = $(this).closest('.col-md-4').find('.image-upload-file');
        let imageUploadId = fileInput.attr('id');
        $('#' + imageUploadId).click();
        $('#' + imageUploadId).on('change', function () {
            let imgNameDisplay = $(this).closest('.col-md-4').find('.image-file-name');
            let imageNameId = imgNameDisplay.attr('id');
            let filename = $(this)[0].files[0].name;
            var shortenedFilename = filename.substring(0, 8) + "..." + filename.substring(filename.length - 10);
            $('#' + imageNameId).text(shortenedFilename);
        });
    });


    $('#close-btn').on('click', function() {
        $('#left-menu-wrapper').toggleClass('col-md-3 col-md-1');
        $('#close-btn i').toggleClass('fa-xmark fa-plus');
        $('.menu-name').toggleClass('hide-menu-name');
        $('#right-content-wrapper').toggleClass('col-md-9 col-md-11');
        $('#expand-close-btn').toggleClass('text-end text-center');
        $('#menu-list-card-body').toggleClass('text-center');
        $('.nav-link i').toggleClass('me-3');
    });
});