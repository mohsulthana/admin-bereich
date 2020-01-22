var myabosComponent = (function () {
    var that = {};
    //---Selectors---
    that.rootNode = $('#site-content').find('#myabos');
    that.editAboModal = that.rootNode.find('#editAboModal');
    that.saveAboChangesButton = that.editAboModal.find("button.btn-success");
    that.quitAboModal = that.rootNode.find('#quitAboModal');
    that.quitAboButton = that.quitAboModal.find("button.btn-success");
    that.aboTable = that.rootNode.find("#articlesTable");
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
        that.baseEndpointMyAboUpdate = function (formData) {
            window.overlay.show();
            var baseEnpointUrl = '../api/update/abo';
            $.post(baseEnpointUrl, formData).success(that.updateaboLoad).fail(window.notify.ajaxError);
        };
        that.baseEndpointMyAboQuit = function (formData) {
            window.overlay.show();
            var baseEnpointUrl = '../api/quit/abo';
            $.post(baseEnpointUrl, formData).success(that.quitaboLoad).fail(window.notify.ajaxError);
        };
        that.baseEndpointGetEditAboForm = function (aboid) {
            window.overlay.show();
            var baseEnpointUrl = '../myabos/abo/' + aboid;
            $.get(baseEnpointUrl).success(that.updateabomodalLoad).fail(window.notify.ajaxError);
        };
    };

    //---Load Methods---
    that.updateabomodalLoad = function (data) {
        that.editAboModal.find('.modal-body').html(data);
        that.editAboModal.modal('show');
        window.overlay.hide();
    }

    that.updateaboLoad = function (data) {debugger;
        window.overlay.hide();
        that.editAboModal.modal('hide');
        window.notify.info(data.answer);
        that.aboDataTable.ajax.reload();
    }

    //---Load Methods---
    that.quitabomodalLoad = function (id, name) {
        that.quitAboModal.find("#aboEndDate").val("");
        that.quitAboModal.find("#quitReason").val("");
        that.quitAboModal.find("#aboId").val(id);
        that.quitAboModal.find('#deleteAboModalTitle').text(name);
        that.quitAboModal.modal('show');
    }

    that.quitaboLoad = function (data) {
        window.overlay.hide();
        that.quitAboModal.modal('hide');
        window.notify.info(data.answer);
        that.aboDataTable.ajax.reload();
    }

    //---Init Components---
    that.initComponents = function () {
        that.saveAboChangesButton.click(function () {
            var orderAboSubmitForm = that.editAboModal.find("form");
            that.baseEndpointMyAboUpdate(orderAboSubmitForm.serialize());
        });
        that.quitAboButton.click(function () {
            var quitAboSubmitForm = that.quitAboModal.find("form");
            that.baseEndpointMyAboQuit(quitAboSubmitForm.serialize());
        });
        that.aboDataTable = that.aboTable.DataTable(
            {
                "bLengthChange": false,
                "language": { "url": "/js/vendor/i18n/datatables.de.json" },
                "ajax": {
                    "url": '/api/abos/' + that.aboTable.attr('data-user-id'),
                    "dataSrc": function (json) {
                        var return_data = new Array();
                        for (var i = 0; i < json.length; i++) {
                            return_data.push([
                                json[i].articletype.article.name + (json[i].enddate === null ? '' : ' <span style="font-style:italic;">(Abo wurde per ' + moment(json[i].enddate.date).format('DD.MM.YYYY') + ' gekündet)</span>'),
                                moment(json[i].startdate.date).format('DD.MM.YYYY'),
                                json[i].articletype.price !== null ? json[i].articletype.price : 0,
                                '<span class="glyphicon glyphicon-' + (json[i].enddate === null ? 'ok' : moment(json[i].enddate.date).isAfter(moment().subtract(1, 'd')) ? 'ok' : 'remove') + '" aria-hidden="true"></span>',
                                '<button type="button" data-aboid="' + json[i].id + '" class="btn btn-default btn-sm btn-action viki-editabo-btn" ' + (json[i].enddate === null ? '' : 'disabled') + '><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button> ' +
                                '<button type="button" data-aboid="' + json[i].id + '" data-aboname="' + json[i].articletype.article.name + '" class="btn btn-default btn-sm btn-action viki-quitabo-btn" ' + (json[i].enddate === null ? '' : 'disabled') + '><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>']);
                        }
                        return return_data;
                    }
                },
                "createdRow": function (row, data, index) {
                    if($(row).find('td:last-child').find("button:disabled").length > 0){
                        $(row).addClass('danger');
                    }
                },
                "initComplete": function (settings, json) {
                    that.aboTable.on('click', '.viki-editabo-btn', function () {
                        that.baseEndpointGetEditAboForm($(this).data("aboid") );
                    });
                    that.aboTable.on('click', '.viki-quitabo-btn', function () {
                        that.quitabomodalLoad($(this).data("aboid"), ($(this).data("aboname")));
                    });
                },
                "aoColumnDefs": [{
                    "bSortable": false,
                    "aTargets": ["sorting_disabled"]
                }],
            });
    }

    //---Custom Methods---
    return that;
}());

$(document).ready(function () {
    myabosComponent.init();
});