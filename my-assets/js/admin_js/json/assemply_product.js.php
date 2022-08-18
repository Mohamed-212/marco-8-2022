"use strict";
<?php
$cache_file = "product.json";
header('Content-Type: text/javascript; charset=utf8');
?>
//var productList = <?php //echo file_get_contents($cache_file); ?>// ;
var csrf_test_name=  $("#CSRF_TOKEN").val();


function assembly_productList(cName) {
$( ".assemblyproductSelection" ).autocomplete(
{
//source: productList,
        source: function (request, response) {
            $.ajax({
                url: base_url + "dashboard/Cinvoice/product_search_all_products",
                method: "post",
                dataType: "json",
                data: {
                    csrf_test_name: csrf_test_name,
                },
                success: function (data) {
                    response(data);
                },
            });
        },
delay:300,
focus: function(event, ui) {
$(this).parent().find(".autocomplete_hidden_value").val(ui.item.value);
$(this).val(ui.item.label);
return false;
},
select: function(event, ui) {
$(this).parent().find(".autocomplete_hidden_value").val(ui.item.value);
$(this).val(ui.item.label);

var id=ui.item.value;
var dataString = 'csrf_test_name='+csrf_test_name+'&product_id='+ id;
var base_url = $('.baseUrl').val();
var priceClass = 'price_item'+cName;
var priceClass2 = 'product_price'+cName;
var supplier_price = Number( $('#supplier_price').val());
var sell_price = Number( $('#sell_price').val());
$.ajax
({
type: "POST",
url: base_url+"dashboard/Cinvoice/retrieve_product_data",
data: dataString,
cache: false,
success: function(data)
{

var obj = jQuery.parseJSON(data);
$('.'+priceClass).val(obj.supplier_price);
supplier_price+=Number(obj.supplier_price);
$('#supplier_price').val(supplier_price);

$('.'+priceClass2).val(obj.price);
sell_price+=Number(obj.price);
$('#sell_price').val(sell_price);
}
});

$(this).unbind("change");
return false;
}
});

}