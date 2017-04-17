$(document).ready(function () {
    (function () {
        $('#accordion').on('shown.bs.collapse', function (event, target) {
            var that = $(this);
            var panelIn = that.find('.collapse.in').parent();
            panelIn.removeClass('closed');
            panelIn.addClass('open');
        });
        $('#accordion').on('hide.bs.collapse', function (event, target) {
            var that = $(this);
            var panelIn = that.find('.collapse.in').parent();
            panelIn.removeClass('open');
            panelIn.addClass('closed');
        });
    })();
});