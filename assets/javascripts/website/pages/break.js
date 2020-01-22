var breakComponent = (function () {
    var that = {};
    //---Selectors---
    that.rootNode = $('#site-content').find('#pause-index');
    that.breakForm = that.rootNode.find('#breakForm');
    that.breakFormSubmit = that.breakForm.find("button");
    that.removeBreakButton = that.rootNode.find("#deleteBreakButton");

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
        that.baseEndpointCreatePause = function (request) {
            window.overlay.show();
            var baseEnpointUrl = '/api/create/pause';
            $.post(baseEnpointUrl, request).success(that.breakcreateLoad).fail(window.notify.ajaxError);
        };
        that.removePauseEndpoint = function (breakid) {
                window.overlay.show();
                var baseEnpointUrl = '/api/delete/pause/' + breakid;
                $.get(baseEnpointUrl).success(that.breakdeletedLoad).fail(window.notify.ajaxError);
        };
    };

    //---Load Methods---
    that.breakcreateLoad = function (data) {
        window.notify.info(data.response);
        setTimeout(location.reload.bind(location), 3000);
    }
    that.breakdeletedLoad = function (data) {
        window.notify.info(data.response);
        setTimeout(location.reload.bind(location), 3000);
    }
    //---Init Components---
    that.initComponents = function () {
        that.breakFormSubmit.click(function (e) {
            that.baseEndpointCreatePause(that.breakForm.serialize());
        });

        that.removeBreakButton.click(function (e) {
           that.removePauseEndpoint( $(this).data("breakid"));
        });
    }

    //---Custom Methods---

    return that;
}());

$(document).ready(function () {
    breakComponent.init();
});