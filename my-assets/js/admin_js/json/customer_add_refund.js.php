"use strict";
<?php
$cache_file = "customer.json";
   header('Content-Type: text/javascript; charset=utf8');
   header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
   header("Cache-Control: post-check=0, pre-check=0", false);
   header("Pragma: no-cache");
?>
//var customerList = <?php //echo file_get_contents($cache_file); ?>// ;
var csrf_test_name=  $("#CSRF_TOKEN").val();

var APchange = function(event, ui){
	$(this).data("autocomplete").menu.activeMenu.children(":first-child").trigger("click");
}
$(function() {
  
    $( ".customerSelection" ).autocomplete(
	{
        //source:customerList,
        source:function (request, response) {
            $.ajax({
                url: base_url + "dashboard/Cinvoice/customer_search_all_customers",
                method: "post",
                dataType: "json",
                data: {
                    csrf_test_name: csrf_test_name,
                    customer_name: customer_name,
                },
                success: function (data) {
                    response(data);
                },
            });
        },
		delay:300,
		focus: function(event, ui) {
			$(this).parent().find(".customer_hidden_value").val(ui.item.value);
			$(this).val(ui.item.label);
			return false;
		},
		select: function(event, ui) {
			$(this).parent().find(".customer_hidden_value").val(ui.item.value);
			$(this).val(ui.item.label);

            var opts = "";
            $.ajax({
                url: base_url + 'dashboard/Crefund/get_invoice_by_customer',
                method: 'post',
                data: {
                    customer_id: ui.item.value,
                    csrf_test_name: csrf_test_name,
                },
                success: function(data) {
                    var obj = JSON.parse(data);
                    for (var i = 0;i < obj.length; i++) {
                        opts += '<option id="invid" data-invoice-id="' + obj[i].invoice_id + '" value="' + obj[i].invoice.replace('Inv-', '') + '">' + obj[i].invoice.replace('Inv-', '') + '</option>';
                    }
                    $('#invoice_no').html(opts);
                    $('#invoice_no').trigger('change');
                },
            });
            

			return false;
		}
	});

	$( ".customerSelection" ).focus(function(){
		$(this).change(APchange);
	
	});
	
});