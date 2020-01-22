var forgotComponent = (function () {
    var that = {};
    //---Selectors---
    that.rootNode = $('#site-content').find('#forgotpassword-index');
    that.resetForm = that.rootNode.find('#resetForm');
    that.resetEmail = that.resetForm.find('#resetEmail');
    that.resetButton = that.resetForm.find('#resetButton');
    that.resetSuccess = that.resetForm.find('#resetSuccess');

    that.setForm = that.rootNode.find('#setForm');
    that.setPassword = that.setForm.find('#setPassword');
    that.setPasswordConfirm = that.setForm.find('#setPasswordConfirm');
    that.setButton = that.setForm.find('#setButton');
    that.setError = that.setForm.find('#setError');

    //---Init Method---
    that.init = function () {
        this.rootNode.one('destroyed', function () {
            that.dispose();
        });
        that.initBaseEndpoints();
        that.initComponents();
    };
    //--Deconstructor--
    that.dispose = function () {
    };
    //---Endpoints---
    that.initBaseEndpoints = function () {
        that.baseEndpointResetPassword = function (request) {
            window.overlay.show();
            var baseEnpointUrl = '/resetpassword';
            $.post(baseEnpointUrl, request).success(that.resetPasswordSuccess).fail(window.notify.ajaxError);
        };
        that.baseEndpointSetPassword = function (request) {
            window.overlay.show();
            var baseEnpointUrl = '/setpassword';
            $.post(baseEnpointUrl, request).success(that.setPasswordSuccess).fail(window.notify.ajaxError);
        };
    };
    //---Load Methods---
    that.resetPasswordSuccess = function (data) {
        that.resetButton.hide();
        that.resetSuccess.show();
        that.resetEmail.prop("readonly", true);
        window.overlay.hide();
    }

    that.setPasswordSuccess = function (data) {
        if (data.user !== undefined) {
            that.setError.hide();
            if (window.location.pathname == '/register') {
                window.location.href = '/';
            }
            else {
                location.reload();
            }
        }
        else {
            that.setError.show();
        }
        window.overlay.hide();
    }

    //---Init Components---
    that.initComponents = function () {
        that.resetForm.submit(function (e) {
            e.preventDefault();
            that.baseEndpointResetPassword(that.resetForm.serialize());
        });
        that.setForm.submit(function (e) {
            e.preventDefault();
            that.baseEndpointSetPassword(that.setForm.serialize());
        });
    }

    //---Custom Methods---

    return that;
}());

$(document).ready(function () {
    forgotComponent.init();
});