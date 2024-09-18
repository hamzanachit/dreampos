$(document).ready(function () {
    const basePath = $('#basePath').val();

    // Show the Add Translation Modal
    $('#addTranslation').click(function () {
        $('#addTranslationModal').modal('show');
    });

    // Show the Edit Translation Modal with data
    $(document).on('click', '.editbtn', function () {
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
    $(document).on('click', '.deletebtn', function () {
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
            success: function (response) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Translation added successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('#addTranslationModal').modal('hide');
                    location.reload(); // Reload the page to update the table
                });
            },
            error: function () {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to add translation.',
                    icon: 'error',
                    confirmButtonText: 'OK'
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
            success: function (response) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Translation edited successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('#editTranslationModal').modal('hide');
                    location.reload(); // Reload the page to update the table
                });
            },
            error: function () {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to update translation.',
                    icon: 'error',
                    confirmButtonText: 'OK'
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
            success: function (response) {
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Translation deleted successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('#deleteTranslationModal').modal('hide');
                    location.reload(); // Reload the page to update the table
                });
            },
            error: function () {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to delete translation.',
                    icon: 'error',
                    confirmButtonText: 'OK'
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