$(document).ready(function () {
    $('.image-upload-file').on('change', function () {
        $input_id = $(this).attr('id');
        let filename = $(this)[0].files[0];
        let img_preview = $(this).next('.img-preview-wrapper').find('.img-preview');
        let reader = new FileReader();
        console.log(img_preview.attr('id'));
        reader.onload = function(e) {
            $(img_preview).attr('src', e.target.result);
        };
        reader.readAsDataURL(filename);
    });
});