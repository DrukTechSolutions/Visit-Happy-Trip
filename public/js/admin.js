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
});