window.notify = (function () {
    var that = {};

    //--Notify Error--
    that.error = function (message) {
        $.notify({
            message: message
        },
            {
                type: 'danger',
                z_index: 9999,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                }
            });
    };
    //---Notify Info---
    that.info = function (message) {
        $.notify({
            message: message
        },
            {
                type: 'info',
                z_index: 9999,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                }
            });
    };
    //---Notify Warning---
    that.warning = function (message) {
        $.notify({
            message: message
        },
            {
                type: 'warning',
                z_index: 9999,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                }
            });
    };
    //---Notify Warning---
    that.ajaxError = function (data) {
        window.overlay.hide();
        var errorMessages;
        try {
            console.log(data);
            errorMessages = JSON.parse(data.responseText);
        } catch (e) {
            that.error('Es ist ein unerwarteter Fehler aufgetreten!');
        }
        if (errorMessages !== undefined) {
            for (var key in errorMessages) {
                that.error(key + ': ' + errorMessages[key]);
            }
        }
    };
    return that;
}());