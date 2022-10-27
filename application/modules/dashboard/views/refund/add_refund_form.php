<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Customer js php -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/customer.js.php"></script>
<!-- Product invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/product_invoice.js.php"></script>
<!-- Invoice js -->

<script src="<?php echo MOD_URL . 'dashboard/assets/js/add_refund_form.js'; ?>"></script>
<script src="<?php echo MOD_URL . 'dashboard/assets/js/add_invoice_form_2.js'; ?>"></script>
<link rel="stylesheet" href="<?php echo MOD_URL . 'dashboard/assets/css/invoice/add_invoice_form.css' ?>">

<!-- Add New Invoice Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('new_return') ?></h1>
            <small><?php echo display('add_new_return') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('return') ?></a></li>
                <li class="active"><?php echo display('new_return') ?></li>
            </ol>
        </div>
    </section>
    <section class="content">

        <!-- Alert Message -->
        <?php
        $message = $this->session->userdata('message');
        if (isset($message)) {
        ?>
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $message ?>
            </div>
        <?php
            $this->session->unset_userdata('message');
        }
        $error_message = $this->session->userdata('error_message');
        if (isset($error_message)) {
        ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $error_message ?>
            </div>
        <?php
            $this->session->unset_userdata('error_message');
        }
        ?>


        <!--Add return -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('new_return') ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php echo form_open(base_url() . '/dashboard/Crefund/get_invoice_by_product', array('class' => 'form-vertical', 'id' => 'validate', 'name' => 'insert_invoice')) ?>
                        <div class="row">
                            <div class="col-sm-6" id="payment_from_1">
                                <div class="form-group row">
                                    <label for="customer_name" class="col-sm-4 col-form-label"><?php echo display('customer_name') ?> <i class="text-danger">*</i></label>
                                    <div class="col-sm-8">
                                        <input type="text" size="100" value="<?php echo html_escape($customer_name); ?>" name="customer_name" required class="customerSelection form-control" placeholder='<?php echo display('customer_name_or_phone'); ?>' id="customer_name" autocomplete="off" />
                                        <input id="SchoolHiddenId" value="<?php echo html_escape($customer_id) ?>" class="customer_hidden_value" type="hidden" name="customer_id" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label"><?php echo display('product_name') ?>:</label>
                                    <div class="col-sm-8">
                                        <!-- <input type="text" class="form-control" name="invoice_no" id="invoice_no" value="<?php echo set_value('invoice_no', @$_GET['invoice_no']) ?>" placeholder='<?php echo display('invoice_no') ?>'> -->
                                        <!-- <input type="hidden" class="form-control" name="product_id" id="product_id" value=""> -->
                                        <input type="text" name="product_name" onkeyup="invoice_productList(1);" class="form-control productSelection" placeholder='<?php echo display('product_name') ?>' required="" id="product_name_1" value="<?= $product_name ?>">

                                        <?php
                                        $defaultStore = $this->db->select('store_id')
                                            ->from('store_set')
                                            ->where('default_status', 1)
                                            ->limit(1)
                                            ->get()
                                            ->row();
                                        ?>
                                        <input type="hidden" hidden id="store_id" name="store_id" value="<?= $defaultStore->store_id ?>" />

                                        <input type="hidden" class="autocomplete_hidden_value product_id_1" name="product_id[]" required value="<?= $product_id ?>" />

                                        <input type="hidden" class="sl" value="1">
                                        <input type="hidden" name="assembly[]" id="assembly1" value="">
                                        <input type="hidden" name="colorv[]" id="color1" value="">
                                        <input type="hidden" name="sizev[]" id="size1" value="">
                                        <input type="hidden" class="baseUrl" value="<?php echo base_url(); ?>" />
                                        <input type="hidden" hidden name="category_id" id="category_id_1" value="" />
                                        <input type="hidden" hidden name="product_model" id="product_model_1" value="" />
                                        <div style="display: none;">
                                            <select name="variant_id" id="variant_id_1" class="variant_id" style="display: none;" disabled="">
                                                <?php if (!empty($variant_id)) : ?>
                                                    <option value="<?= $variant_id ?>" selected><?= $variant_id ?></option>
                                                <?php else : ?>
                                                    <option value=""></option>
                                                <?php endif ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label"></label>
                                    <div class="col-sm-8">
                                        <button type="submit" class="btn btn-success"><?= display('search') ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close() ?>


                        <div class="table-responsive mt_10">
                            <table class="table table-bordered table-hover dataTablePagination" id="normalinvoice">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo display('invoice') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('price') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('available_quantity') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center" id="trans" data-fit="<?= display('fit') ?>" data-warranty="<?= display('no warranty') ?>" data-damaged="<?= display('damaged') ?>"><?php echo display('status') ?></th>
                                        <th class="text-center"><?php echo display('return_price') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('quantity') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('action') ?> <i class="text-danger">*</i></th>
                                    </tr>
                                </thead>
                                <tbody id="addinvoiceItem">
                                    <?php foreach ($invoices as $inx => $inv) : ?>
                                        <tr>
                                        <?php echo form_open(base_url() . '/dashboard/Crefund/new_return', ['method' => 'GET', 'class' => 'form-vertical new_return', 'id' => 'validate', 'name' => 'insert_invoice']) ?>
                                            <input type="hidden" hidden name="customer_id" value="<?= $customer_id ?>" />
                                            <input type="hidden" hidden name="product_id" value="<?= $product_id ?>" />
                                            <input type="hidden" hidden name="invoice_no" value="<?= $inv->invoice ?>" />
                                            <input type="hidden" hidden name="invoice_id" value="<?= $inv->invoice_id ?>" />
                                            <input type="hidden" hidden name="variant_id" value="<?= $inv->variant_id ?>" />
                                            <td>
                                                <a href="<?= base_url() ?>/dashboard/Cinvoice/invoice_inserted_data/<?= $inv->invoice_id ?>"><?= $inv->invoice ?></a>
                                            </td>
                                            <td>
                                                <?php
                                                $item_discount = round(((float)$inv->item_invoice_discount * (float)$inv->quantity) + ((float)$inv->discount * (float)$inv->quantity), 2);
                                                $total_price_after_discount = round((float)$inv->total_price - (float)$item_discount, 2);

                                                // var_dump($item_discount, $total_price_after_discount, $inv->total_price);
                                                echo $total_price_after_discount;
                                                ?>
                                                <input type="hidden" hidden name="rate_<?= $inx + 1 ?>" value="<?= $inv->rate ?>" />
                                            </td>
                                            <td><input type='text' id='available_quantity_<?= $inx + 1 ?>' name='available_quantity[]' class='form-control text-right available_quantity_<?= $inx + 1 ?>' id='avl_qntt_<?= $inx + 1 ?>' placeholder='0' readonly='' value="<?= $inv->ava_quantity ?>" /></td>
                                            </td>
                                            <td>
                                                <select class='form-control' id='status_<?= $inx + 1 ?>' required='required' name='status'>
                                                    <option value='0'><?= display('fit') ?></option>
                                                    <option value='1'><?= display('damaged') ?></option>
                                                    <option value='2'><?= display('no warranty') ?></option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class='form-control' id='price_type_<?= $inx + 1 ?>' required='required' name='price_type'>
                                                    <option value='0'><?= display('sell_price') ?></option>
                                                    <option value='1'><?= display('with_cases_price') ?></option>
                                                </select>
                                            </td>
                                            <td><input type='number' class='form-control' id='quantity_<?= $inx + 1 ?>' required='required' min='0' value='0' max='<?= $inv->ava_quantity ?>' name='quantity'></td>
                                            <td>

                                                <button type="submit" class="btn btn-primary"><?= display('submit') ?></button>
                                            </td>
                                            <?php echo form_close() ?>
                                        
                                        </tr>
                                        <?php endforeach ?>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                            <div style="text-align: center;">
                                <!-- <button type="submit" class="btn btn-primary"><?= display('submit') ?></button> -->
                            </div>
                            <!-- </form> -->
                        </div>
                        <!-- view -->



                    </div>


    </section>
</div>
<!-- Invoice Report End -->
<script>
    $(document).ready(function() {
        $(document).on('change', '#invoice_no', function() {
            var val = $(this).val();
            $('option#invid').each(function(inx, el) {
                if ($(this).val() == val) {
                    $('#invoice_id').val($(this).attr('data-invoice-id'));
                }
            });
        });
        $(document).on('change', '#payment_type', function() {
            var val = $(this).val();

            if (val == 1) {
                $('#bank_list_container').removeClass('hidden');
            } else {
                $('#bank_list_container').addClass('hidden');
            }
        });
    });
</script>