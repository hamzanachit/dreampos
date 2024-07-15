$(document).ready(function () {
  // console.log('Document ready function executed');

  // modal add
  $('#addcategory').on('click', function (e) {
    e.preventDefault();
    $('#addcategoryModal').modal('show');
  });

  // modal edit
  $('.editbtn').on('click', function (e) {
    // e.preventDefault();
    $('#editcategoryModal').modal('show');
    var idcategory = $(this).attr('data-item-id');
    var categoryName = $(this).attr('data-item-categoryName');
    var categoryCode = $(this).attr('data-item-categoryCode');
    var description = $(this).attr('data-item-description');
    // console.log(categoryCode);
    $('#idcategoryedit').val(idcategory);
    $('#categoryNameedit').val(categoryName);
    $('#categoryCodeedit').val(categoryCode);
    $('#descriptionedit').val(description);
    $('#idcategory').val(idcategory);
  });


  // Bind the form submission to the AJAX request
  $('#addcategoryForm').on('submit', function (event) {
    event.preventDefault();
    var basePath = $('#basePath').val();
    var formData = {
      name: $('#categoryname').val(),
      code: $('#categorycode').val(),
      description: $('#description').val(),
      image: '' // Placeholder for base64-encoded image data
    };

    // Convert image to base64 string
    var fileInput = $('#image')[0].files[0];
    if (fileInput) {
      var reader = new FileReader();
      reader.onload = function (e) {
        formData.image = e.target.result.split(',')[1]; // Extract base64 string from data URL
        sendFormData(formData);
      };
      reader.readAsDataURL(fileInput);
    } else {
      sendFormData(formData);
    }

    function sendFormData(formData) {
      $.ajax({
        url: basePath + '/ajaxcategory/add',
        type: 'POST',
        data: JSON.stringify(formData),
        contentType: 'application/json',
        success: function (response, textStatus, xhr) {
          console.log('AJAX request success');
          if (xhr.status === 200) {
            $('#addcategoryModal').modal('hide');
            $('#categoryname').val('');
            $('#categorycode').val('');
            $('#description').val('');
            $('#idcategory').val('');
            Swal.fire({
              title: 'Success',
              text: 'Category added successfully!',
              icon: 'success',
              confirmButtonClass: 'btn btn-success',
              buttonsStyling: false
            }).then(function () {
              location.reload();
            });
          } else {
            Swal.fire({
              title: 'Error',
              text: 'Failed to add category',
              icon: 'error',
              confirmButtonClass: 'btn btn-danger',
              buttonsStyling: false
            });
          }
        },
        error: function (xhr, textStatus, errorThrown) {
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
  });



  // edit category
  $('#editcategoryForm').on('submit', function (event) {
    event.preventDefault();
    var basePath = $('#basePath').val();
    var formData = {
      idcategory: $('#idcategory').val(),
      name: $('#categoryNameedit').val(),
      code: $('#categoryCodeedit').val(),
      description: $('#descriptionedit').val(),
      image: '' // Placeholder for base64-encoded image data
    };
    // console.log(name);
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
        success: function (response, textStatus, xhr) {
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
        error: function (xhr, textStatus, errorThrown) {
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
  });


























  // Remove background color on focus
  $('.input-req').on('focus', function () {
    $(this).css('background', 'white');
  });


  $('.deletebtn').on('click', function (e) {
    e.preventDefault();
    $('#deletecategoryModal').modal('show');
    //  var idcategory = deleteBtn.attr('data-item-id');
    var idcategory = $(this).attr('data-item-id');

    $('#categoryid').val(idcategory);

  });


  $('#confirm').on('click', function () {
    var basePath = $('#basePath').val();
    var deleteBtn = $(this);
    var idcategory = $('#categoryid').val();

    // console.log(idcategory);

    $.ajax({
      url: basePath + '/ajaxcategory/deletecategory',
      type: 'POST',
      data: JSON.stringify(idcategory),
      contentType: 'application/json',
      success: function (response, textStatus, xhr) {
        console.log('AJAX request success');
        if (xhr.status === 200) {
          $('#deletecategoryModal').modal('hide');
          $('#categoryid').val('');

          Swal.fire({
            title: 'Success',
            text: 'Category deleted successfully!',
            icon: 'success',
            confirmButtonClass: 'btn btn-success',
            buttonsStyling: false
          }).then(function () {
            location.reload(); // Reload the page after closing Swal
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
      error: function (xhr) {
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
  });


});