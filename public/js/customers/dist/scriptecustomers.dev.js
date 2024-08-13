"use strict";

$(document).ready(function () {
  // console.log('Document ready function executed');
  // modal add
  $('#addCustomer').on('click', function (e) {
    // alert();
    e.preventDefault();
    $('#addCustomerModal').modal('show');
  });
  $('#addCustomerform').on('submit', function (event) {
    event.preventDefault();
    var basePath = $('#basePath').val(); // Gather form data

    var formData = {
      name: $('#Name').val(),
      email: $('#email').val(),
      phone: $('#phone').val(),
      ICE: $('#ICE').val(),
      address: $('#address').val(),
      Bank: $('#Bank').val(),
      Note: $('#Note').val()
    };
    sendFormData(formData);

    function sendFormData(formData) {
      $.ajax({
        url: basePath + '/customers/add',
        // Ensure this URL matches your route
        type: 'POST',
        data: JSON.stringify(formData),
        contentType: 'application/json',
        success: function success(response, textStatus, xhr) {
          console.log('AJAX request success');

          if (response.success) {
            $('#addCustomerModal').modal('hide'); // Adjust modal ID as needed
            // Clear form fields

            $('#name').val('');
            $('#email').val('');
            $('#phone').val('');
            $('#ICE').val('');
            $('#address').val('');
            $('#Bank').val('');
            $('#Note').val('');
            Swal.fire({
              title: 'Success',
              text: response.message,
              icon: 'success',
              confirmButtonClass: 'btn btn-success',
              buttonsStyling: false
            }).then(function () {
              location.reload();
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
        error: function error(xhr, textStatus, errorThrown) {
          console.log('AJAX request error');
          Swal.fire({
            title: 'Error',
            text: 'An error occurred: ' + xhr.status + ': ' + xhr.statusText,
            icon: 'error',
            confirmButtonClass: 'btn btn-danger',
            buttonsStyling: false
          });
        }
      });
    }
  }); // edit category

  $('#editcategoryForm').on('submit', function (event) {
    event.preventDefault();
    var basePath = $('#basePath').val();
    var formData = {
      idcategory: $('#idcategory').val(),
      name: $('#categoryNameedit').val(),
      code: $('#categoryCodeedit').val(),
      description: $('#descriptionedit').val(),
      image: '' // Placeholder for base64-encoded image data

    }; // console.log(name);

    var fileInput = $('#imageedit')[0].files[0];

    if (fileInput) {
      var reader = new FileReader();

      reader.onload = function (e) {
        formData.image = e.target.result.split(',')[1];
        sendFormData(formData);
      };

      reader.readAsDataURL(fileInput);
    } else {
      sendFormData(formData);
    }

    function sendFormData(formData) {
      $.ajax({
        url: basePath + '/ajaxcategory/editcategory',
        type: 'POST',
        data: JSON.stringify(formData),
        contentType: 'application/json',
        success: function success(response, textStatus, xhr) {
          console.log('AJAX request success');

          if (xhr.status === 200) {
            $('#addcategoryModal').modal('hide');
            $('#categoryNameedit').val('');
            $('#categoryCodeedit').val('');
            $('#descriptionedit').val('');
            $('#image').val('');
            Swal.fire({
              title: 'Success',
              text: 'Category edited successfully!',
              icon: 'success',
              confirmButtonClass: 'btn btn-success',
              buttonsStyling: false
            }).then(function () {
              location.reload();
            });
          } else {
            Swal.fire({
              title: 'Error',
              text: 'Failed to edited category',
              icon: 'error',
              confirmButtonClass: 'btn btn-danger',
              buttonsStyling: false
            });
          }
        },
        error: function error(xhr, textStatus, errorThrown) {
          console.log('AJAX request error');
          Swal.fire({
            title: 'Error',
            text: 'An error occurred: ' + xhr.status + ': ' + xhr.statusText,
            icon: 'error',
            confirmButtonClass: 'btn btn-danger',
            buttonsStyling: false
          });
        }
      });
    }
  }); // Remove background color on focus

  $('.input-req').on('focus', function () {
    $(this).css('background', 'white');
  }); // $('.deletebtn').on('click', function (e) {
  //     e.preventDefault();
  //     $('#deletecategoryModal').modal('show');
  //     //  var idcategory = deleteBtn.attr('data-item-id');
  //     var idcategory = $(this).attr('data-item-id');
  //     $('#categoryid').val(idcategory);
  // });
  //  edit part

  $('.editbtn').on('click', function (e) {
    e.preventDefault();
    var id = $(this).data('item-id');
    var name = $(this).data('item-name');
    var email = $(this).data('item-email');
    var phone = $(this).data('item-phone');
    var ICE = $(this).data('item-ICE');
    var address = $(this).data('item-address');
    var bank = $(this).data('item-bank');
    var note = $(this).data('item-note'); // Populate modal form fields

    $('#customerId').val(id);
    $('#editName').val(name);
    $('#editEmail').val(email);
    $('#editPhone').val(phone);
    $('#editICE').val(ICE);
    $('#editAddress').val(address);
    $('#editBank').val(bank);
    $('#editNote').val(note); // Show the modal

    $('#editCustomerModal').modal('show');
  });
  $('#editCustomerForm').on('submit', function (event) {
    event.preventDefault();
    var basePath = $('#basePath').val(); // Gather form data

    var formData = {
      id: $('#customerId').val(),
      name: $('#editName').val(),
      email: $('#editEmail').val(),
      phone: $('#editPhone').val(),
      ICE: $('#editICE').val(),
      address: $('#editAddress').val(),
      Bank: $('#editBank').val(),
      Note: $('#editNote').val()
    };
    $.ajax({
      url: basePath + '/customers/edit',
      // Ensure this URL matches your route
      type: 'POST',
      data: JSON.stringify(formData),
      contentType: 'application/json',
      success: function success(response, textStatus, xhr) {
        if (response.success) {
          $('#editCustomerModal').modal('hide'); // Adjust modal ID as needed
          // Clear form fields if necessary

          $('#customerId').val('');
          $('#editName').val('');
          $('#editEmail').val('');
          $('#editPhone').val('');
          $('#editICE').val('');
          $('#editAddress').val('');
          $('#editBank').val('');
          $('#editNote').val('');
          Swal.fire({
            title: 'Success',
            text: response.message,
            icon: 'success',
            confirmButtonClass: 'btn btn-success',
            buttonsStyling: false
          }).then(function () {
            location.reload();
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
      error: function error(xhr, textStatus, errorThrown) {
        Swal.fire({
          title: 'Error',
          text: 'An error occurred: ' + xhr.status + ': ' + xhr.statusText,
          icon: 'error',
          confirmButtonClass: 'btn btn-danger',
          buttonsStyling: false
        });
      }
    });
  }); // delete part 

  $('.deletebtn').on('click', function (e) {
    e.preventDefault();
    $('#deleteCustomerModal').modal('show');
    var idrow = $(this).data('item-id'); // console.log(idrow);

    $('#idrow').val(idrow);
  });
  $('.cancelbtn').on('click', function (e) {
    $('#deleteCustomerModal').modal('hide');
    e.preventDefault();
  });
  $('#deleteCustomerForm').on('submit', function (event) {
    event.preventDefault();
    var basePath = $('#basePath').val();
    var formData = {
      id: $('#idrow').val()
    };
    $.ajax({
      url: basePath + '/customers/delete',
      // Ensure this URL matches your route
      type: 'POST',
      data: JSON.stringify(formData),
      contentType: 'application/json',
      success: function success(response, textStatus, xhr) {
        if (response.success) {
          $('#deleteCustomerModal').modal('hide'); // Adjust modal ID as needed
          // Clear form fields if necessary

          $('#idrow').val('');
          Swal.fire({
            title: 'Success',
            text: response.message,
            icon: 'success',
            confirmButtonClass: 'btn btn-success',
            buttonsStyling: false
          }).then(function () {
            location.reload();
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
      error: function error(xhr, textStatus, errorThrown) {
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