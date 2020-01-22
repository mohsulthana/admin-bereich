var loginComponent = (function () {
    var that = {};
    //---Selectors---
    that.rootNode = $('#login-nav');
    that.loginError = that.rootNode.find('#loginError');

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
        that.baseEndpointLogin = function (request) {
            window.overlay.show();
            var baseEnpointUrl = '/login';
            $.post(baseEnpointUrl, request).success(that.loginSuccess).fail(window.notify.ajaxError);
        };
    };
    //---Load Methods---
    that.loginSuccess = function (data) {
        if (data.user !== undefined) {
            that.loginError.hide();
            if (window.location.pathname == '/register') {
                window.location.href = '/';
            }
            else {
                location.reload();
            }
        }
        else {
            that.loginError.show();
        }
        window.overlay.hide();
    }

    //---Init Components---
    that.initComponents = function () {
        that.rootNode.submit(function (e) {
            e.preventDefault();
            that.baseEndpointLogin(that.rootNode.serialize());
        });
    }

    //---Custom Methods---

    return that;
}());

$(document).ready(function () {
    loginComponent.init();
});