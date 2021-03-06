/*
 Exemples : 
 <a href="posts/2" data-method="delete" data-token="{{csrf_token()}}"> 
 - Or, request confirmation in the process -
 <a href="posts/2" data-method="delete" data-token="{{csrf_token()}}" data-confirm="Are you sure?">
 */


(function () {

    $('#assign_location').multiselect({
        onInitialized: function (select, container) {
            $(container).find('.multiselect').addClass('form-control').removeClass('btn btn-default');
            $(container).find('.multiselect-container').css({'max-height': '200px', 'overflow-y': 'auto'});
        }
    });
    $(".st_select_tally_user").select2();
    var laravel = {
        initialize: function () {
            this.methodLinks = $('a[data-method]');
            this.token = $('a[data-token]');
            this.registerEvents();
        },
        registerEvents: function () {
            this.methodLinks.on('click', this.handleMethod);
        },
        handleMethod: function (e) {
            var link = $(this);
            var httpMethod = link.data('method').toUpperCase();
            var form;

            // If the data-method attribute is not PUT or DELETE,
            // then we don't know what to do. Just ignore.
            if ($.inArray(httpMethod, ['PUT', 'DELETE']) === -1) {
                return;
            }

            // Allow user to optionally provide data-confirm="Are you sure?"
            if (link.data('confirm')) {
                if (!laravel.verifyConfirm(link)) {
                    return false;
                }
            }

            form = laravel.createForm(link);
            form.submit();

            e.preventDefault();
        },
        verifyConfirm: function (link) {
            $('#confirm').modal({backdrop: 'static', keyboard: false})
                    .on('click', '#delete-btn', function () {
                        password = $(this).closest('.modal-content').find('.modal-body #cpassword').val();
                        if ($.trim(password) != '') {
                            form = laravel.createForm(link, password);
                            form.submit();
                        }else{
                            if (password == '') {
                                form = laravel.createForm(link, password);
                                form.submit();
                            }
                        }
                    })
            return false;
        },
        createForm: function (link, password) {
            var form =
                    $('<form>', {
                        'method': 'POST',
                        'action': link.attr('href')
                    });

            var token =
                    $('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': link.data('token')
                    });

            var hiddenInput =
                    $('<input>', {
                        'name': '_method',
                        'type': 'hidden',
                        'value': link.data('method')
                    });

            var passwordInput =
                    $('<input>', {
                        'name': 'password',
                        'type': 'hidden',
                        'value': password
                    });
            return form.append(token, hiddenInput, passwordInput)
                    .appendTo('body');
        }
    };

    laravel.initialize();

    $(document).on('change', '#cuser_location', function (event) {
        event.preventDefault();
        $(this).closest('form').submit();
    });

    $(document).on('click', '.st_download_collection_u_list', function (event) {
        event.preventDefault();
        var search_text = $('#st_collection_user_form').find('input[name="search"]').val();
        var location = $('#st_collection_user_form').find('select[name="location"]').val();
        var territory = $('#st_collection_user_form').find('select[name="territory_filter"]').val();
        var form =
                $('<form>', {
                    'method': 'POST',
                    'action': 'account/export_collection_user'
                });

        var token =
                $('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': $(this).data('token')
                });
        var locationInput =
                $('<input>', {
                    'name': 'location',
                    'type': 'hidden',
                    'value': location
                });
        var searchInput =
                $('<input>', {
                    'name': 'search',
                    'type': 'hidden',
                    'value': search_text
                });
        var territoryInput =
                $('<input>', {
                    'name': 'territory',
                    'type': 'hidden',
                    'value': territory
                });
        form.append(token, locationInput, searchInput, territoryInput).appendTo('body');
        form.submit();
    });
    $(document).on('change', '.st_select_tally_user', function (event) {
        var val = $(this).val();
        var elem = $(this);
        var flag = true;
        var lastselelem = $(elem).clone(true);
        var lastsel = $(lastselelem).attr('data-lastsel');
        $('#st-settle-container .st-settle-block').each(function () {
            if (!$(this).find('.st_select_tally_user').is($(elem)) && ($(this).find('.st_select_tally_user').val() != '' && $(elem).val() != '') && $(this).find('.st_select_tally_user').val() == $(elem).val()) {
                flag = false;
            }

        });
        var block = $(this).closest('.st-settle-block').find('.settle-input-elem');
        var block_narration = $(this).closest('.st-settle-block').find('.narration-input-elem');
        if (flag) {
            if ($(this).val() != '') {
                $(this).attr('data-lastsel', $(this).val());
                $(block).html('<input class="form-control" placeholder="Amount" name="settle_amount[' + $(this).val() + ']" value="" onkeypress=" return numbersOnly(this,event,false,false);" type="text">');
                $(block_narration).html('<input class="form-control" placeholder="Narration" name="narration[' + $(this).val() + ']" value="" type="text">');
            } else {
                $(block).html('<input class="form-control" placeholder="Amount" name="settle_amount" onkeypress=" return numbersOnly(this,event,false,false);" value="" type="text">');
                $(block_narration).html('<input class="form-control" placeholder="Narration" name="narration" value="" type="text">');
            }
        } else {
            alert('Already Selected');
            var block = $(elem).closest('.st-settle-block');
            var options = $('#st_select_tally_user_master').html();
            var e = '<select data-lastsel="' + lastsel + '" class="st_select_tally_user form-control" name="tally_users[]">' + options +
                    '</select>';
            $(block).find('.col-md-3').first().html(e);
            $(block).find('.st_select_tally_user').val(lastsel);
            $(block).find('.st_select_tally_user').find('select option[value="' + lastsel + '"]').attr("selected", true);
            $(block).find('.st_select_tally_user').select2();
        }
        var element = $('#st-settle-container').find('.st-settle-block').last();
        if (val > 0) {
            $(elem).closest('.st-settle-block').addClass("temp_tally_user");
        } else {
            $(elem).closest('.st-settle-block').removeClass("temp_tally_user");
        }
        $(element).find(".add-tally_u").trigger("click");
    });
    $(document).on('click', '.add-tally_u', function (event) {
        event.preventDefault();
        var options = $('#st_select_tally_user_master').html();
        var element = '<div class="st-settle-block"><div class="col-md-12" style="margin:10px 0;padding:0">' +
                '<div class="col-md-3">' +
                '<select data-lastsel="" class="st_select_tally_user form-control" name="tally_users[]">' + options +
                '</select>' +
                '</div>' +
                '<div class="col-md-3 settle-input-elem">' +
                '<input class="form-control" placeholder="Amount" name="settle_amount[]" value="" onkeypress=" return numbersOnly(this,event,false,false);" type="text">' +
                '</div>' +
                '<div class="col-md-4 narration-input-elem">' +
                '<input class="form-control" placeholder="Narration" name="narration[]" value=""  type="text">' +
                '</div>' +
                '<div class="col-md-1 action_btn">' +
                '<a href="javascript:void(0)" style="border-bottom:none" class="btn add-tally_u st-border-bottom-none"><i class="fa fa-plus"></i></a>' +
                '</div>' +
                '</div></div>';
        $('#st-settle-container').append(element);
        var element = $('#st-settle-container').find('.st-settle-block').last();
        $('#st-settle-container .st-settle-block').each(function () {
            var tval = $(this).find('.st_select_tally_user').val();
//            console.log(tval);
            if (tval != '') {
                if ($(element).find('.st_select_tally_user').find('option[value=' + tval + ']').length > 0)
                {
                    $(element).find('.st_select_tally_user').find('option[value=' + tval + ']').remove();
                }
            }
        });
        $(element).find('.del-tally_u').remove();
        $(element).find('.action_btn').append('<a href="javascript:void(0)" style="border-bottom:none" class="btn del-tally_u st-border-bottom-none"><i class="fa fa-trash-o"></i></a>');
        $(element).find('.st_select_tally_user').select2();
    });
    $(document).on('click', '#edit_receipt .del-tally_u', function (event) {
        event.preventDefault();
        $('#edit_receipt').find('#flash_message_div').css('display', 'none');
        var tval = $('#st-settle-container').find('.st-settle-block').length;
        var last = $('#edit_receipt').find('#user_type').val();
        if (last == "admin") {
            $(this).closest('.st-settle-block').remove();
        } else if (last == "account") {
            if (tval > 1) {
                $(this).closest('.st-settle-block').remove();
            } else {
                var error_msg = '<div class="alert alert-warning" id="flash_message_div">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: relative;"><span aria-hidden="true">x</span></button>' +
                        '<p>Receipt could not delete.</p>' +
                        '</div>';
                $('#edit_receipt').prepend(error_msg);
            }
        }
    });
    $(document).on('click', '#edit_receipt .delete_customer_receipts', function (event) {
        event.preventDefault();
        $('#edit_receipt').find('#flash_message_div').css('display', 'none');
        var tval = $('#st-settle-container').find('.st-settle-block').length;
        if (tval > 1) {
            $(this).closest('.st-settle-block').remove();
        } else {
            var last = $('#edit_receipt').find('#user_type').val();
            if (last == "account") {
                var error_msg = '<div class="alert alert-warning" id="flash_message_div">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: relative;"><span aria-hidden="true">x</span></button>' +
                        '<p>Receipt could not delete.</p>' +
                        '</div>';
                $('#edit_receipt').prepend(error_msg);
            }
        }
    });
    $(document).on('click', '#add_receipt .del-tally_u', function (event) {
        event.preventDefault();
        var tval = $('#st-settle-container').find('.st-settle-block').length;
        if (tval > 1) {
            $(this).closest('.st-settle-block').remove();
        }
    });


    $(".st_select_tally_user_d").select2();
    $(document).on('change', '.st_select_tally_user_d', function (event) {
        var val = $(this).val();
        var elem = $(this);
        var flag = true;
        var lastselelem = $(elem).clone(true);
        var lastsel = $(lastselelem).attr('data-lastsel');
        $('#st-settle-container .st-settle-block').each(function () {
            if (!$(this).find('.st_select_tally_user_d').is($(elem)) && ($(this).find('.st_select_tally_user_d').val() != '' && $(elem).val() != '') && $(this).find('.st_select_tally_user_d').val() == $(elem).val()) {
                flag = false;
            }

        });
        var block = $(this).closest('.st-settle-block_d').find('.settle-input-elem');
        var block_narration_d = $(this).closest('.st-settle-block_d').find('.narration-input-elem');
        if (flag) {
            if ($(this).val() != '') {
                $(this).attr('data-lastsel', $(this).val());
                $(block).html('<input class="form-control" placeholder="Amount" name="settle_amount_d[' + $(this).val() + ']" value="" onkeypress=" return numbersOnly(this,event,false,false);" type="text">');
                $(block_narration_d).html('<input class="form-control" placeholder="Narration" name="narration_d[' + $(this).val() + ']" value="" type="text">');
            } else {
                $(block).html('<input class="form-control" placeholder="Amount" name="settle_amount_d" onkeypress=" return numbersOnly(this,event,false,false);" value="" type="text">');
                $(block_narration_d).html('<input class="form-control" placeholder="Narration" name="narration_d" value="" type="text">');
            }
        } else {
            alert('Already Selected');
            var block = $(elem).closest('.st-settle-block_d');
            var options = $('#st_select_tally_user_master').html();
            var e = '<select data-lastsel="' + lastsel + '" class="st_select_tally_user_d form-control" name="tally_users_d[]">' + options +
                    '</select>';
            $(block).find('.col-md-3').first().html(e);
            $(block).find('.st_select_tally_user_d').val(lastsel);
            $(block).find('.st_select_tally_user_d').find('select option[value="' + lastsel + '"]').attr("selected", true);
            $(block).find('.st_select_tally_user_d').select2();
        }
        var element = $('#st-settle-container_d').find('.st-settle-block_d').last();
        if (val > 0) {
            $(elem).closest('.st-settle-block_d').addClass("temp_tally_user_d");
        } else {
            $(elem).closest('.st-settle-block_d').removeClass("temp_tally_user_d");
        }
        $(element).find(".add-tally_d").trigger("click");
    });

    $(document).on('click', '.add-tally_d', function (event) {
        event.preventDefault();

        var options = $('#st_select_tally_user_master_d').html();
        var element = '<div class="st-settle-block_d"><div class="col-md-12" style="margin:10px 0;padding:0">' +
                '<div class="col-md-3">' +
                '<select data-lastsel="" class="st_select_tally_user_d form-control" name="tally_users_d[]">' + options +
                '</select>' +
                '</div>' +
                '<div class="col-md-3 settle-input-elem">' +
                '<input class="form-control" placeholder="Amount" name="settle_amount_d[]" value="" onkeypress=" return numbersOnly(this,event,false,false);" type="text">' +
                '</div>' +
                '<div class="col-md-4 narration-input-elem">' +
                '<input class="form-control" placeholder="Narration" name="narration_d[]" value=""  type="text">' +
                '</div>' +
                '<div class="col-md-1 action_btn">' +
                '<a href="javascript:void(0)" style="border-bottom:none" class="btn add-tally_d st-border-bottom-none"><i class="fa fa-plus"></i></a>' +
                '</div>' +
                '</div></div>';
        $('#st-settle-container_d').append(element);
        var element = $('#st-settle-container_d').find('.st-settle-block_d').last();
        $('#st-settle-container_d .st-settle-block_d').each(function () {
            var tval = $(this).find('.st_select_tally_user_d').val();
//            console.log(tval);
            if (tval != '') {
                if ($(element).find('.st_select_tally_user_d').find('option[value=' + tval + ']').length > 0)
                {
                    $(element).find('.st_select_tally_user_d').find('option[value=' + tval + ']').remove();
                }
            }
        });
        $(element).find('.del-tally_d').remove();
        $(element).find('.action_btn').append('<a href="javascript:void(0)" style="border-bottom:none" class="btn del-tally_d st-border-bottom-none"><i class="fa fa-trash-o"></i></a>');
        $(element).find('.st_select_tally_user_d').select2();
    });

    $(document).on('click', '#edit_receipt .del-tally_d', function (event) {

        event.preventDefault();
        $('#edit_receipt').find('#flash_message_div').css('display', 'none');
        var tval = $('#st-settle-container_d').find('.st-settle-block_d').length;
        var last = $('#edit_receipt').find('#user_type').val();
        console.log(tval);
        if (last == "admin") {
            if (tval > 1) {
                $(this).closest('.st-settle-block_d').remove();
            }
        } else if (last == "account") {
            if (tval > 1) {
                $(this).closest('.st-settle-block_d').remove();
            } else {
                var error_msg = '<div class="alert alert-warning" id="flash_message_div">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: relative;"><span aria-hidden="true">x</span></button>' +
                        '<p>Receipt could not delete.</p>' +
                        '</div>';
                $('#edit_receipt').prepend(error_msg);
            }
        }
    });


    $(document).on('click', '#add_receipt .del-tally_d', function (event) {
        event.preventDefault();
        var tval = $('#st-settle-container_d').find('.st-settle-block_d').length;
        if (tval > 1) {
            $(this).closest('.st-settle-block_d').remove();
        }
    });



})();


