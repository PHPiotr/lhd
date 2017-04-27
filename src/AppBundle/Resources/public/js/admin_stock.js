$(document).ready(function () {
    (function () {
        $('input.checkbox').on('change', function () {
            var that = $(this);
            if (that.is(':checked')) {
                $('input.checkbox:checked').not('#' + that.attr('id')).prop('checked', false);
            }
        });
    })();
});