var registerComponent = (function () {
    var that = {};
    //---Selectors---
    that.rootNode = $('#site-content').find('#register-index');
    that.registerForm = $('#site-content').find('#register-form');

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
        that.baseEndpointRegister = function (request) {
            window.overlay.show();
            var baseEnpointUrl = '/registration';
            $.post(baseEnpointUrl, request).success(that.registerSuccess).fail(window.notify.ajaxError);
        };
    };
    //---Load Methods---
    that.registerSuccess = function (data) {
        window.location.href = '/';
    }

    //---Init Components---
    that.initComponents = function () {
        that.registerForm.submit(function (e) {
            e.preventDefault();
            that.baseEndpointRegister(that.registerForm.serialize());
        });
    }

    //---Custom Methods---

    return that;
}());

$(document).ready(function () {
    registerComponent.init();
});