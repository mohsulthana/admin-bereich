var myabodetailComponent = (function () {
    var that = {};
    //---Selectors---
    that.rootNode = $('#site-content').find('#myabodetail-index');
    that.updateAboForm = that.rootNode.find('#updateAboForm');
    that.btnSaveAbo = that.updateAboForm.find('#btnSaveAbo');

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
        that.baseEndpointMyAboUpdate = function (formData) {
            window.overlay.show();
            var baseEnpointUrl = '/api/update/abo';
            $.post(baseEnpointUrl, formData).success(that.myAboUpdated).fail(window.notify.ajaxError);
            window.overlay.hide();
        };
    };
    //---Load Methods---
    that.myAboUpdated = function (data) {
            window.notify.info(data.answer);
    }
    //---Init Components---
    that.initComponents = function () {
        that.btnSaveAbo.click(function () {
            that.baseEndpointMyAboUpdate(that.updateAboForm.serialize());
        });
    }

    //---Custom Methods---

    return that;
}());

$(document).ready(function () {
    myabodetailComponent.init();
});