$(document).ready(function () {
    // Function to initialize the totals on page load
    function initializeTotals() {
        $('#salesTableBody tr').each(function () {
            // Ensure existing rows have the correct subtotal
            updateRowSubtotal($(this));
        });
        updateTotals();
    }

    // Function to load old products into the table
    function loadOldProducts(products, currency) {
        var tableBody = $('#salesTableBody');
        var rowCount = tableBody.find('tr').length;
        products.forEach(function (product, index) {
            var rowIndex = rowCount + index + 1;
            // console.log(product.productId);

            var newRow = `
                        <tr>
                         <td>${rowIndex}</td>
                            <td class="iditem" style="display:none" data-item-id="${product.orderItemId}">${product.orderItemId}</td>
                             <td  class="productid" style="display:none" data-product-id="${product.productId}">${product.productId}</td>
                           
                               <td class="productname" data-productname="${product.productName}">${product.productName}</td>
                            <td><input type="number" class="form-control quantity" value="${product.quantity}" min="1" step="1.00" /></td>
                            <td><input type="number" class="form-control price" value="${parseFloat(product.productPrice).toFixed(2)}" min="0" step="1.00" /></td>
                            <td><input type="number" class="form-control discount" value="${parseFloat(product.discount).toFixed(2)}" min="0" step="1.00" /></td>
                            <td><input type="number" class="form-control tax" value="${parseFloat(product.TAX).toFixed(2)}" min="0" step="1.00" /></td>
                            <td><input type="number" class="form-control subtotal" value="${parseFloat(product.subtotal).toFixed(2)}" min="0" step="1.00" readonly /></td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-danger btn-remove">
                                    <img src="${$('#basePath').val()}/img/icons/delete.svg" alt="delete icon">
                                </a>
                            </td>
                        </tr>`;


            tableBody.append(newRow);
        });

        updateRowIndices();
        bindRowEvents(tableBody.find('tr')); // Bind events for newly added rows
        updateTotals();
    }

    // Handle product search input
    $('#productSearch').on('input', function () {
        var query = $(this).val();
        var basePath = $('#basePath').val();

        if (query.length < 1) {
            $('#productSearchResults').empty();
            return;
        }

        $.ajax({
            url: basePath + '/sales/get-products',
            type: 'GET',
            data: {
                query: query,
                searchBy: 'name_or_reference'
            },
            success: function (response) {
                if (response.success) {
                    var results = $('#productSearchResults');
                    var currency = $('#hiddencurrency').val();

                    results.empty();

                    response.products.forEach(function (product) {
                        results.append(
                            `<a href="#" class="list-group-item list-group-item-action product-item" 
                                data-id="${product.id}" data-name="${product.name}" 
                                 data-iditem = "${product.orderItemId}"
                                data-price="${parseFloat(product.price)}">
                                ${product.name} - ${parseFloat(product.price).toFixed(2)} ${currency} </a>`
                        );
                    });

                    $('.product-item').on('click', function () {
                        var productId = $(this).data('id');
                        var iditem = $(this).data('iditem');
                        var productName = $(this).data('name');
                        var productPrice = parseFloat($(this).data('price'));
                        // console.log(productName);
                        addProductToTable(productId, productName, productPrice, iditem);
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

    // Add a new product to the table
    function addProductToTable(productId, productName, productPrice, iditem) {
        var tableBody = $('#salesTableBody');
        var rowCount = tableBody.find('tr').length;
        var rowIndex = rowCount + 1;
        var currency = $('#hiddencurrency').val();

        var newRow = `<tr>
            <td>${rowIndex}</td>
            <td class="iditem" data-item-id="${iditem}" style="display: none;">${iditem}</td>
            <td class="productname" data-productname="${productName}">${productName}</td>

            <td  class="productid" style="display:none" data-product-id="${productId}">${productId}</td>

            <td><input type="number" class="form-control quantity" value="1" min="1" /></td>
            <td><input type="number" class="form-control price" value="${productPrice.toFixed(2)}" min="0" step="1.00" /> </td>
            <td><input type="number" class="form-control discount" value="0" min="0" step="1.00" /></td>
            <td><input type="number" class="form-control tax" value="0" min="0" step="1.00" /></td>
            <td><input type="number" class="form-control subtotal" value="${productPrice.toFixed(2)}" min="0" step="1.00" readonly /> </td>
            <td>
                <a href="javascript:void(0);" class="btn btn-danger btn-remove">
                    <img src="${$('#basePath').val()}/img/icons/delete.svg" alt="delete icon">
                </a>
            </td>
        </tr>`;

        tableBody.append(newRow);
        updateRowIndices();
        updateTotals();

        // Bind events for newly added rows
        bindRowEvents(tableBody.find('tr:last-child'));
    }



    function bindRowEvents(row) {
        row.find('.quantity, .price, .discount, .tax').on('input', function () {
            updateRowSubtotal($(this).closest('tr'));
            updateTotals();
        });

        row.find('.btn-remove').on('click', function () {
            var isOldItem = $(this).closest('tr').find('td.iditem').data('item-id');
            var row = $(this).closest('tr');

            if (isOldItem != "undefined") {
                // Show confirmation dialog for old items
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This item will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-danger',
                    cancelButtonClass: 'btn btn-secondary',
                    confirmButtonText: 'Yes, delete it!',
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed to delete item from the server
                        deleteItemFromDatabase(row);
                    }
                });
            } else {
                // For new items, just remove the row from the table
                row.remove();
                updateRowIndices();
                updateTotals();
            }
        });
    }

    function deleteItemFromDatabase(row) {
        var itemId = row.find('.iditem').data('item-id');
        var basePath = $('#basePath').val();

        $.ajax({
            url: basePath + '/sales/delete-order-item', // Adjust the URL to match your endpoint
            type: 'POST',
            data: JSON.stringify({
                itemId: itemId
            }), // Send the item ID to be deleted
            contentType: 'application/json',
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'The item has been deleted.',
                        icon: 'success',
                        confirmButtonClass: 'btn btn-success',
                        buttonsStyling: false
                    });

                    // Remove the row from the table after successful deletion
                    row.remove();
                    updateRowIndices();
                    updateTotals();
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred: ' + response.message,
                        icon: 'error',
                        confirmButtonClass: 'btn btn-danger',
                        buttonsStyling: false
                    });
                }
            },
            error: function (xhr) {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred while deleting the item.',
                    icon: 'error',
                    confirmButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                });
                console.error('Error response:', xhr.responseText);
            }
        });
    }

    // Update the subtotal for a row
    function updateRowSubtotal(row) {
        var quantity = parseFloat(row.find('.quantity').val()) || 0;
        var price = parseFloat(row.find('.price').val()) || 0;
        var discountPercentage = parseFloat(row.find('.discount').val()) || 0;
        var taxPercentage = parseFloat(row.find('.tax').val()) || 0;

        var initialSubtotal = quantity * price;
        var discountAmount = (initialSubtotal * discountPercentage) / 100;
        var subtotalAfterDiscount = initialSubtotal - discountAmount;
        var taxAmount = (subtotalAfterDiscount * taxPercentage) / 100;
        var finalSubtotal = subtotalAfterDiscount + taxAmount;

        row.find('.subtotal').val(finalSubtotal.toFixed(2)); // Update the input field for subtotal
    }

    // Update totals for the order
    function updateTotals() {
        var subtotal = 0;
        var totalDiscount = 0;
        var totalTax = 0;
        var currency = $('#hiddencurrency').val();

        $('#salesTableBody tr').each(function () {
            subtotal += parseFloat($(this).find('.subtotal').val()) || 0;
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
        $('#orderTaxDisplay').text(`${orderTaxPercentage.toFixed(2)}% (+ ${orderTaxAmount.toFixed(2)} ${currency})`);
        $('#discountDisplay').text(`${discountPercentage.toFixed(2)}% (- ${discountAmount.toFixed(2)} ${currency})`);
        $('#shippingDisplay').text(`${shipping.toFixed(2)} ${currency}`);
        $('#grandTotal').text(`${grandTotal.toFixed(2)} ${currency}`);
    }

    // Update row indices
    function updateRowIndices() {
        $('#salesTableBody tr').each(function (index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    // Bind events for order inputs
    $('#orderTax, #discount, #shipping').on('input', updateTotals);

    // Submit order form
    $('.btn-submit').on('click', function (e) {
        e.preventDefault();

        var orderData = {
            orderid: $('#hiddenorderId').val(),
            customer: $('#customer').val(),
            date: $('#orderDate').val(),
            supplier: $('#supplier').val(),
            status: $('#status').val(),
            orderTax: $('#orderTax').val(),
            discount: $('#discount').val(),
            shipping: $('#shipping').val(),
            orderType: $('#orderType').val(),
            type: $('#type').val(),
            products: []
        };

        $('#salesTableBody tr').each(function () {
            var row = $(this);
            orderData.products.push({
                iditem: row.find('td.iditem').data('item-id'),
                productId: row.find('td.productid').data('product-id'),
                name: row.find('td.productname').data('productname'),
                quantity: parseFloat(row.find('.quantity').val()) || 0,
                price: parseFloat(row.find('.price').val()) || 0,
                discount: parseFloat(row.find('.discount').val()) || 0,
                tax: parseFloat(row.find('.tax').val()) || 0,
                subtotal: parseFloat(row.find('.subtotal').val()) || 0
            });

        });

        $.ajax({
            url: $('#basePath').val() + '/sales/update-order',
            type: 'POST',
            contentType: 'application/json', // Ensure the server knows we're sending JSON
            data: JSON.stringify(orderData), // Convert the JavaScript object to a JSON string
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success',
                        text: 'Order updated successfully!',
                        icon: 'success',
                        confirmButtonClass: 'btn btn-success',
                        buttonsStyling: false
                    }).then(function () {
                        // location.reload();
                        // window.location.href = $('#basePath').val() + "/sales/list";
                        window.history.back();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred: ' + response.message,
                        icon: 'error',
                        confirmButtonClass: 'btn btn-danger',
                        buttonsStyling: false
                    });
                }
            },
            error: function (xhr) {
                // Detailed error handling
                let errorMsg = 'An error occurred: ' + xhr.status + ' - ' + xhr.statusText;
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response && response.message) {
                        errorMsg += ' - ' + response.message;
                    }
                } catch (e) {
                    // If the response is not valid JSON, just use the original error message
                }
                Swal.fire({
                    title: 'Error',
                    text: errorMsg,
                    icon: 'error',
                    confirmButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                });
                console.error('Detailed error response:', xhr.responseText);
            }
        });

    });

    // Initialize old products if data is available
    if (typeof orderData !== 'undefined' && orderData.items) {
        loadOldProducts(orderData.items, $('#hiddencurrency').val());
    }

    // Initialize totals
    initializeTotals();
});