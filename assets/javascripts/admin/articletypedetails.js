window.articletypeDetailsComponent = (function () {
    var that = {};
    //---Init Method---
    that.init = function () {
        //---Selectors---
        that.rootNode = $('#articletypedetails-index');
        that.articletypedetailsForm = that.rootNode.find('#articletypedetails-form');
        that.price = that.rootNode.find('#price');
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
        that.price.numeric();
    }

    //---Custom Methods---

    return that;
}());