"use strict";
<?php
$cache_file = "product.json";
header('Content-Type: text/javascript; charset=utf8');
?>
//var productList = <?php //echo file_get_contents($cache_file); ?>// ;
var csrf_test_name = $("#CSRF_TOKEN").val();

var APchange = function (event, ui) {
    $(this).data("autocomplete").menu.activeMenu.children(":first-child").trigger("click");
}
function invoice_productList(cName) {
    var qnttClass = 'ctnqntt' + cName;
    var priceClass = 'price_item' + cName;
    var priceSavedClass = 'price_item_saved_' + cName;
    var total_tax_price = 'total_tax_' + cName;
    var available_quantity = 'available_quantity_' + cName;
    var unit = 'unit_' + cName;
    var cgst = 'cgst_' + cName;
    var sgst = 'sgst_' + cName;
    var igst = 'igst_' + cName;
    var variant = 'variant_' + cName;
    var cgst_id = 'cgst_id_' + cName;
    var sgst_id = 'sgst_id_' + cName;
    var igst_id = 'igst_id_' + cName;
    var variant_id = 'variant_id_' + cName;
    var variant_color = 'variant_color_id_' + cName;
//var pricing   ='pricing_'+cName;
    var color = 'color' + cName;
    var size = 'size' + cName;
    var assembly = 'assembly' + cName;
    var viewassembly = "viewassembly" + cName;
    var discount = 'discount_' + cName;
    var category_id = 'category_id_' + cName;
    var product_model = 'product_model_' + cName;
    var product_name = $("#product_name_" + cName).val();

    $(".productSelection").autocomplete(
        {
            //source: productList,
            source: function (request, response) {
                $.ajax({
                    url: base_url + "dashboard/Cinvoice/product_search_all_products",
                    method: "post",
                    dataType: "json",
                    data: {
                        csrf_test_name: csrf_test_name,
                        product_name: product_name,
                    },
                    success: function (data) {
                        response(data);
                    },
                });
            },
            delay: 300,
            focus: function (event, ui) {
                $(this).parent().find(".autocomplete_hidden_value").val(ui.item.value);
                $(this).val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $(this).parent().find(".autocomplete_hidden_value").val(ui.item.value);
                $(this).val(ui.item.label);

                var id = ui.item.value;
                var dataString = 'csrf_test_name=' + csrf_test_name + '&product_id=' + id;
                var base_url = $('.baseUrl').val();
                $.ajax
                ({
                    type: "POST",
                    url: base_url + "dashboard/Cinvoice/retrieve_product_data",
                    data: dataString,
                    cache: false,
                    success: function (data) {

                        var obj = jQuery.parseJSON(data);
                        console.log(obj);
                        $('.' + qnttClass).val(obj.cartoon_quantity);
                        $('.' + priceClass).val(obj.price);
                        $('.' + total_tax_price).val(obj.tax);
                        $('.' + unit).val(obj.unit);
                        $('#' + cgst).val(obj.cgst_tax);
                        $('#' + sgst).val(obj.sgst_tax);
                        $('#' + igst).val(obj.igst_tax);
                        $('#' + variant).val(obj.variant_id);
                        $('#' + cgst_id).val(obj.cgst_id);
                        $('#' + sgst_id).val(obj.sgst_id);
                        $('#' + igst_id).val(obj.igst_id);
                        $('#' + variant_id).html(obj.variant);
                        $('#' + variant_color).html(obj.colorhtml);
//$('#'+pricing).html(obj.pricinghtml);
                        $('#' + discount).val(obj.discount);
                        $('#' + category_id).val(obj.category_id);
                        $('#' + product_model).val(obj.product_model);
                        $("#" + size).val(obj.size);
                        $("#" + color).val(obj.color);
                        $("#" + assembly).val(obj.assembly);
                        var assemplyvalue = obj.assembly;

//This Function Stay on others.js page

                        stock_by_product_variant_id(cName);
                        stock_by_product_variant_color(cName);
//quantity_calculate(cName);
                        if (assemplyvalue == 1) {
                            $("#" + viewassembly).removeClass("hidden");
                        } else {
                            $("#" + viewassembly).addClass("hidden");
                        }
                        get_pri_type_rate1(cName);

                        if (obj.category_id == accessories_category_id) {
                            // this item is accessories
                            // set price to zero if type is assemply
                            if ($('#product_type').val() == '2') {
                                $('#price_item_' + cName).val(0);
                            }
                            // get all items with same name sum quantity
                            var totalQuantity = 0;
                            $('[name="product_name"]').each(function() {
                                var itemName = $(this).val();
                                var counter = $(this).attr('id').replace('product_name_', '');
                                var itemCategoryId = $('#category_id_' + counter).val();
                                var itemProductModel = $('#product_model_' + counter).val();
                                var itemQuantity = $('#total_qntt_' + counter).val();
                                itemName = itemName.replace(itemProductModel, '');
                                if (itemCategoryId != accessories_category_id) {
                                    if (itemName.indexOf(obj.product_name.replace(obj.product_model, '')) > -1) {
                                        totalQuantity += parseInt(itemQuantity);
                                    }
                                    // console.log(cName, itemName.indexOf(obj.product_name.replace(obj.product_model, '')), itemName, counter, itemCategoryId);
                                }
                            }).promise().then(function() {
                                $('#total_qntt_' + cName).val(totalQuantity).trigger('keyup');
                                console.log(totalQuantity);
                            });
                        }

                    }
                });

                $(this).unbind("change");
                return false;
            }
        });
    $(".productSelection").focus(function () {
        $(this).change(APchange);
    });
}