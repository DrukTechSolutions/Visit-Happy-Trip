$(document).ready(function() {
    let sub_category = $('#tour_package_tourCategory');
    enableDisableSelect($('#tour_package_tourCategory option').length);
    $('#tour_package_tour_parent_category').on('change', function(){
        let category = $('#tour_package_tour_parent_category').val();
        var url = $('#tour-package-form').data('select-sub-category-url');
        sub_category.empty();
        $.ajax({
            url: url,
            method: 'POST',
            data : { category_id : category },
            success: function(res){
                enableDisableSelect(Object.keys(res).length);
                $.each(res, function(key, value) {
                    sub_category.append($('<option>', {
                        value: key,
                        text: value
                    }));
                });
            }
        });
    });

    function enableDisableSelect($len) {
        if($len == 0) {
            $('#tour_package_tourCategory').attr('disabled','disabled');
        } else {
            $('#tour_package_tourCategory').removeAttr('disabled');
        }
    }
});