$(document).ready(function() {
    (function() {
        $('.has-error :input').focus(function() {
            var that = $(this);
            that.parent().find('.help-block').remove();
            that.parents('.has-error').removeClass('has-error');
        });
    })();
});