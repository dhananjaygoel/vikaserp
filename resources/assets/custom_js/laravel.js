/*
Exemples : 
<a href="posts/2" data-method="delete" data-token="{{csrf_token()}}"> 
- Or, request confirmation in the process -
<a href="posts/2" data-method="delete" data-token="{{csrf_token()}}" data-confirm="Are you sure?">
*/


(function() {
 
  $(".st_select_tally_user").select2();
  var laravel = {
    initialize: function() {
      this.methodLinks = $('a[data-method]');
      this.token = $('a[data-token]');
      this.registerEvents();
    },
 
    registerEvents: function() {
      this.methodLinks.on('click', this.handleMethod);
    },
 
    handleMethod: function(e) {
      var link = $(this);
      var httpMethod = link.data('method').toUpperCase();
      var form;
 
      // If the data-method attribute is not PUT or DELETE,
      // then we don't know what to do. Just ignore.
      if ( $.inArray(httpMethod, ['PUT', 'DELETE']) === - 1 ) {
        return;
      }
 
      // Allow user to optionally provide data-confirm="Are you sure?"
      if ( link.data('confirm') ) {
        if ( ! laravel.verifyConfirm(link) ) {
          return false;
        }
      }
 
      form = laravel.createForm(link);
      form.submit();
 
      e.preventDefault();
    },
 
    verifyConfirm: function(link) {
      $('#confirm').modal({ backdrop: 'static', keyboard: false })
        .on('click', '#delete-btn', function(){
          password = $(this).closest('.modal-content').find('.modal-body #cpassword').val();
          if($.trim(password)!=''){
            form = laravel.createForm(link,password);
            form.submit();
          }
      })
      return false;       
    },
 
    createForm: function(link,password) {
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
      return form.append(token, hiddenInput,passwordInput)
                 .appendTo('body');
    }
  };
 
  laravel.initialize();

  $(document).on('change','#cuser_location',function(event){
      event.preventDefault();
      $(this).closest('form').submit();
  });

  $(document).on('click','.st_download_collection_u_list',function(event){
    event.preventDefault();
    var search_text = $('#st_collection_user_form').find('input[name="search"]').val();
    var location = $('#st_collection_user_form').find('select[name="location"]').val();
    var form = 
    $('<form>', {
      'method': 'POST',
      'action': 'export_collection_user'
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
    form.append(token, locationInput,searchInput).appendTo('body');
    form.submit();                 
  });
  $(document).on('change','.st_select_tally_user',function(event){
    var val = $(this).val();
    var elem = $(this);
    var flag = true;
    var lastselelem = $(elem).clone(true);
    var lastsel = $(lastselelem).attr('data-lastsel');
    $('#st-settle-container .st-settle-block').each(function(){
      if(!$(this).find('.st_select_tally_user').is($(elem)) && ($(this).find('.st_select_tally_user').val()!='' && $(elem).val()!='' ) && $(this).find('.st_select_tally_user').val()==$(elem).val()){
        flag = false;
      }
    });
    var block = $(this).closest('.st-settle-block').find('.settle-input-elem');
    if(flag)
    {
      if($(this).val()!='')
      {
        // $.ajax({
        //   url: 'get-p-settle-amount',
        //   type: 'get',
        //   data: {'user_id':$(this).val()},
        //   success:function(data){

        //   }
        // });
        $(this).attr('data-lastsel',$(this).val());
        $(block).html('<input class="form-control" placeholder="Settle Amount" name="settle_amount['+$(this).val()+']" value="123456" type="text">');
      }else{
        $(block).html('<input class="form-control" placeholder="Settle Amount" name="settle_amount" value="" type="text">');
      }
    }else{
      alert('Already Selected');  
      var block = $(elem).closest('.st-settle-block');
      var options = $('#st_select_tally_user_master').html();
      var e = '<select data-lastsel="'+lastsel+'" class="st_select_tally_user form-control" name="tally_users[]">'+ options +                
            '</select>';
      $(block).find('.col-md-3').first().html(e);      
      $(block).find('.st_select_tally_user').val(lastsel);
      $(block).find('.st_select_tally_user').find('select option[value="'+lastsel+'"]').attr("selected",true);
      $(block).find('.st_select_tally_user').select2();      
    }
  });
  $(document).on('click','.add-tally_u',function(event){
    event.preventDefault();
    var options = $('#st_select_tally_user_master').html();
    var element = '<div class="st-settle-block"><div class="col-md-12" style="margin:10px 0;padding:0">'+
        '<div class="col-md-3">'+
            '<select data-lastsel="" class="st_select_tally_user form-control" name="tally_users[]">'+ options +                
            '</select>'+
        '</div>'+
        '<div class="col-md-4 settle-input-elem">'+
            '<input class="form-control" placeholder="Settle Amount" name="settle_amount" value="" type="text">'+
        '</div>'+
        '<div class="col-md-1 action_btn">'+
            '<a href="javascript:void(0)" style="border-bottom:none" class="btn add-tally_u st-border-bottom-none"><i class="fa fa-plus"></i></a>'+
        '</div>'+
    '</div></div>';
    $('#st-settle-container').append(element);
    var element = $('#st-settle-container').find('.st-settle-block').last();
    $('#st-settle-container .st-settle-block').each(function(){
      var tval = $(this).find('.st_select_tally_user').val();
      console.log(tval);
      if(tval!=''){
        if($(element).find('.st_select_tally_user').find('option[value='+tval+']').length > 0)
        {
          $(element).find('.st_select_tally_user').find('option[value='+tval+']').remove();
        }
      }        
    });
    $(element).find('.del-tally_u').remove();
    $(element).find('.action_btn').append('<a href="javascript:void(0)" style="border-bottom:none" class="btn del-tally_u st-border-bottom-none"><i class="fa fa-trash-o"></i></a>');
    $(element).find('.st_select_tally_user').select2();
  });
  $(document).on('click','.del-tally_u',function(event){
    event.preventDefault();
    $(this).closest('.st-settle-block').remove();
  });  
})();