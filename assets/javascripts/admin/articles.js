var articlesComponent = (function () {
    var that = {};
    //---Selectors---
    that.rootNode = $('#admin-content').find('#articles-index');
    that.articlesTable = that.rootNode.find('#articlesTable');
    that.articleDetailsModal = that.rootNode.find('#articleDetailsModal');
    that.articletypeDetailsModal = that.rootNode.find('#articletypeDetailsModal');
    that.btnArticletypedetailsSave = that.articletypeDetailsModal.find('#btn-articletype-save');
    that.btnArticledetailsSave = that.articleDetailsModal.find('#btn-articledetails-save');

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
        that.baseEndpointArticleDetails = function (articleId) {
            window.overlay.show();
            var baseEnpointUrl = 'articledetails/' + articleId;
            $.get(baseEnpointUrl).success(that.articleDetailsLoad).fail(window.notify.ajaxError);
        };
        that.baseEndpointArticleDetailsSave = function (articleId, formData) {
            window.overlay.show();
            var baseEnpointUrl = 'articledetails/' + articleId;
            $.post(baseEnpointUrl, formData).success(that.articleDetailsSaved).fail(window.notify.ajaxError);
        };
        that.baseEndpointArticletypeDetailsSave = function (articletypeId, articleId, formData) {
            window.overlay.show();
            var baseEnpointUrl = 'articletypedetails/' + articleId + '/' + articletypeId;
            $.post(baseEnpointUrl, formData).success(that.articletypeDetailsSaved).fail(window.notify.ajaxError);
        };
    };
    //---Load Methods---
    that.articleDetailsLoad = function (data) {
        that.articleDetailsModal.find('.modal-body').html(data);
        that.articleDetailsModal.modal('show');
        window.overlay.hide();
    }
    that.articleDetailsSaved = function (data) {
        that.articleDetailsModal.modal('hide');
        that.articlesDataTable.ajax.reload();
        window.overlay.hide();
    }
    that.articletypeDetailsSaved = function (data) {
        that.articletypeDetailsModal.modal('hide');
        that.articleDetailsModal.find('#articletypesTable').DataTable().ajax.reload();
        window.overlay.hide();
    }
    //---Init Components---
    that.initComponents = function () {
        that.articlesDataTable = that.articlesTable.DataTable({
            "bLengthChange": false,
            "language": {
                "url": "/js/vendor/i18n/datatables.de.json"
            },
            "ajax": {
                "url": '/api/articles',
                "dataSrc": function (json) {
                    var return_data = new Array();
                    for (var i = 0; i < json.length; i++) {
                        return_data.push([json[i].name,
                        json[i].description,
                        '<img class="article--table--image" src="\\img\\articles\\' + json[i].imagepath + '" />',
                        '<button type="button" data-article-id="' + json[i].id + '" class="btn btn-default btn-sm btn-action"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>']);
                    }
                    return return_data;
                }
            },
            "initComplete": function (settings, json) {
                that.articlesTable.on('click', '.btn-action', function () {
                    that.baseEndpointArticleDetails($(this).attr('data-article-id'));
                });
            },
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": ["sorting_disabled"]
            }],
        });
        that.btnArticletypedetailsSave.click(function () {
            var articleDetailsForm = that.rootNode.find('#articledetails-index').find("#articledetails-form");
            var articletypeDetailsForm = that.rootNode.find('#articletypedetails-index').find("#articletypedetails-form");

            that.baseEndpointArticletypeDetailsSave(articletypeDetailsForm.attr('data-articletype-id'), articleDetailsForm.attr('data-article-id'), articletypeDetailsForm.serialize());
        });
        that.btnArticledetailsSave.click(function () {
            var articleDetailsForm = that.rootNode.find('#articledetails-index').find("#articledetails-form");
            that.baseEndpointArticleDetailsSave(articleDetailsForm.attr('data-article-id'), articleDetailsForm.serialize());
        });
    }

    //---Custom Methods---

    return that;
}());

$(document).ready(function () {
    articlesComponent.init();
});