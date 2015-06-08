$(document).ready(function() {

  $('#btnDelete').click(function() {
    bootbox.confirm("Are you sure want to delete?", function(result) {
      alert("Confirm result: " + result);
    });
  });
});