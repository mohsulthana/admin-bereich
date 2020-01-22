window.articledetailsComponent = (function () {
    var that = {};
    //---Init Method---
    that.init = function () {
        //---Selectors---
        that.rootNode = $('#articledetails-index');
        that.articleImage = that.rootNode.find('#article-image');
        that.articleImageFileInput = that.rootNode.find('#article-image-file-input');
        that.articletypesTable = that.rootNode.find('#articletypesTable');
        that.articletypeDetailsModal = $('#articles-index').find('#articletypeDetailsModal');
        that.btnArticletypeDetailsSave = that.articletypeDetailsModal.find('#btn-articletype-details-save');
        that.btnArticletypeDetailsClose = that.articletypeDetailsModal.find('#btn-articletype-details-close');
        that.articledetailsForm = that.rootNode.find('#articledetails-form');
        that.price = that.rootNode.find('#price');
        that.btnAddArticletype = that.rootNode.find('#btn-add-articletype');
        this.rootNode.one('destroyed', function () {
            that.dispose();
        });
        that.selectPrices = that.rootNode.find('#selectedPrices');
        that.initBaseEndpoints();
        that.initComponents();
    };
    //--Deconstructor--
    that.dispose = function () {
    };
    //---Endpoints---
    that.initBaseEndpoints = function () {
        that.baseEndpointArticletypeDetails = function (articletypeId) {
            window.overlay.show();
            var baseEnpointUrl = 'articletypedetails/' + articletypeId;
            $.get(baseEnpointUrl).success(that.articletypeDetailsLoad).fail(window.notify.ajaxError);
        };
    };
    //---Load Methods---
    that.articletypeDetailsLoad = function (data) {
        that.articletypeDetailsModal.find('.modal-body').html(data);
        that.articletypeDetailsModal.modal('show');
        window.overlay.hide();
    }
    //---Init Components---
    that.initComponents = function () {
        that.articleImageFileInput.fileinput({
            language: "de",
            uploadAsync: true,
            uploadUrl: "/api/imageupload",
            uploadExtraData: function() {
                return {
                    articleId: that.articledetailsForm.attr('data-article-id')
                };
            }
        });
        that.articleImageFileInput.on('fileuploaded', function (event, data, previewId, index) {
            that.articleImageFileInput.fileinput('refresh').fileinput('enable');
        });

        that.price.numeric();
        that.selectPrices.selectpicker();

        that.articletypesDataTable = that.articletypesTable.DataTable({
            "pageLength": 5,
            "bFilter": false,
            "bLengthChange": false,
            "language": {
                "url": "/js/vendor/i18n/datatables.de.json"
            },
            "ajax": {
                "url": '/api/articletypes/' + that.articledetailsForm.attr('data-article-id'),
                "dataSrc": function (json) {
                    var return_data = new Array();
                    for (var i = 0; i < json.length; i++) {
                        return_data.push([
                            json[i].price,
                            json[i].description,
                            '<span class="glyphicon glyphicon-' + (json[i].isActive ? 'ok' : 'remove') + '" aria-hidden="true"></span>',
                            '<button type="button" data-articletype-id="' + json[i].id + '" class="btn btn-default btn-sm btn-action"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>']);
                    }
                    return return_data;
                }
            },
            "initComplete": function (settings, json) {
                that.articletypesTable.on('click', '.btn-action', function () {
                    that.baseEndpointArticletypeDetails($(this).attr('data-articletype-id'));
                });
            },
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": ["sorting_disabled"]
            }],
        });
        that.btnArticletypeDetailsClose.click(function(){
            that.articletypeDetailsModal.modal('hide');
        });
        that.btnAddArticletype.click(function () {
            that.baseEndpointArticletypeDetails(0);
        });
    }

    //---Custom Methods---

    return that;
}());