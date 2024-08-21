 $(document).ready(function () {
     // Handle product search and display results
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
                         results.append(`
              <a href="#" class="list-group-item list-group-item-action product-item" 
                data-id="${product.id}" data-name="${product.name}" data-price="${parseFloat(product.price)}">
                ${product.name} - ${parseFloat(product.price).toFixed(2)} ${currency}
              </a>
            `);
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

     // Add product to table with editing capabilities
     function addProductToTable(productId, productName, productPrice) {
         var tableBody = $('#salesTableBody');
         var rowCount = tableBody.find('tr').length;
         var rowIndex = rowCount + 1;
         var currency = $('#hiddencurrency').val();
         var newRow = `
      <tr data-product-id="${productId}">
        <td>${rowIndex}</td>
        <td>${productName}</td>
        <td><input type="number" class="form-control quantity" value="1" min="1" /></td>
        <td class="price">${productPrice.toFixed(2)} ${currency}</td>
        <td><input type="number" class="form-control discount" value="0" min="0" /></td>
        <td><input type="number" class="form-control tax" value="0" min="0" /></td>
        <td class="subtotal">${productPrice.toFixed(2)} ${currency}</td>
        <td>
          <a href="javascript:void(0);" class="delete-set"><img src="${$('#basePath').val()}/img/icons/delete.svg" alt="svg"></a>
        </td>
      </tr>
    `;
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

     // Update row subtotal based on quantity, price, discount, and tax
     function updateRowSubtotal(row) {
         var quantity = parseFloat(row.find('.quantity').val()) || 0;
         var price = parseFloat(row.find('.price').text()) || 0;
         var discount = parseFloat(row.find('.discount').val()) || 0;
         var tax = parseFloat(row.find('.tax').val()) || 0;
         var subtotal = quantity * price - discount + tax;
         row.find('.subtotal').text(subtotal.toFixed(2));
     }

     // Update overall totals including subtotal, discount, tax, and shipping
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
         var orderTax = parseFloat($('#orderTax').val()) || 0;
         var shipping = parseFloat($('#shipping').val()) || 0;
         var discount = parseFloat($('#discount').val()) || 0;
         var grandTotal = subtotal + orderTax + shipping - discount;
         $('#orderTaxDisplay').text(` ${orderTax.toFixed(2)}% ${currency}`);
         $('#discountDisplay').text(` ${discount.toFixed(2)} ${currency}`);
         $('#shippingDisplay').text(` ${shipping.toFixed(2)} ${currency}`);
         $('#grandTotal').text(`${grandTotal.toFixed(2)} ${currency}`);
     }

     // Bind updateTotals to input fields for order tax, discount, and shipping
     $('#orderTax, #discount, #shipping').on('input', updateTotals);

     // Handle form submission for editing orders
     $('.btn-submit').on('click', function (e) {
         e.preventDefault();

         // Collect order data
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

         // Collect product data from the table
         $('#salesTableBody tr').each(function () {
             var row = $(this);

             orderData.products.push({
                 id: row.data('product-id'), // Ensure 'product-id' is set in the data attributes of each row
                 quantity: row.find('.quantity').val(), // Ensure '.quantity' is present in your HTML
                 price: row.find('.price').text(), // Ensure '.price' contains the correct value
                 discount: row.find('.discount').val(), // Ensure '.discount' is present in your HTML
                 tax: row.find('.tax').val(), // Ensure '.tax' is present in your HTML
                 subtotal: row.find('.subtotal').text() // Ensure '.subtotal' contains the correct value
             });
         });

         // Validate data (example: check if essential fields are not empty)
         if (!orderData.customer || !orderData.date || !orderData.type) {
             alert('Please fill out all required fields.');
             return;
         }

         // Log the order data for debugging
         console.log('Submitting order data:', orderData);

         // Make the AJAX request
         $.ajax({
             url: $('#basePath').val() + '/sales/edit-order', // Ensure basePath is correct
             type: 'POST',
             data: JSON.stringify(orderData),
             contentType: 'application/json',
             success: function (response) {
                 if (response.success) {
                     alert('Order updated successfully!');
                     // Optionally, you could redirect or update the page content here
                 } else {
                     alert('Error updating order: ' + response.message);
                 }
             },
             error: function (xhr, status, _error) {
                 alert('An error occurred while updating the order.');
                 console.error('Error details:', xhr.responseText); // Log the error details
             }
         });
     });

 });