window.overlay = (function () {
    var that = {};
    //---Overlay Selector---
    that.rootNode = $('#site-overlay');
    that.overlayCounter = 0;
    //--Show Overlay--
    that.show = function () {
        that.overlayCounter = that.overlayCounter + 1;
        if (that.overlayCounter >= 0)
            that.rootNode.show();
    };
    //---Hide Overlay---
    that.hide = function () {
        if (that.overlayCounter > 0)
            that.overlayCounter = that.overlayCounter - 1;
        if (that.overlayCounter === 0)
            that.rootNode.hide();
    };
    //---Reset Overlay---
    that.reset = function () {
        that.overlayCounter = 0;
        that.rootNode.hide();
    };
    return that;
}());

$(document).ready(function () {
    window.overlay.hide();
});