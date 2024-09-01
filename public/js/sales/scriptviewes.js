$(document).on('click', '.downloadpdf', function () {
    var orderId = $(this).data('order-id');
    var basePath = $('#basePath').val();
    var invoiceUrl = basePath + '/sales/generate-pdf-es/' + orderId;
    $('#invoiceFrame').attr('src', invoiceUrl);
    $.ajax({
        url: basePath + '/sales/get-order-details-es',
        type: 'GET',
        data: {
            id: orderId
        },
        success: function (response) {
            if (response.success && response.order && response.order.length > 0) {
                var order = response.order[0];
                var orderDetailsHtml = `
                    <p><strong>Customer Name:</strong> ${order.customername}</p>
                    <p><strong>Full Name:</strong> ${order.fullname}</p>
                    <p><strong>Order Date:</strong> ${new Date(order.createdAt.date).toLocaleDateString()}</p>
                    <p><strong>Order Type:</strong> ${order.type}</p>
                    <p><strong>Grand Total:</strong> ${parseFloat(order.grandTotal).toFixed(2)} ${$('#hiddencurrency').val()}</p>
                    <hr>
                    <h5>Items</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Discount Type</th>
                                <th>Tax</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>`;

                var rowIndex = 1;
                var productName = order.name || 'Unknown Product';
                var productPrice = parseFloat(order.price) || 0;
                var quantity = order.quantity || 1;
                var discountType = order.discountType || 'None';
                var tax = parseFloat(order.TAX) || 0;
                var subtotal = parseFloat(order.subtotal) || 0;
                var currency = $('#hiddencurrency').val();

                var newRow = `<tr data-product-id="${order.id}">
                    <td>${rowIndex}</td>
                    <td>${productName}</td>
                    <td><input type="number" class="form-control quantity" value="${quantity}" min="1" /></td>
                    <td class="price">${productPrice.toFixed(2)} ${currency}</td>
                    <td>${discountType}</td>
                    <td>${tax.toFixed(2)} ${currency}</td>
                    <td class="subtotal">${subtotal.toFixed(2)} ${currency}</td>
                    <td>
                        <a href="javascript:void(0);" class="delete-set"><img src="${basePath}/img/icons/delete.svg" alt="svg"></a>
                    </td>
                </tr>`;

                orderDetailsHtml += newRow;
                orderDetailsHtml += `</tbody></table>`;

                $('#invoiceContent').html(orderDetailsHtml);

                // Show the modal
                $('#invoiceModal').modal('show');
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to load order details',
                    icon: 'error',
                    confirmButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                });
            }
        },
        error: function (xhr, status, error) {
            Swal.fire({
                title: 'Error',
                text: 'An error occurred while loading the order details.',
                icon: 'error',
                confirmButtonClass: 'btn btn-danger',
                buttonsStyling: false
            });
            console.error('Error details:', xhr.responseText);
        }
    });
});