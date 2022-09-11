$(document).ready(function(){
var productArr={};
	$("#invoice_no").on('change', function(){
		var invoice_no = $("#invoice_no").val();
		var csrf_test_name = $("#CSRF_TOKEN").val();
	    $.ajax({
        url: base_url + "dashboard/Crefund/get_invoice_products",
        method: "post",
        dataType: "json",
        data: {
            invoice_no: invoice_no,
            csrf_test_name: csrf_test_name
        },
        success: function (data) {
          $('#product_id').empty()
          select = document.getElementById('product_id');
          var opt =  document.createElement('option');
          opt.innerHTML = '';
          opt.value ='';
          select.appendChild(opt);
          for (i = 0; i < data.length; i++) {
            var opt = document.createElement('option');
            opt.innerHTML = data[i]['product_name'];
            opt.value = data[i]['product_id'];
            select.appendChild(opt);
        } 
        },
      });
	});

	$("#product_id").on('change', function(){
		var id = this.value;
    var invoice_no = $("#invoice_no").val();
		var csrf_test_name = $("#CSRF_TOKEN").val();
	    $.ajax({
        url: base_url + "dashboard/Crefund/get_product_variants",
        method: "post",
        dataType: "json",
        data: {
            invoice_no: invoice_no,
            product_id:id,
            csrf_test_name: csrf_test_name
        },
        success: function (data) {
          $('#variant_id').empty()
          productArr={};
          select = document.getElementById('variant_id');
          var opt =  document.createElement('option');
          opt.innerHTML = '';
          opt.value ='';
          select.appendChild(opt);

          for (i = 0; i < data.length; i++) {
            productArr[id+data[i]['variant_id']]=data[i]['quantity'];
            var opt = document.createElement('option');
            opt.innerHTML = data[i]['variant_name'];
            opt.value = data[i]['variant_id'];
            select.appendChild(opt);
        } 
          
        },
      });
      });

      $("#variant_id").on('change', function(){
        var id=this.value;
        var product_id = $("#product_id").val();
            console.log(productArr[product_id+id]);
        document.getElementById("quantity").max = productArr[product_id+id];
	});

  
});
