window.aboDetailsComponent = (function () {
    var that = {};
    //---Init Method---
    that.init = function () {
        //---Selectors---
        that.rootNode = $('#abodetails-index');
        that.abodetailsForm = that.rootNode.find('#abodetails-form');
        that.articleSelect = that.rootNode.find('#article');
        that.articletypeSelect = that.rootNode.find('#articletype');
        that.origin = that.rootNode.find('#origin');
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
        that.baseEndpointArticletypes = function (articleId) {
            window.overlay.show();
            var baseEnpointUrl = '/api/articles/' + articleId;
            $.get(baseEnpointUrl).success(that.articletypesLoaded).fail(window.notify.ajaxError);
        };
    };
    //---Load Methods---
    that.articletypesLoaded = function (data) {
        if (data.hasOrigin) {
            that.origin.removeAttr('disabled');
        }
        else {
            that.origin.attr('disabled', 'disabled');
        }
        that.articletypeSelect.html('');
        $.each(data.assignedArticletypes, function () {
            if (this.isActive) {
                that.articletypeSelect.append($('<option />').val(this.id).text(this.price));
            }
        });
        window.overlay.hide();
    }
    //---Init Components---
    that.initComponents = function () {
        $('.input-group.date').datepicker({
            language: "de",
            calendarWeeks: true,
            autoclose: true,
            startDate: "dateToday"
        });
        that.articleSelect.change(function () {
            that.baseEndpointArticletypes($(this).val());
        });
    }

    //---Custom Methods---

    return that;
}());