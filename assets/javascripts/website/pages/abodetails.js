var abodetailsComponent = (function () {
    var that = {};
    //---Selectors---
    that.rootNode = $('#site-content').find('#abodetails-index');
    that.orderAboForm = that.rootNode.find('#orderAboForm');
    that.btnOrderAbo = that.orderAboForm.find('#btnOrderAbo');
    that.orderAboModal = that.rootNode.find('#orderAboModal');
    that.btnOrdnerAboSubmitForm = that.orderAboModal.find("button.btn-success");
    that.hasSet = false;

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
        that.baseEndpointOrderAboSubmit = function (formData) {
            window.overlay.show();
            var baseEnpointUrl = '../api/create/abo';
            $.post(baseEnpointUrl, formData).success(that.orderAboSubmitLoad).fail(window.notify.ajaxError);
        };
        that.baseEndpointCheckout = function () {
            window.overlay.show();
            var baseEnpointUrl = '/checkout';
            $.get(baseEnpointUrl).success(that.checkoutLoad).fail(window.notify.ajaxError);
        };
        that.baseEndpointUpdateAddresses = function (formData) {
            window.overlay.show();
            var baseEnpointUrl = '../api/update/addresses';
            $.post(baseEnpointUrl, formData).success(that.updateAddressesLoad).fail(window.notify.ajaxError);
        };
        that.baseEndpointRegister = function (request) {
            window.overlay.show();
            var baseEnpointUrl = '/registration';
            $.post(baseEnpointUrl, request).success(that.registerSuccess).fail(window.notify.ajaxError);
        };
    };

    //---Load Methods---
    that.checkoutLoad = function (data) {
        that.orderAboModal.find('.modal-body').html(data);
        that.orderAboModal.find('#plzA').numeric();
        that.orderAboModal.find('#plzB').numeric();
        that.orderAboModal.modal('show');
        var orderAboModalCheckbox = that.orderAboModal.find('#hasShippingAddress');
        var shippingAddressArea = that.orderAboModal.find("#shippingAddressArea");
        orderAboModalCheckbox.click(function() {
            var me = $(this);
            if (me.is(":checked")) {
                shippingAddressArea.removeClass("hidden");
            }
            else {
                shippingAddressArea.addClass("hidden");
            }
        });

        if(that.hasSet == false) {
            that.btnOrdnerAboSubmitForm.click(function () {
                if(that.orderAboModal.find("#createUserPart").length > 0)
                {
                    var registerForm = that.orderAboModal.find("#register-form");
                    that.baseEndpointRegister(registerForm.serialize()); 
                    window.overlay.hide();
                }
                else
                {
                    var updateAddressesForm = that.orderAboModal.find("#orderAboSubmitForm");
                    that.baseEndpointUpdateAddresses(updateAddressesForm.serialize());
                }
            });
            that.hasSet = true;
        }

        window.overlay.hide();
    }
    
    that.updateAddressesLoad = function (data) {
        that.baseEndpointOrderAboSubmit(that.orderAboForm.serialize()); 
    }

    that.registerSuccess = function (data) {
        window.notify.info(data.answer);
        var registerPart = that.orderAboModal.find("#createUserPart");
        registerPart.hide();
        registerPart.remove();
        var updateAddressesForm = that.orderAboModal.find("#orderAboSubmitForm");
        that.baseEndpointUpdateAddresses(updateAddressesForm.serialize());
    }

    that.orderAboSubmitLoad = function (data) {
        window.overlay.hide();
        that.orderAboModal.modal('hide');
        window.notify.info(data.answer);
        window.setTimeout(function(){ window.location.href = '/myabos'; }, 3000);
    }

    //---Init Components---
    that.initComponents = function () {
        $('.input-group.date').datepicker({
            language: "de",
            calendarWeeks: true,
            autoclose: true,
            startDate: "dateToday",
        });

        $('.input-group.date').datepicker('update', new Date());

        that.orderAboForm.validator();

        that.btnOrderAbo.click(function () {
            if($('.input-group.date input').val() != "")
            {
                that.baseEndpointCheckout();
            }
        });
    }

    //---Custom Methods---

    return that;
}());

$(document).ready(function () {
    abodetailsComponent.init();
});