"use strict";
<?php
$cache_file = "customer.json";
   header('Content-Type: text/javascript; charset=utf8');
?>
var customerList = <?php echo file_get_contents($cache_file); ?> ; 
var csrf_test_name=  $("#CSRF_TOKEN").val();

var APchange = function(event, ui){
	$(this).data("autocomplete").menu.activeMenu.children(":first-child").trigger("click");
}
$(function() {
  
    $( ".customerSelection" ).autocomplete(
	{
        source:customerList,
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
                        opts += '<option value="' + obj[i].invoice_id + '">' + obj[i].invoice + '</option>';
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