window.userDetailsComponent = (function () {
    var that = {};
    //---Init Method---
    that.init = function () {
        //---Selectors---
        that.rootNode = $('#userdetails-index');
        that.userdetailsForm = that.rootNode.find('#userdetails-form');
        that.abosTable = that.rootNode.find('#abosTable');
        that.aboDetailsModal = $('#users-index').find('#aboDetailsModal');
        that.btnAboDetailsSave = that.aboDetailsModal.find('#btn-abo-details-save');
        that.btnAboDetailsClose = that.aboDetailsModal.find('#btn-abo-details-close');
        that.btnAddAbo = that.rootNode.find('#btn-add-abo');
        that.btnDeleteBreak = that.userdetailsForm.find('#btnDeleteBreak');
        that.breakId = that.userdetailsForm.find('#breakId');
        that.breakFrom = that.userdetailsForm.find('#breakFrom');
        that.breakTo = that.userdetailsForm.find('#breakTo');
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
        that.baseEndpointAboDetails = function (userId) {
            window.overlay.show();
            var baseEnpointUrl = 'abodetails/' + userId;
            $.get(baseEnpointUrl).success(that.aboDetailsLoad).fail(window.notify.ajaxError);
        };
    };
    //---Load Methods---
    that.aboDetailsLoad = function (data) {
        that.aboDetailsModal.find('.modal-body').html(data);
        that.aboDetailsModal.modal('show');
        window.overlay.hide();
    }
    //---Init Components---
    that.initComponents = function () {
        that.abosDataTable = that.abosTable.DataTable({
            "pageLength": 4,
            "bFilter": false,
            "bLengthChange": false,
            "language": {
                "url": "/js/vendor/i18n/datatables.de.json"
            },
            "ajax": {
                "url": '/api/abos/' + that.userdetailsForm.attr('data-user-id'),
                "dataSrc": function (json) {
                    var return_data = new Array();
                    for (var i = 0; i < json.length; i++) {
                        return_data.push([
                            json[i].articletype.article.name,
                            moment(json[i].startdate.date).format('DD.MM.YYYY'),
                            json[i].articletype !== null ? json[i].articletype.price : 0,
                            json[i].credit,
                            '<span class="glyphicon glyphicon-' + (json[i].enddate === null ? 'ok' : moment(json[i].enddate.date).isAfter(moment().subtract(1, 'd')) ? 'ok' : 'remove') + '" aria-hidden="true"></span>',
                            '<button type="button" data-abo-id="' + json[i].id + '" class="btn btn-default btn-sm btn-action"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>']);
                    }
                    return return_data;
                }
            },
            "initComplete": function (settings, json) {
                that.abosTable.on('click', '.btn-action', function () {
                    that.baseEndpointAboDetails($(this).attr('data-abo-id'));
                });
            },
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": ["sorting_disabled"]
            }],
        });
        that.btnAboDetailsClose.click(function () {
            that.aboDetailsModal.modal('hide');
        });
        that.btnAddAbo.click(function () {
            that.baseEndpointAboDetails(0);
        });
        $('.input-group.date').datepicker({
            language: "de",
            calendarWeeks: true,
            autoclose: true,
            startDate: "dateToday"
        });
        that.btnDeleteBreak.click(function () {
            that.btnDeleteBreak.attr('disabled', 'disabled');
            that.breakId.attr('name', 'breakId');
            that.breakFrom.val('');
            that.breakTo.val('');
        });
    }

    //---Custom Methods---

    return that;
}());