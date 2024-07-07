"use strict";

$(document).ready(function () {
  console.log('Document ready function executed'); // Show the modal when the "Add Category" button is clicked

  $('#addcategory').on('click', function (e) {
    e.preventDefault();
    console.log('#addcategory button clicked');
    $('#addcategoryModal').modal('show');
  }); // Bind the form submission to the AJAX request

  $('#addcategoryForm').on('submit', function (event) {
    event.preventDefault();
    console.log('#addcategoryForm submitted');
    var basePath = $('#basePath').val();
    var formData = {
      name: $('#categoryname').val(),
      code: $('#categorycode').val(),
      description: $('#description').val()
    };
    $.ajax({
      url: basePath + '/ajaxcategory/add',
      type: 'POST',
      data: JSON.stringify(formData),
      contentType: 'application/json',
      success: function success(response, textStatus, xhr) {
        console.log('AJAX request success');

        if (xhr.status === 200) {
          $('#addcategoryModal').modal('hide');
          $('#categoryname').val('');
          $('#categorycode').val('');
          $('#description').val('');
          showCustomAlert('Category added successfully!');
        } else {
          alert('Error: ' + response.message);
        }
      },
      error: function error(xhr) {
        console.log('AJAX request error');
        alert('An error occurred: ' + xhr.status + ': ' + xhr.statusText);
      }
    });
  }); // Remove background color on focus

  $('.input-req').on('focus', function () {
    $(this).css('background', 'white');
  });
});