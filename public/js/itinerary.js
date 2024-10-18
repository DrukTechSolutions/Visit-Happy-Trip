jQuery(document).ready(function () {
    var $collectionHolder = $('div.itinerary');
    let collectionCount = $('.copy-form-section').length;
    $collectionHolder.data('index', collectionCount);
    if (collectionCount === 0) {
        addItemForm($collectionHolder);
    }
    $(document).on('click', '.add-item-btn', function (e) {
        e.preventDefault();
        addItemForm($collectionHolder);
    });
});

function addItemForm($collectionHolder) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    var $newFormLi = $(newForm);
    var currentCount = $collectionHolder.children('.copy-form-section').length + 1;
    $newFormLi.find('.day-counter').text(currentCount);
    $collectionHolder.append($newFormLi);
    // var $addItemBtn = $('<a href="#" class="add-item-btn btn btn-primary btn-sm mt-2 mb-2">+ Add Itinerary</a>');
    // $newFormLi.append($addItemBtn);
    $(document).on('click', '.remove-btn', function (e) {
        e.preventDefault();
        var $formWrapper = $(this).closest('.copy-form-section');
        if ($collectionHolder.children('.copy-form-section').length > 1) {
            $formWrapper.remove();
            renumberItems($collectionHolder);
        }
    });
}

function renumberItems($collectionHolder) {
    $collectionHolder.children('.copy-form-section').each(function (index) {
        $(this).find('.day-counter').text(index + 1);
    });
}
