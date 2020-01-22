var myabosComponent = (function () {
    var that = {};
    //---Selectors---
    that.rootNode = $('#site-content').find('#myprofile');
    that.addressesForm = that.rootNode.find('#addressesForm');
    that.shippingAddressCheckbox = that.addressesForm.find("#hasShippingAddress");
    that.shippingAddressArea = that.addressesForm.find("#shippingAddressArea");
    that.addressesFormSaveButton = that.addressesForm.find("button.btn-primary");
    that.zipaInput = that.addressesForm.find('#plzA');
    that.zipbInput = that.addressesForm.find('#plzB');

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
        that.baseEndpointUpdateAddresses = function (formData) {
            window.overlay.show();
            var baseEnpointUrl = '../api/update/addresses';
            $.post(baseEnpointUrl, formData).success(that.updateaddressesLoad).fail(window.notify.ajaxError);
        };

    };

    //---Load Methods---
    that.updateaddressesLoad = function (data) {
        window.overlay.hide();
        window.notify.info(data.answer);
    }

    //---Init Components---
    that.initComponents = function () {
        that.shippingAddressCheckbox.click(function() {
            var me = $(this);
            if (me.is(":checked")) {
                that.shippingAddressArea.removeClass("hidden");
            }
            else {
                that.shippingAddressArea.addClass("hidden");
            }
        });
        that.addressesFormSaveButton.click(function() {
            that.baseEndpointUpdateAddresses($('#addressesForm').serialize());
        });
        that.zipaInput.numeric();
        that.zipbInput.numeric();
    }

    //---Custom Methods---
    return that;
}());

$(document).ready(function () {
    myabosComponent.init();
}); 