var abosComponent = (function () {
    var that = {};
    //---Selectors---
    that.rootNode = $('#admin-content').find('#abos-index');
    that.abosTable = that.rootNode.find('#abosTable');
    that.aboDetailsModal = that.rootNode.find('#aboDetailsModal');
    that.btnAbodetailsSave = that.aboDetailsModal.find('#btn-abodetails-save');

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
        that.abosDataTable = that.abosTable.DataTable({
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
                        json[i].price,
                        '<img class="article--table--image" src="' + json[i].imagepath + '" />',
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
        that.btnAbodetailsSave.click(function () {
            //var articleDetailsForm = that.rootNode.find('#articledetails-index').find("#articledetails-form");
            //that.baseEndpointArticleDetailsSave(articleDetailsForm.attr('data-article-id'), articleDetailsForm.serialize());
        });
    }

    //---Custom Methods---

    return that;
}());

$(document).ready(function () {
    abosComponent.init();
});