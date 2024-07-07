$(document).ready(function () {
  function validateForm () {
    var msg = ''

    $('.input-req').each(function () {
      if ($(this).val() === '') {
        msg += $(this).prev('label').text() + ' is required. <br>\n'
        $(this).css('background-color', '#F3C200')
      }
    })

    if (msg === '') {
      return true
    } else {
      bootbox.alert({ title: 'Action denied', message: msg })
      return false
    }
  }

  $('#formAddProduct').on('submit', function (event) {
    event.preventDefault()

    if (validateForm()) {
      var formData = new FormData(this)

      $.ajax({
        url: 'http://localhost/erpp/public/settings/edit',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          alert('Product added successfully')
        },
        error: function (xhr, status, error) {
          alert('An error occurred: ' + xhr.responseText)
        }
      })
    }
  })

  $('.input-req').on('focus', function () {
    $(this).css('background', 'white')
  })
})
