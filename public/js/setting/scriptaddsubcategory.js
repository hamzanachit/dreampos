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
        var idsubcategory = $(this).attr('data-item-id');
        var categoryname = $(this).attr('data-item-categoryname');
        var SubCategoryName = $(this).attr('data-item-SubCategoryName');
        var description = $(this).attr('data-item-description');
        // console.log(categoryname);
        $('#idcategoryedit').val(idsubcategory);
        $('#categoryNameedit').val(categoryname);
        $('#SubCategoryNameedit').val(SubCategoryName);
        $('#descriptionedit').val(description);
        $('#idsubcategory').val(idsubcategory);
    });


    // Bind the form submission to the AJAX request
    $('#addsubcategoryForm').on('submit', function (event) {
        event.preventDefault();
        var basePath = $('#basePath').val();
        var formData = {
            subcategoryname: $('#subcategoryname').val(),
            categoryname: $('#categoryname').val(),
            description: $('#description').val(),
        };
        sendFormData(formData);

        function sendFormData(formData) {
            $.ajax({
                url: basePath + '/ajaxcategory/addsubcategory',
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                success: function (response, textStatus, xhr) {
                    console.log('AJAX request success');
                    if (xhr.status === 200) {
                        $('#addcategoryModal').modal('hide');
                        $('#subcategoryname').val('');
                        $('#description').val('');
                        $('#idcategory').val('');
                        Swal.fire({
                            title: 'Success',
                            text: 'Sub Category added successfully!',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-success',
                            buttonsStyling: false
                        }).then(function () {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to add sub category',
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



    // edit sub category
    $('#editsubcategoryForm').on('submit', function (event) {
        event.preventDefault();
        var basePath = $('#basePath').val();
        var formData = {
            idsubcategory: $('#idsubcategory').val(),
            categoryNameedit: $('#categoryNameedit').val(),
            SubCategoryNameedit: $('#SubCategoryNameedit').val(),
            description: $('#descriptionedit').val(),
        };
        sendFormData(formData);

        function sendFormData(formData) {
            $.ajax({
                url: basePath + '/ajaxcategory/editsubcategory',
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
                            text: 'Sub Category edited successfully!',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-success',
                            buttonsStyling: false
                        }).then(function () {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to edited Sub category',
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




















    $('.deletebtn').on('click', function (e) {
        e.preventDefault();
        $('#deletecategoryModal').modal('show');
        //  var idcategory = deleteBtn.attr('data-item-id');
        var idsubcategorydelete = $(this).attr('data-item-id');
        // console.log(idsubcategorydelete);
        $('#idsubcategorydelete').val(idsubcategorydelete);

    });

    $('#confirm').on('click', function () {
        var basePath = $('#basePath').val();
        var deleteBtn = $(this);
        var idsubcategorydelete = $('#idsubcategorydelete').val();
        console.log(idsubcategorydelete);
        $.ajax({
            url: basePath + '/ajaxcategory/deleteSubCategory',
            type: 'POST',
            data: JSON.stringify(idsubcategorydelete),
            contentType: 'application/json',
            success: function (response, textStatus, xhr) {
                console.log('AJAX request success');
                if (xhr.status === 200) {
                    $('#deletecategoryModal').modal('hide');
                    $('#idsubcategorydelete').val('');

                    Swal.fire({
                        title: 'Success',
                        text: 'Sub Category deleted successfully!',
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