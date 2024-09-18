$(document).ready(function () {
    const basePath = $('#basePath').val();

    // Show the Add Translation Modal
    $('#addTranslation').click(function (e) {
        e.preventDefault();
        $('#addTranslationModal').modal('show');
    });

    // Show the Edit Translation Modal with data
    $(document).on('click', '.editbtn', function (e) {
        e.preventDefault();
        const id = $(this).data('item-id');
        const origin = $(this).data('item-origin');
        const french = $(this).data('item-french');
        const arabic = $(this).data('item-arabic');

        $('#translationId').val(id);
        $('#originEdit').val(origin);
        $('#frenchEdit').val(french);
        $('#arabicEdit').val(arabic);

        $('#editTranslationModal').modal('show');
    });

    // Show the Delete Translation Modal with data
    $(document).on('click', '.deletebtn', function (e) {
        e.preventDefault();
        const id = $(this).data('item-id');
        $('#translationIdDelete').val(id);

        $('#deleteTranslationModal').modal('show');
    });

    // Add Translation Form Submit
    $('#addTranslationForm').submit(function (e) {
        e.preventDefault();

        const origin = $('#origin').val();
        const french = $('#french').val();
        const arabic = $('#arabic').val();

        $.ajax({
            type: 'POST',
            url: `${basePath}/ajaxcategory/addtranslation`,
            data: {
                origin,
                french,
                arabic
            },
            success: function (response, textStatus, xhr) {
                if (xhr.status === 200) {
                    $('#addTranslationModal').modal('hide');
                    $('#origin').val('');
                    $('#french').val('');
                    $('#arabic').val('');
                    Swal.fire({
                        title: 'Success',
                        text: 'Translation added successfully!',
                        icon: 'success',
                        confirmButtonClass: 'btn btn-success',
                        buttonsStyling: false
                    }).then(function () {
                        location.reload(); // Reload the page to update the table
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to add translation.',
                        icon: 'error',
                        confirmButtonClass: 'btn btn-danger',
                        buttonsStyling: false
                    });
                }
            },
            error: function (xhr, textStatus, errorThrown) {
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

    // Edit Translation Form Submit
    $('#editTranslationForm').submit(function (e) {
        e.preventDefault();

        const id = $('#translationId').val();
        const origin = $('#originEdit').val();
        const french = $('#frenchEdit').val();
        const arabic = $('#arabicEdit').val();

        $.ajax({
            type: 'POST',
            url: `${basePath}/ajaxcategory/edittranslation`,
            data: {
                id,
                origin,
                french,
                arabic
            },
            success: function (response, textStatus, xhr) {
                if (xhr.status === 200) {
                    $('#editTranslationModal').modal('hide');
                    Swal.fire({
                        title: 'Success',
                        text: 'Translation edited successfully!',
                        icon: 'success',
                        confirmButtonClass: 'btn btn-success',
                        buttonsStyling: false
                    }).then(function () {
                        location.reload(); // Reload the page to update the table
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to edit translation.',
                        icon: 'error',
                        confirmButtonClass: 'btn btn-danger',
                        buttonsStyling: false
                    });
                }
            },
            error: function (xhr, textStatus, errorThrown) {
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

    // Confirm Delete Translation
    $('#confirmDeleteTranslation').click(function () {
        const id = $('#translationIdDelete').val();

        $.ajax({
            type: 'POST',
            url: `${basePath}/ajaxcategory/deletetranslation`,
            data: {
                id
            },
            success: function (response, textStatus, xhr) {
                if (xhr.status === 200) {
                    $('#deleteTranslationModal').modal('hide');
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Translation deleted successfully!',
                        icon: 'success',
                        confirmButtonClass: 'btn btn-success',
                        buttonsStyling: false
                    }).then(function () {
                        location.reload(); // Reload the page to update the table
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message || 'Failed to delete translation.',
                        icon: 'error',
                        confirmButtonClass: 'btn btn-danger',
                        buttonsStyling: false
                    });
                }
            },
            error: function (xhr) {
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

    // Select/Deselect All Checkboxes
    $('#select-all').click(function () {
        $('tbody input[type="checkbox"]').prop('checked', this.checked);
    });

    // Validate Form (Optional - you can add more validation as needed)
    function validateForm() {
        let valid = true;
        $('.form-control').each(function () {
            if ($(this).val() === '') {
                valid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        return valid;
    }
});