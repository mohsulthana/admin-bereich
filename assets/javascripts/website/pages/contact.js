var contactComponent = (function () {
    var that = {};
    //---Selectors---
    that.rootNode = $('#site-content').find('#contact-index');
    that.contactForm = that.rootNode.find('#formContact');

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
        that.baseEndpointSendContact = function (request) {
            window.overlay.show();
            var baseEnpointUrl = '/api/contact';
            $.post(baseEnpointUrl, request).success(that.sendContactSuccess).fail(window.notify.ajaxError);
        };
    };
    //---Load Methods---
    that.sendContactSuccess = function (data) {
        window.notify.info(data.message);
        that.contactForm.find("input").prop("disabled", true);
        that.contactForm.find("textarea").prop("disabled", true);
        window.overlay.hide();
    }

    //---Init Components---
    that.initComponents = function () {
        that.contactForm.submit(function (e) {
            e.preventDefault();
            that.baseEndpointSendContact(that.contactForm.serialize());
        });
    }

    //---Custom Methods---

    return that;
}());

$(document).ready(function () {
    contactComponent.init();
});