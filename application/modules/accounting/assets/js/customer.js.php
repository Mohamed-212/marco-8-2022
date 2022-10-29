"use strict";
<?php
	$cache_file = dirname('__FILE__')."/../../../../../my-assets/js/admin_js/json/customer.json";
    header('Content-Type: text/javascript; charset=utf8');
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
			$(this).unbind("change");
			$('#rv_customer_name').trigger('focusout');
			return false;
		}
	});
	$( ".customerSelection" ).focus(function(){
		$(this).change(APchange);
	
	});
});