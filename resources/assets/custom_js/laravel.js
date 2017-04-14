/*
Exemples : 
<a href="posts/2" data-method="delete" data-token="{{csrf_token()}}"> 
- Or, request confirmation in the process -
<a href="posts/2" data-method="delete" data-token="{{csrf_token()}}" data-confirm="Are you sure?">
*/


(function() {
 
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
 
})();