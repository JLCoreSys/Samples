/**
 * User Administration Javascript
 */
$(document).ready(function () {
    initContextMenus();
    addFnServerData();
    var dt = $('#users-table').dataTable();
    dt.fnSettings().aoDrawCallback.push({
        'fn': userTableDraw,
        'sName': 'user'
    });

    addDateRangeToDataTables();
    addRoleSearchToDataTables();

    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });

    $("#sparkline-bar").sparkline('html', {
        type: 'bar',
        height: '35px',
        zeroAxis: false,
        barColor: App.getLayoutColorCode('green'),
        tooltipFormat: '{{offset:offset}}: {{value}}',
        tooltipValueLookups: {
            'offset': {
                0: getMonthString(-11),
                1: getMonthString(-10),
                2: getMonthString(-9),
                3: getMonthString(-8),
                4: getMonthString(-7),
                5: getMonthString(-6),
                6: getMonthString(-5),
                7: getMonthString(-4),
                8: getMonthString(-3),
                9: getMonthString(-2),
                10: getMonthString(-1),
                11: getMonthString(0)
            }
        }
    });

    function getMonthString(offset) {
        var date = new Date(),
            month = date.getMonth(),
            year = date.getFullYear();

        month += offset;
        if (month < 0) {
            year--;
            month = 12 + month;
        }

        switch (month) {
            case 0:
                return 'Jan ' + year;
                break;
            case 1:
                return 'Feb ' + year;
                break;
            case 2:
                return 'Mar ' + year;
                break;
            case 3:
                return 'Apr ' + year;
                break;
            case 4:
                return 'May ' + year;
                break;
            case 5:
                return 'Jun ' + year;
                break;
            case 6:
                return 'Jul ' + year;
                break;
            case 7:
                return 'Aug ' + year;
                break;
            case 8:
                return 'Sep ' + year;
                break;
            case 9:
                return 'Oct ' + year;
                break;
            case 10:
                return 'Nov ' + year;
                break;
            case 11:
                return 'Dec ' + year;
                break;
        }
    }

    $('.remove-selected').click(removeSelected);
});

function addRoleSearchToDataTables() {
    var $container = $('.dataTables_length'),
        $label = $('<label style="clear:both;width:200px"></label>'),
        $select = $('<input id="dt_role" name="dt_role" type="hidden" style="width:100%" class="select2-offscreen"/>');

    $label.append($select);
    $container.append($label);
    $select.select2({
        placeholder: "Select Users With ROLE",
        allowClear: true,
        data: user_roles
    });

    $select.on('change', function () {
        refreshTable();
    });
}

function addFnServerData() {
    var dt = $('#users-table').dataTable();
    dt.fnSettings().fnServerData = function (sSource, aoData, fnCallback) {
        var $date = $('#from_date'),
            date = null,
            $role = $('#dt_role'),
            role = null;

        if (typeof $date !== undefined) {
            date = $date.val();
        }

        if (typeof $role !== undefined) {
            role = $role.val();
        }

        aoData.push({"name": 'date_range', "value": date});
        aoData.push({"name": 'role_search', "value": role});

        $.ajax({
            "dataType": 'json',
            "type": "POST",
            "url": sSource,
            "data": aoData,
            "success": fnCallback
        });
    }
}

function addDateRangeToDataTables() {
    var $container = $('.dataTables_filter'),
        $wrapper = $('<div class="row" style="clear:both;width:350px;float:right;"></div>'),
        $from_label = $('<label class="col-md-12" style="float:none;"></label>'),
        $from_input_group = $('<div class="input-group" style="width:auto;"></div>'),
        $from_addon = $('<div class="input-group-addon"><i class="icon-calendar"></i></div>'),
        $from = $('<input class="form-control" id="from_date" name="from_date" type="text" rel="dt_datepicker" value="" placeholder="Date Range" />');

    $container.append($wrapper);
    $wrapper.append($from_label);

    $from_label.append($from_input_group);
    $from_input_group.append($from_addon).append($from);
    $from_label.append($from_input_group);

    $from_addon.click(function () {
        $from.click();
    });

    $($from).daterangepicker({
            ranges: {
                'All': [moment().subtract('years', 50), moment().endOf('year')],
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                'Last Week': [moment().startOf('week'), moment().endOf('week')],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
                'Last 3 Months': [moment().subtract('month', 3).startOf('month'), moment().subtract('month', 1).endOf('month')],
                'Last 6 Months': [moment().subtract('month', 6).startOf('month'), moment().subtract('month', 1).endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract('year', 1).startOf('year'), moment().subtract('year', 1).endOf('year')]
            }
        },
        function (start, end) {
            refreshTable();
        });
}

var checkedRows = [];
var user_form_modal;
var contextMenuDocumentClick = false;

function initContextMenus() {
    var $wrapper = $('<div id="contextMenu" class="context-menu dropdown clearfix"></div>');
    var $menu = $('<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;"></ul>');
    var $edit = $('<li><a tabindex="-1" href="#"><i class="icon-edit"></i> Edit</a></li>');
    var $view = $('<li><a tabindex="-1" href="#"><i class="icon-search"></i> View</a></li>');
    var $remove = $('<li><a tabindex="-1" href="#"><i class="icon-remove red"></i> Remove</a></li>');
    $wrapper.css({position: 'absolute', display: 'none'});
    $menu.append($edit).append($view).append($remove);
    $wrapper.append($menu);
    $('body').append($wrapper);
    $wrapper.hide();
}
function userTableDraw(dt) {
    updateRowIdsAndLogins();

    var $contextMenu = $("#contextMenu");
    var $rowClicked;

    $("body").unbind("contextmenu");
    $("body").on("contextmenu", "table tbody tr", function (e) {
        $('.cmlabel,.cmlabel-divider').remove();
        $rowClicked = $(this);
        $contextMenu.css({
            display: "block",
            left: e.pageX,
            top: e.pageY
        });
        var login = $(this).attr('data-login'),
            $label = $('<li class="cmlabel text-center" role="presentation" class="dropdown-header"><strong>' + login + '</strong></li>'),
            $sep = $('<li class="divider cmlabel-divider"></li>');
        $contextMenu.find('ul').prepend($sep);
        $contextMenu.find('ul').prepend($label);
        return false;
    });

    $contextMenu.unbind("click");
    $contextMenu.on("click", "a", function () {
        var uid = $rowClicked.attr('data-uid'),
            text = $(this).text().replace(' ', ''),
            login = $rowClicked.attr('data-login');

        if (text == 'Add') {
            addNewUser();
        } else if (text == 'Edit') {
            editUser(uid, login);
        } else if (text == 'View') {
            viewUser(uid, login);
        } else if (text == 'Remove') {
            removeUser(uid, login);
        }

        $('.cmlabel,.cmlabel-divider').remove();
        $contextMenu.hide();
    });

    if (!contextMenuDocumentClick) {
        // only perform this once to not interfere with dropdown clicks
        $(document).click(function () {
            $('.cmlabel,.cmlabel-divider').remove();
            $('.btn-group').removeClass('open');
            $contextMenu.hide();
        });
        contextMenuDocumentClick = true;
    }
}
function updateRowIdsAndLogins() {
    $('#users-table tbody tr').each(function () {
        var uid = null,
            login = null,
            el = $(this).find('[data-uid]'),
            el = $(this).find('[data-login]');

        uid = el.attr('data-uid');
        login = el.attr('data-login');
        el.removeAttr('data-uid');
        el.removeAttr('data-login');
        $(this).attr('data-uid', uid);
        $(this).attr('data-login', login);

        $(this).find('td').eq(0).addClass('no-context');
        $(this).find('td').eq(6).addClass('no-context');
    });

    $('.user-actions').each(function () {
        $(this).dropdown();
    });

    setTimeout(function () {
        initCheckAll();
    }, 250);
}
function initCheckAll() {
    $('.remove-selected').hide();
    var $header_check = $('#users-table thead tr').find('input[type=checkbox]'),
        $rows = $('#users-table tbody tr');

    $header_check.unbind('click');
    $header_check.click(function () {
        var header_checked = $(this).is(':checked');
        $.each($rows, function (ix, row) {
            var checkbox = $(row).find('input[type=checkbox]');

            if (header_checked) {
                if (!checkbox.is(':checked')) {
                    checkbox.prop('checked', true);
                    checkbox.parent().addClass('checked');
                }
            } else {
                if (checkbox.is(':checked')) {
                    checkbox.prop('checked', false);
                    checkbox.parent().removeClass('checked');
                }
            }
        });
        selectActions();
    });

    $.each($rows, function (ix, row) {
        var $row = $(row),
            checkbox = $row.find('input[type=checkbox]');
        checkbox.click(function () {
            selectActions();
        });
    });

    selectActions();
}
function selectActions() {
    var selected = getSelected(),
        total = $('#users-table tbody tr').find('input[type=checkbox]').length,
        $header_check = $('#users-table thead tr').find('input[type=checkbox]');

    if (selected.length == total) {
        $header_check.prop('checked', true);
        $header_check.parent().addClass('checked');
    } else {
        $header_check.prop('checked', false);
        $header_check.parent().removeClass('checked');
    }

    if (selected.length <= 0) {
        $('.remove-selected:visible').toggle(250);
    } else {
        $('.remove-selected:hidden').toggle(250);
    }
}
function getSelected() {
    checkedRows = [];
    $('#users-table tbody tr').each(function () {
        var $checkbox = $(this).find('input[type=checkbox]');
        if ($checkbox.is(':checked')) {
            checkedRows.push($(this));
        }
    });
    return checkedRows;
}
function addNewUser() {
    user_form_modal = $('#userModalAdd').modal({
        remote: ajax_url_add_user
    });
}
function editUser(uid, login) {
    user_form_modal = $('#userModalEdit').modal({
        remote: ajax_url_edit_user + '/' + uid
    });
}
function viewUser(uid, login) {
    window.location = ajax_url_view_user + '/' + login;
}
function removeUser(uid, login) {
    noty({
        text: 'Are you sure you want to remove the user "' + login + '"',
        type: 'confirm',
        layout: 'top',
        timeout: 2000,
        modal: true,
        buttons: [
            {
                addClass: 'btn btn-primary', text: 'Ok', onClick: function ($noty) {
                $noty.close();
                $.ajax({
                    url: ajax_url_remove_user + '/' + uid,
                    success: function (res) {
                        res = $.parseJSON(res);
                        if (res.success) {
                            noty({
                                type: 'success',
                                text: res.msg,
                                timeout: 3000
                            });
                        } else {
                            noty({
                                type: 'error',
                                text: res.msg,
                                timeout: 3000
                            });
                        }
                        refreshTable();
                    }
                });
            }
            },
            {
                addClass: 'btn btn-danger', text: 'Cancel', onClick: function ($noty) {
                $noty.close();
            }
            }
        ]
    });
}
function removeSelected() {
    var selected = getSelected(),
        ids = [],
        names = '';

    $.each(selected, function (ix, $row) {
        $row = $($row);
        console.log($row);
        var uid = $row.attr('data-uid'),
            login = $row.attr('data-login');
        ids.push(uid);
        if (names.length == 0) {
            names = '"' + login + '"';
        } else {
            names = names + ',"' + login + '"';
        }
    });

    noty({
        text: 'Are you sure you want to remove the ' + ids.length + ' users ' + names + '?',
        type: 'confirm',
        layout: 'top',
        timeout: 2000,
        modal: true,
        buttons: [
            {
                addClass: 'btn btn-primary', text: 'Ok', onClick: function ($noty) {
                $noty.close();
                $.ajax({
                    url: ajax_url_remove_selected_users,
                    type: 'post',
                    data: {'user_ids': ids},
                    success: function (res) {
                        res = $.parseJSON(res);
                        if (res.success) {
                            noty({
                                type: 'success',
                                text: res.msg,
                                timeout: 3000
                            });
                        } else {
                            noty({
                                type: 'error',
                                text: res.msg,
                                timeout: 3000
                            });
                        }
                        refreshTable();
                    }
                });
            }
            },
            {
                addClass: 'btn btn-danger', text: 'Cancel', onClick: function ($noty) {
                $noty.close();
            }
            }
        ]
    });
}
function userSavedCallback(res, status, new_user) {
    res = $.parseJSON(res);
    if (res.success) {
        noty({
            type: 'success',
            text: res.msg,
            timeout: 3000
        });
        if (user_form_modal) {
            $('.close-user-form').click();
        }
        user_form_modal = null;
        refreshTable();
    } else {
        noty({
            type: 'error',
            text: res.msg,
            timeout: 3000
        });
    }
}
function refreshTable() {
    var dt = $('#users-table').dataTable();
    dt.fnDraw();
}