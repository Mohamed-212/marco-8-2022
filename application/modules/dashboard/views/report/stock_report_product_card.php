<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Product js php -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/product.js.php"></script>

<script src="<?php echo MOD_URL . 'dashboard/assets/js/print.js'; ?>"></script>

<!-- Stock List Supplier Wise Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon">
            <i class="pe-7s-note2"></i>
        </div>
        <div class="header-title">
            <h1><?php echo display('stock_report_product_card') ?></h1>
            <small><?php echo display('stock_report_product_card') ?></small>
            <ol class="breadcrumb">
                <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('stock') ?></a></li>
                <li class="active"><?php echo display('stock_report_product_card') ?></li>
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
        $validatio_error = validation_errors();
        if (($error_message || $validatio_error)) {
        ?>
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $error_message ?>
                <?php echo $validatio_error ?>
            </div>
        <?php
            $this->session->unset_userdata('error_message');
        }
        ?>


        <div class="row">
            <div class="col-sm-12">
                <div class="column">
                    <?php if ($this->permission->check_label('stock_report')->read()->access()) { ?>
                        <a href="<?php echo base_url('dashboard/Creport') ?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"> </i><?php echo display('stock_report') ?></a>
                    <?php }
                    if ($this->permission->check_label('stock_report_product_wise')->read()->access()) { ?>
                        <a href="<?php echo base_url('dashboard/Creport/stock_report_product_wise') ?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify">
                            </i><?php echo display('stock_report_product_wise') ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php
        date_default_timezone_set(DEF_TIMEZONE);
        $today = date('d-m-Y');
        $this_month = date('01-m-Y');
        ?>

        <!-- Stock report supplier wise -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <?php echo form_open('dashboard/Creport/stock_report_product_card', array(
                            'class' => '', 'id' => 'validate', 'method' => 'GET'
                        )); ?>


                        <div class="form-group row">
                            <label for="store_id" class="col-sm-2 col-form-label"><?php echo display('store') ?>:</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="store_id" id="store_id" required="">
                                    <option value=""></option>
                                    <?php foreach ($store_list as $single_store) :
                                        if (1 == $single_store['default_status']) {
                                    ?>

                                            <option selected value="<?php echo html_escape($single_store['store_id']) ?>">
                                                <?php echo html_escape($single_store['store_name']) ?>
                                            </option>
                                        <?php } else { ?>
                                            <option value="<?php echo html_escape($single_store['store_id']) ?>">
                                                <?php echo html_escape($single_store['store_name']) ?>
                                            </option>
                                    <?php }
                                    endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="product_id" class="col-sm-2 col-form-label"><?php echo display('product') ?>:</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="product_id" id="product_id" required="">
                                    <option value=""></option>
                                    <?php foreach ($product_list as $product_item) { ?>
                                        <option value="<?php echo $product_item['product_id'] ?>" <?php echo (($product_item['product_id'] == @$_GET['product_id']) ? 'selected' : '') ?>>
                                            <?php echo html_escape($product_item['product_name']) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="from_date" class="col-sm-2 col-form-label"><?php echo display('start_date') ?>:</label>
                            <div class="col-sm-4">
                                <input type="text" name="from_date" class="form-control datepicker2" id="from_date" value="<?php echo $getID = $this->input->get('from_date', TRUE) ? $this->input->get('from_date', TRUE) : $this_month; ?>" placeholder="<?php echo
                                                                                                                                                                                                                                                            display('start_date') ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="to_date" class="col-sm-2 col-form-label"><?php echo display('end_date') ?>:</label>
                            <div class="col-sm-4">
                                <input type="text" name="to_date" class="form-control datepicker2" id="to_date" value="<?php echo $getID = $this->input->get('to_date', TRUE) ? $this->input->get('to_date', TRUE) : $today; ?>" placeholder="<?php echo display('end_date') ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="supplier_name" class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary"><?php echo display('search') ?></button>
                                <a class="btn btn-warning" href="#" onclick="printDiv('printableArea')"><?php echo display('print') ?></a>
                            </div>
                        </div>
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('stock_report_product_card') ?></h4>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="printableArea" class="ml_2">
                            <div class="table-responsive mt_10">
                                <table id="" class="table table-bordered table-striped table-hover dataTablePagination">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><?php echo display('date') ?></th>
                                            <th class="text-center"><?php echo display('description') ?></th>
                                            <th class="text-center"><?php echo display('invoice') ?>
                                            </th>
                                            <th class="text-center"><?php echo display('receive_quantity') ?></th>
                                            <th class="text-center"><?php echo display('transfer_quantity') ?></th>
                                            <th class="text-center"><?php echo display('sell_price') ?></th>
                                            <th class="text-center"><?php echo display('total_value') ?></th>
                                            <th class="text-center"><?php echo display('balance') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php
                                        // $all_in_quantity = $stok_report[1];
                                        // $all_in_sales = $stok_report[0]->price;
                                        $all_in_quantity = 0;
                                        $all_in_sales = 0;
                                        ?>
                                        <tr>
                                            <td align="center"><?php echo date('d-m-Y', strtotime($stok_report[0]->created_at)); ?></td>
                                            <td align="center">
                                                <?= display('previous_quantity') ?>
                                            </td>
                                            <td align="center">
                                                <a href="<?php echo base_url() . 'dashboard/Cproduct/product_details/' . $stok_report[0]->product_id; ?>">
                                                    <?php echo $stok_report[0]->product_name; ?>
                                                    <i class="fa fa-shopping-bag pull-right" aria-hidden="true"></i>
                                                </a>
                                            </td>

                                            <td class="text-center">
                                            <?= $stok_report[1] ?>
                                            </td>
                                            <td class="text-center">
                                                --
                                            </td>
                                            <td align="center">
                                                <?= $stok_report[0]->price ?>
                                            </td>
                                            <td align="center">
                                                <?= $stok_report[0]->price * $stok_report[1] ?>
                                            </td>
                                            <td align="center">
                                                --
                                            </td>
                                        </tr>
                                        <?php
                                        if (!empty($stok_report[2])) {
                                            foreach ($stok_report[2] as $stock) {
                                                $all_in_quantity += $stock['quantity'];
                                                $all_in_sales += $stock['rate'];
                                        ?>
                                                <tr>
                                                    <td align="center"><?php echo date('d-m-Y', strtotime($stock['date_time'])); ?></td>
                                                    <td align="center">
                                                        <?= display('sales') ?>
                                                    </td>
                                                    <td align="center">
                                                        <a href="<?php echo base_url() . 'dashboard/Cinvoice/invoice_inserted_data/' . $stock['invoice_id']; ?>">
                                                            <?php echo $stock['invoice']; ?>
                                                            <i class="fa fa-shopping-bag pull-right" aria-hidden="true"></i>
                                                        </a>
                                                    </td>

                                                    <td align="center">
                                                        --
                                                    </td>

                                                    <td align="center">
                                                    <?= $stock['quantity'] ?>
                                                    </td>

                                                    <td class="text-center">
                                                        <?= $stock['rate'] ?>
                                                    </td>
                                                    <td align="center">
                                                        <?= $stock['rate'] * $stock['quantity'] ?>
                                                    </td>
                                                    <td>
                                                        --
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>
                                        <?php
                                        if (!empty($stok_report[3])) {
                                            foreach ($stok_report[3] as $stock) {
                                                $all_in_quantity += $stock['quantity'];
                                                $all_in_sales += $stock['rate'];
                                        ?>
                                                <tr>
                                                    <td align="center"><?php echo date('d-m-Y', strtotime($stock['date_time'])); ?></td>
                                                    <td align="center">
                                                        <?= display('purchase_return') ?>
                                                    </td>
                                                    <td align="center">
                                                        <a href="<?php echo base_url() . 'dashboard/Cpurchase_return/purchase_return_details_data/' . $stock['purchase_return_id']; ?>">
                                                            <?php echo $stock['purchase_return_id']; ?>
                                                            <i class="fa fa-shopping-bag pull-right" aria-hidden="true"></i>
                                                        </a>
                                                    </td>

                                                    <td align="center">
                                                        --
                                                    </td>

                                                    <td align="center">
                                                    <?= $stock['quantity'] ?>
                                                    </td>

                                                    <td class="text-center">
                                                        <?= $stock['rate'] ?>
                                                    </td>
                                                    <td align="center">
                                                        <?= $stock['rate'] * $stock['quantity'] ?>
                                                    </td>
                                                    <td>
                                                        --
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>
                                        <!-- total in -->
                                        <tr>
                                        <td>--</td>
                                        <td>--</td>
                                            <th style="text-align: center">
                                                <?= display('grand_total') ?>
                                            </th>
                                            <td align="center">
                                                --
                                            </td>
                                            <td align="center">
                                            <?= $all_in_quantity ?>
                                                
                                            </td>
                                            <td align="center">
                                            <?= $all_in_sales ?>
                                            </td>
                                            <td align="center">
                                                <?= $all_in_quantity * $all_in_sales ?>
                                            </td>
                                            <td>--</td>
                                        </tr>

                                        <!-- purchase -->
                                        <?php
                                        $all_out_quantity = 0;
                                        $all_out_sales = 0;
                                        if (!empty($stok_report[4])) {
                                            foreach ($stok_report[4] as $stock) {
                                                $all_out_quantity += $stock['quantity'];
                                                $all_out_sales += $stock['rate'];
                                        ?>
                                                <tr>
                                                    <td align="center"><?php echo date('d-m-Y', strtotime($stock['date_time'])); ?></td>
                                                    <td align="center">
                                                        <?= display('purchase') ?>
                                                    </td>
                                                    <td align="center">
                                                        <a href="<?php echo base_url() . 'dashboard/Cpurchase/purchase_details_data/' . $stock['purchase_id']; ?>">
                                                            <?php echo $stock['invoice']; ?>
                                                            <i class="fa fa-shopping-bag pull-right" aria-hidden="true"></i>
                                                        </a>
                                                    </td>

                                                    <td align="center">
                                                        <?= $stock['quantity'] ?>
                                                    </td>

                                                    <td align="center">
                                                        --
                                                    </td>

                                                    <td class="text-center">
                                                        <?= $stock['rate'] ?>
                                                    </td>
                                                    <td align="center">
                                                        <?= $stock['rate'] * $stock['quantity'] ?>
                                                    </td>
                                                    <td>
                                                        --
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>

                                        <!-- invoice return -->
                                        <?php
                                        if (!empty($stok_report[5])) {
                                            foreach ($stok_report[5] as $stock) {
                                                $all_out_quantity += $stock['return_quantity'];
                                                $all_out_sales += $stock['rate'];
                                        ?>
                                                <tr>
                                                    <td align="center"><?php echo date('d-m-Y', strtotime($stock['date_time'])); ?></td>
                                                    <td align="center">
                                                        <?= display('sales') ?> <?= display('return') ?>
                                                    </td>
                                                    <td align="center">
                                                        <a href="<?php echo base_url() . 'dashboard/Crefund/return_invoice/' . $stock['return_invoice_id']; ?>">
                                                            <?php echo $stock['id']; ?>
                                                            <i class="fa fa-shopping-bag pull-right" aria-hidden="true"></i>
                                                        </a>
                                                    </td>

                                                    <td align="center">
                                                        <?= $stock['return_quantity'] ?>
                                                    </td>

                                                    <td align="center">
                                                        --
                                                    </td>

                                                    <td class="text-center">
                                                        <?= $stock['rate'] ?>
                                                    </td>
                                                    <td align="center">
                                                        <?= $stock['rate'] * $stock['return_quantity'] ?>
                                                    </td>
                                                    <td>
                                                        --
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>

                                        <!-- total out -->
                                        <tr>
                                        <td>--</td>
                                        <td>--</td>
                                            <th style="text-align: center">
                                                <?= display('grand_total') ?>
                                            </th>
                                            <td align="center">
                                                <?= $all_out_quantity ?>
                                            </td>
                                            <td align="center">
                                                --
                                            </td>
                                            <td align="center">
                                                <?= $all_out_sales ?>
                                            </td>
                                            <td align="center">
                                                <?= $all_out_quantity * $all_out_sales ?>
                                            </td>
                                            <td>--</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td>--</td>
                                        <td>--</td>
                                            <th style="text-align: center">
                                                <?= display('grand_total') ?>
                                            </th>
                                            <td align="center">
                                                <!-- <?= ($all_in_quantity + $stok_report[1]) - $all_out_quantity ?> -->
                                                --
                                            </td>
                                            <td>--</td>
                                            <td align="center">
                                                <?= ($all_in_sales) - $all_out_sales ?>
                                            </td>
                                            <td align="center">
                                            <?= ($all_in_sales) - $all_out_sales ?>
                                            </td>
                                            <td align="center">
                                                <b>
                                                
                                                <?= ($all_in_quantity + $stok_report[1]) - $all_out_quantity ?>
                                                </b>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Stock List Supplier Wise End -->
<script src="<?php echo MOD_URL . 'dashboard/assets/js/stock_report_variant_wise.js'; ?>"></script>
<script>
    $(document).ready(function() {
        $(".datepicker2").datepicker({
            dateFormat: "dd-mm-yy"
        });
    });
</script>