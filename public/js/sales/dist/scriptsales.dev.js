"use strict";

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
      success: function success(response) {
        if (response.success) {
          var results = $('#productSearchResults');
          var currency = $('#hiddencurrency').val();
          results.empty();
          response.products.forEach(function (product) {
            results.append("<a href=\"#\" class=\"list-group-item list-group-item-action product-item\" \n                                data-id=\"".concat(product.id, "\" data-name=\"").concat(product.name, "\" data-price=\"").concat(parseFloat(product.price), "\">\n                                ").concat(product.name, " - ").concat(parseFloat(product.price).toFixed(2), " \n                             ").concat(currency, " </a>"));
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
      error: function error(xhr) {
        console.error('An error occurred: ' + xhr.status + ': ' + xhr.statusText);
      }
    });
  });

  function addProductToTable(productId, productName, productPrice) {
    var tableBody = $('#salesTableBody');
    var rowCount = tableBody.find('tr').length;
    var rowIndex = rowCount + 1;
    var currency = $('#hiddencurrency').val();
    var newRow = "<tr data-product-id=\"".concat(productId, "\">\n            <td>").concat(rowIndex, "</td>\n            <td>").concat(productName, "</td>\n            <td><input type=\"number\" class=\"form-control quantity\" value=\"1\" min=\"1\" /></td>\n            <td class=\"price\">").concat(productPrice.toFixed(2), "   ").concat(currency, "</td>\n            <td><input type=\"number\" class=\"form-control discount\" value=\"0\" min=\"0\" /></td>\n            <td><input type=\"number\" class=\"form-control tax\" value=\"0\" min=\"0\" /></td>\n            <td class=\"subtotal\">").concat(productPrice.toFixed(2), "   ").concat(currency, "</td>\n            <td>\n                <a href=\"javascript:void(0);\" class=\"delete-set\"><img src=\"").concat($('#basePath').val(), "/img/icons/delete.svg\" alt=\"svg\"></a>\n            </td>\n        </tr>");
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
    var discount = parseFloat(row.find('.discount').val()) || 0;
    var tax = parseFloat(row.find('.tax').val()) || 0;
    var subtotal = quantity * price - discount + tax;
    row.find('.subtotal').text(subtotal.toFixed(2));
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
    var orderTax = parseFloat($('#orderTax').val()) || 0;
    var shipping = parseFloat($('#shipping').val()) || 0;
    var discount = parseFloat($('#discount').val()) || 0;
    var grandTotal = subtotal + orderTax + shipping - discount;
    $('#orderTaxDisplay').text("  ".concat(orderTax.toFixed(2), "%  ").concat(currency));
    $('#discountDisplay').text(" ".concat(discount.toFixed(2), " ").concat(currency));
    $('#shippingDisplay').text(" ".concat(shipping.toFixed(2), " ").concat(currency));
    $('#grandTotal').text("".concat(grandTotal.toFixed(2), " ").concat(currency));
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
    }); // console.log('Submitting order data:', orderData); // Log the order data

    $.ajax({
      url: $('#basePath').val() + '/sales/add-order',
      type: 'POST',
      data: JSON.stringify(orderData),
      contentType: 'application/json',
      success: function success(response) {
        if (response.success) {
          alert('Order added successfully!');
        } else {
          alert('Error adding order: ' + response.message);
        }
      },
      error: function error(xhr, status, _error) {
        alert('An error occurred while adding the order.');
        console.error('Error details:', xhr.responseText); // Log the error details
      }
    });
  });
});