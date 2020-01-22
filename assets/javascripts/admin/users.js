var usersComponent = (function () {
    var that = {};
    //---Selectors---
    that.rootNode = $('#admin-content').find('#users-index');
    that.usersTable = that.rootNode.find('#usersTable');
    that.userDetailsModal = that.rootNode.find('#userDetailsModal');
    that.btnUserdetailsSave = that.userDetailsModal.find('#btn-userdetails-save');
    that.aboDetailsModal = that.rootNode.find('#aboDetailsModal');
    that.btnAbodetailsSave = that.aboDetailsModal.find('#btn-abodetails-save');

    //---Init Method---
    that.init = function () {
        this.rootNode.one('destroyed', function () {
            that.dispose();
        });
        that.initBaseEndpoints();
        that.initComponents();
        if($.urlParam('userId') !== null){
            that.baseEndpointUserDetails($.urlParam('userId'));
        }
    };
    //--Deconstructor--
    that.dispose = function () {
    };
    //---Endpoints---
    that.initBaseEndpoints = function () {
        that.baseEndpointUserDetails = function (userId) {
            window.overlay.show(); 
            var baseEnpointUrl = 'userdetails/' + userId;
            $.get(baseEnpointUrl).success(that.userDetailsLoad).fail(window.notify.ajaxError);
        };
        that.baseEndpointUserDetailsSave = function (userId, formData) {
            window.overlay.show();
            var baseEnpointUrl = 'userdetails/' + userId;
            $.post(baseEnpointUrl, formData).success(that.userDetailsSaved).fail(window.notify.ajaxError);
        };
        that.baseEndpointAboDetailsSave = function (aboId, userId, formData) {
            window.overlay.show();
            var baseEnpointUrl = 'abodetails/' + userId + '/' + aboId;
            $.post(baseEnpointUrl, formData).success(that.aboDetailsSaved).fail(window.notify.ajaxError);
        };
    };
    //---Load Methods---
    that.userDetailsLoad = function (data) {
        that.userDetailsModal.find('.modal-body').html(data);
        that.userDetailsModal.modal('show');
        window.overlay.hide();
    }
    that.userDetailsSaved = function (data) {
        that.userDetailsModal.modal('hide');
        that.usersDataTable.ajax.reload();
        window.overlay.hide();
    }
    that.aboDetailsSaved = function (data) {
        that.aboDetailsModal.modal('hide');
        that.userDetailsModal.find('#abosTable').DataTable().ajax.reload();
        window.overlay.hide();
    }
    //---Init Components---
    that.initComponents = function () {
        that.usersDataTable = that.usersTable.DataTable({
            "bLengthChange": false,
            "pageLength": 12,
            "language": {
                "url": "/js/vendor/i18n/datatables.de.json"
            },
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": '/api/users',
                "dataSrc": function (json) {
                    var data = json.data;
                    var return_data = new Array();
                    for (var i = 0; i < data.length; i++) {
                        return_data.push([
                            data[i].billingAddress === null ? data[i].username : data[i].billingAddress.name + ', ' + data[i].billingAddress.firstname + ' (' + data[i].username + ')',
                            data[i].region === null ? '' : data[i].region.name,
                            data[i].billingAddress === null ? '' : data[i].billingAddress.street,
                            data[i].billingAddress === null ? '' : data[i].billingAddress.zip,
                            data[i].billingAddress === null ? '' : data[i].billingAddress.town,
                            '<span class="glyphicon glyphicon-'+(data[i].isActive ? 'ok' : 'remove')+'" aria-hidden="true"></span>',
                            '<button type="button" data-user-id="' + data[i].id + '" class="btn btn-default btn-sm btn-action"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>']);
                            data[i].billingAddress,
                            data[i].billingAddress
                    }
                    return return_data;
                },
                "data": function (d) {
                    var query = {
                        Top: d.length,
                        Skip: d.start,
                        Title: d.search.value,
                        OrderBy: d.order[0].column,
                        OrderByDesc: d.order[0].dir !== 'asc',
                    };
                    return query;
                }
            },
            "initComplete": function (settings, json) {
                that.usersTable.on('click', '.btn-action', function () {
                    that.baseEndpointUserDetails($(this).attr('data-user-id'));
                });
                var btnAddUser = '<button type="button" class="btn btn-success btn-sm" style="margin-right:1em" id="btn-add-user">';
                btnAddUser += '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Neuer Kunde';
                btnAddUser += '</button>';
                that.rootNode.find('.dataTables_filter').prepend(btnAddUser);
                that.btnAddUser = that.rootNode.find('.dataTables_filter').find('#btn-add-user');
                that.btnAddUser.click(function () {
                    that.baseEndpointUserDetails(0);
                });
            },
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": ["sorting_disabled"]
            }],
        });
        that.btnUserdetailsSave.click(function () {
            var userDetailsForm = that.rootNode.find('#userdetails-index').find("#userdetails-form");
            that.baseEndpointUserDetailsSave(userDetailsForm.attr('data-user-id'), userDetailsForm.serialize());
        });
        that.btnAbodetailsSave.click(function () {
            var userDetailsForm = that.rootNode.find('#userdetails-index').find("#userdetails-form");
            var aboDetailsForm = that.rootNode.find('#abodetails-index').find("#abodetails-form");
            that.baseEndpointAboDetailsSave(aboDetailsForm.attr('data-abo-id'), userDetailsForm.attr('data-user-id'), aboDetailsForm.serialize());
        });
    }

    //---Custom Methods---

    return that;
}());

$(document).ready(function () {
    usersComponent.init();
});