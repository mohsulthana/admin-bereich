var billsComponent = (function () {
    var that = {};
    //---Selectors---
    that.rootNode = $('#admin-content').find('#bills-index');

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

    };
    //---Load Methods---

    //---Init Components---
    that.initComponents = function () {

    }

    //---Custom Methods---

    return that;
}());

$(document).ready(function () {
    billsComponent.init();
});