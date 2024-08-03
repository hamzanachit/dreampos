"use strict";

$(document).on('click', '#Upload', function (e) {
  e.preventDefault();
  var form = document.getElementById('import-form');
  var formData = new FormData(form);
  var basePath = $('#basePath').val();
  fetch(basePath + '/products/process-import', {
    method: 'POST',
    body: formData
  }).then(function (response) {
    if (!response.ok) {
      return response.text().then(function (text) {
        console.error('Error response:', text);
        throw new Error('Network response was not ok');
      });
    }

    return response.json();
  }).then(function (data) {
    if (data.success) {
      Swal.fire({
        title: 'Success',
        text: data.message,
        icon: 'success',
        confirmButtonClass: 'btn btn-success',
        buttonsStyling: false
      }).then(function () {
        location.reload();
      });
    } else {
      Swal.fire({
        title: 'Error',
        text: data.message,
        icon: 'error',
        confirmButtonClass: 'btn btn-danger',
        buttonsStyling: false
      });
    }
  })["catch"](function (error) {
    console.error('Error:', error.message);
    Swal.fire({
      title: 'Error',
      text: 'An error occurred while importing products: ' + error.message,
      icon: 'error',
      confirmButtonClass: 'btn btn-danger',
      buttonsStyling: false
    });
  });
});