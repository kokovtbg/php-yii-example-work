$(document).ready(function() {
    $('#select-product').select2({
        ajax: {
          url: 'index.php?r=product/get-all',
          dataType: 'json'
          // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        }
      });
});

$('#select-product').on('select2:select', function (e) {
  const data = e.params.data;
  console.log(data);
  const id = data.id;
  const productCount = $("#productCount").val();
  console.log($("#productCount").val());
  
  $.ajax({
    url: `index.php?r=order/add-product&id=${id}&productsCount=${productCount}`, 
    success: function(result){
    $("#products-to-buy").append(result);
    $("#productCount").val(parseInt($("#productCount").val()) + 1);
  }});
});

$('#form-order').on('submit', function (e) { 
  e.preventDefault();
  e.stopImmediatePropagation();
  console.log('Hello');

  const form = $(this);

  const productCount = $('#productCount').val();

  const disabled = form.find(':input:disabled').removeAttr('disabled');

  $.ajax({
    type: "post",
    url: `index.php?r=order/create-order&productCount=${productCount}`,
    data: form.serialize(),
    dataType: "json",
    success: function (response) {
      alert(response);
    },
    error: function (response) {
      alert(response.responseText);
      console.log(response.responseText);
    },
    complete: function () {
      disabled.attr('disabled', 'disabled');
    }
  });
});
