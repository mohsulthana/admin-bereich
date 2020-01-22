var deliverynotesComponent = (function () {
    var that = {};
    //---Selectors---
    that.rootNode = $('#admin-content').find('#deliverynotes-index');

    that.selectRegions = that.rootNode.find('#selectedRegions');
    that.btnCreateDeliverynotes = that.rootNode.find('#btn-create-deliverynotes');

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

        that.selectRegions.selectpicker();
        that.selectRegions.change(function(){
            if($(this).val() === null){
                that.btnCreateDeliverynotes.prop('disabled',true);
            }
            else{
                that.btnCreateDeliverynotes.prop('disabled',false);
            }
        });
    }

    //---Custom Methods---

    return that;
}());

$(document).ready(function () {
    deliverynotesComponent.init();
});