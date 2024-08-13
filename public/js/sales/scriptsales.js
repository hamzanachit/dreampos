$(document).ready(function () {
    $('#productSearch').on('input', function () {
        var query = $(this).val();
        var basePath = $('#basePath').val();

        if (query.length < 3) {
            $('#productSearchResults').empty();
            return;
        }

        $.ajax({
            url: basePath + '/sales/get-products',
            type: 'GET',
            data: {
                query: query
            },
            success: function (response) {
                if (response.success) {
                    var results = $('#productSearchResults');
                    var currency = $('#hiddencurrency').val();

                    results.empty();

                    response.products.forEach(function (product) {
                        results.append(
                            `<a href="#" class="list-group-item list-group-item-action product-item" 
                                data-id="${product.id}" data-name="${product.name}" data-price="${parseFloat(product.price)}">
                                ${product.name} - ${parseFloat(product.price ).toFixed(2)} 
                             ${currency} </a>`
                        );
                    });

                    $('.product-item').on('click', function () {
                        var productId = $(this).data('id');
                        var productName = $(this).data('name');
                        var productPrice = parseFloat($(this).data('price'));

                        addProductToTable(productId, productName, productPrice);

                        $('#productSearch').val('');
                        $('#productSearchResults').empty();
                    });
                }
            },
            error: function (xhr) {
                console.error('An error occurred: ' + xhr.status + ': ' + xhr.statusText);
            }
        });
    });

    function addProductToTable(productId, productName, productPrice) {
        var tableBody = $('#salesTableBody');
        var rowCount = tableBody.find('tr').length;
        var rowIndex = rowCount + 1;
        var currency = $('#hiddencurrency').val();


        var newRow = `<tr data-product-id="${productId}">
            <td>${rowIndex}</td>
            <td>${productName}</td>
            <td><input type="number" class="form-control quantity" value="1" min="1" /></td>
            <td class="price">${productPrice.toFixed(2)}   ${currency}</td>
            <td><input type="number" class="form-control discount" value="0" min="0" /></td>
            <td><input type="number" class="form-control tax" value="0" min="0" /></td>
            <td class="subtotal">${productPrice.toFixed(2)}   ${currency}</td>
            <td>
                <a href="javascript:void(0);" class="delete-set"><img src="${$('#basePath').val()}/img/icons/delete.svg" alt="svg"></a>
            </td>
        </tr>`;

        tableBody.append(newRow);
        updateTotals();

        tableBody.find('tr:last-child .delete-set').on('click', function () {
            $(this).closest('tr').remove();
            updateTotals();
        });

        tableBody.find('tr:last-child .quantity, tr:last-child .discount, tr:last-child .tax').on('input', function () {
            updateRowSubtotal($(this).closest('tr'));
            updateTotals();
        });
    }


    function updateRowSubtotal(row) {
        var quantity = parseFloat(row.find('.quantity').val()) || 0;
        var price = parseFloat(row.find('.price').text()) || 0;
        var discountPercentage = parseFloat(row.find('.discount').val()) || 0;
        var taxPercentage = parseFloat(row.find('.tax').val()) || 0;
        var initialSubtotal = quantity * price;
        var discountAmount = (initialSubtotal * discountPercentage) / 100;
        var subtotalAfterDiscount = initialSubtotal - discountAmount;
        var taxAmount = (subtotalAfterDiscount * taxPercentage) / 100;
        var finalSubtotal = subtotalAfterDiscount + taxAmount;
        row.find('.subtotal').text(finalSubtotal.toFixed(2));
    }

    function updateTotals() {
        var subtotal = 0;
        var totalDiscount = 0;
        var totalTax = 0;
        var currency = $('#hiddencurrency').val();

        $('#salesTableBody tr').each(function () {
            subtotal += parseFloat($(this).find('.subtotal').text()) || 0;
            totalDiscount += parseFloat($(this).find('.discount').val()) || 0;
            totalTax += parseFloat($(this).find('.tax').val()) || 0;
        });

        var orderTaxPercentage = parseFloat($('#orderTax').val()) || 0;
        var shipping = parseFloat($('#shipping').val()) || 0;
        var discountPercentage = parseFloat($('#discount').val()) || 0;

        // Calculate discount amount based on the percentage
        var discountAmount = (subtotal * discountPercentage) / 100;

        // Calculate order tax amount based on the percentage
        var orderTaxAmount = (subtotal * orderTaxPercentage) / 100;

        // Calculate the grand total
        var grandTotal = subtotal + orderTaxAmount + shipping - discountAmount;

        // Update UI elements with the calculated values
        $('#orderTaxDisplay').text(`${orderTaxPercentage.toFixed(2)} %(+ ${orderTaxAmount.toFixed(2)} ${currency}) `);
        $('#discountDisplay').text(`  ${discountPercentage.toFixed(2)} %  (- ${discountAmount.toFixed(2)} ${currency}) `);
        $('#shippingDisplay').text(` ${shipping.toFixed(2)} ${currency}`);
        $('#grandTotal').text(`${grandTotal.toFixed(2)} ${currency}`);
    }



    $('#orderTax, #discount, #shipping').on('input', updateTotals);

    $('.btn-submit').on('click', function (e) {
        e.preventDefault();

        var orderData = {
            customer: $('#customer').val(),
            date: $('#orderDate').val(),
            supplier: $('#supplier').val(),
            status: $('#status').val(),
            orderTax: $('#orderTax').val(),
            discount: $('#discount').val(),
            shipping: $('#shipping').val(),
            type: $('#type').val(),
            products: []
        };

        $('#salesTableBody tr').each(function () {
            var row = $(this);
            orderData.products.push({
                id: row.data('product-id'),
                quantity: row.find('.quantity').val(),
                price: row.find('.price').text(),
                discount: row.find('.discount').val(),
                tax: row.find('.tax').val(),
                subtotal: row.find('.subtotal').text()
            });
        });

        // console.log('Submitting order data:', orderData); // Log the order data

        // $.ajax({
        //     url: $('#basePath').val() + '/sales/add-order',
        //     type: 'POST',
        //     data: JSON.stringify(orderData),
        //     contentType: 'application/json',
        //     success: function (response) {
        //         if (response.success) {
        //             alert('Order added successfully!');
        //         } else {
        //             alert('Error adding order: ' + response.message);
        //         }
        //     },
        //     error: function (xhr, status, error) {
        //         alert('An error occurred while adding the order.');
        //         console.error('Error details:', xhr.responseText); // Log the error details
        //     }
        // });






        $.ajax({
            url: $('#basePath').val() + '/sales/add-order',
            type: 'POST',
            data: JSON.stringify(orderData),
            contentType: 'application/json',
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success',
                        text: 'Order added successfully!',
                        icon: 'success',
                        confirmButtonClass: 'btn btn-success',
                        buttonsStyling: false
                    }).then(function () {
                        location.reload(); // Reloads the page after the user clicks "OK"
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error adding order: ' + response.message,
                        icon: 'error',
                        confirmButtonClass: 'btn btn-danger',
                        buttonsStyling: false
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred while adding the order.',
                    icon: 'error',
                    confirmButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                });
                console.error('Error details:', xhr.responseText);
            }
        });














    });
});