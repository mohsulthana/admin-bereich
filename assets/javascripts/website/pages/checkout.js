var checkoutComponent = (function () {
  var that = {};
  //---Selectors---
  that.rootNode = $('#site-content').find('#checkout-index');

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
  checkoutComponent.init();
});