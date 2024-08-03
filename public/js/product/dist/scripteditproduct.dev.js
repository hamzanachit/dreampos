"use strict";

$(document).ready(function () {
  function validateForm() {
    var msg = '';
    $('.input-req').each(function () {
      if ($(this).val() === '') {
        msg += $(this).prev('label').text() + ' is required. <br>\n';
        $(this).css('background-color', '#F3C200');
      }
    });

    if (msg === '') {
      return true;
    } else {
      bootbox.alert({
        title: 'Action denied',
        message: msg
      });
      return false;
    }
  }

  $('#formEditProduct').on('submit', function (event) {
    event.preventDefault();

    if (validateForm()) {
      var formData = new FormData(this);
      $.ajax({
        url: 'http://localhost/erpp/public/products/edit',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function success(response) {
          alert('Product edit successfully');
        },
        error: function error(xhr, status, _error) {
          alert('An error occurred: ' + xhr.responseText);
        }
      });
    }
  });
  $('.input-req').on('focus', function () {
    $(this).css('background', 'white');
  });
  $('.deletebtn').on('click', function (e) {
    e.preventDefault();
    var productId = $(this).data('item-id');
    $('#productid').val(productId); // Set the product ID in a hidden input field

    $('#deleteproductModal').modal('show'); // Show the delete confirmation modal
  });
  $('#confirm').on('click', function () {
    var basePath = $('#basePath').val();
    var productId = $('#productid').val(); // Get the product ID from the hidden input

    $.ajax({
      url: basePath + '/products/delete',
      type: 'POST',
      data: JSON.stringify({
        id: productId
      }),
      contentType: 'application/json',
      success: function success(response) {
        if (response.status === 'success') {
          $('#deleteproductModal').modal('hide'); // Hide the modal

          Swal.fire({
            title: 'Success',
            text: response.message,
            icon: 'success',
            confirmButtonClass: 'btn btn-success',
            buttonsStyling: false
          }).then(function () {
            location.reload(); // Reload the page after success
          });
        } else {
          Swal.fire({
            title: 'Error',
            text: response.message,
            icon: 'error',
            confirmButtonClass: 'btn btn-danger',
            buttonsStyling: false
          });
        }
      },
      error: function error(xhr) {
        Swal.fire({
          title: 'Error',
          text: 'An error occurred: ' + xhr.status + ': ' + xhr.statusText,
          icon: 'error',
          confirmButtonClass: 'btn btn-danger',
          buttonsStyling: false
        });
      }
    });
  });
});