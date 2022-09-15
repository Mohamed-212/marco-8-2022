<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Customer js php -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/customer.js.php"></script>
<!-- Product invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/product_invoice.js.php"></script>
<!-- Invoice js -->

<script src="<?php echo MOD_URL . 'dashboard/assets/js/add_refund_form.js'; ?>"></script>
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
        <form action="<?= base_url() ?>dashboard/Crefund/new_return" method="get">

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-bd lobidrag">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4><?php echo display('new_return') ?></h4>
                            </div>
                        </div>
                        <div class="panel-body">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo display('invoice_no') ?>:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="invoice_no" id="invoice_no"
                                                value="<?php echo set_value('invoice_no', @$_GET['invoice_no']) ?>"
                                                placeholder='<?php echo display('invoice_no') ?>' >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="payment_id" class="col-sm-4 col-form-label"><?php echo display('bank_list') ?>
                                            <i class="text-danger">*</i></label>
                                        <div class="col-sm-8">
                                             <select class="form-control" name="payment_id" id="payment_id">
                                                                <option value=""></option>
                                                                <?php
                                                                if ($payment_info) {
                                                                    foreach ($payment_info as $payment_method) {
                                                                ?>
                                                                        <option value="<?php echo html_escape($payment_method->HeadCode); ?>">
                                                                            <?php echo html_escape($payment_method->HeadName); ?>
                                                                        </option>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt_10">
                            <table class="table table-bordered table-hover" id="normalinvoice">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo display('item_information') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center" width="130"><?php echo display('size') ?> <i class="text-danger">*</i></th>
                                       
                                        <th class="text-center"><?php echo display('status') ?></th>
                                      
                                        <th class="text-center"><?php echo display('available_quantity') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center"><?php echo display('quantity') ?> <i class="text-danger">*</i></th>
                                        
                                    </tr>
                                </thead>
                                <tbody id="addinvoiceItem">
                                    <tr>
                                       <td>
                                             <select class="form-control" id="product_id_1" onchange="get_variant(1)" required="required" name="product_id[]">
                                             </select>
                                                        
                                        </td>

                                        <td class="text-center">
                                            
                                                            <select class="form-control" id="variant_id_1" onchange="get_qnty(1)" required="required" name="variant_id[]">

                                                            </select>
                                                       
                                        </td>

                                        <td class="text-center">
                                            
                                                            <select class="form-control" id="status_1" required="required" name="status[]">
                                                                <option value="0"><?php echo display('fit') ?></option>
                                                                <option value="1"><?php echo display('damaged') ?></option>
                                                                <option value="2"><?php echo display('no warranty') ?></option>
                                                            </select>
                                                       
                                        </td>

                                        <td>
                                            <input type="text" id="available_quantity_1" name="available_quantity[]" class="form-control text-right available_quantity_1" id="avl_qntt_1" placeholder="0" readonly="" />
                                        </td>

                                        <td><input type="number" class="form-control" id="quantity_1" required="required" min="0" value="0" max="0" name="quantity[]"></td>

                                        <td>
                                            <button class="btn btn-danger text-right" type="button" value="<?php echo display('delete') ?>" onclick="deleteRow(this)"><?php echo display('delete') ?>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>

                                   
                                    <tr>
                                        <td class="text-center" colspan="1">
                                            <input type="button" id="add-invoice-item" class="btn btn-info color4 color5" name="add-invoice-item" onClick="addInputField2('addinvoiceItem');" value="<?php echo display('add_new_item') ?>" />
                                        </td>
                                      
                                    </tr>

                                </tfoot>
                            </table>
                        </div>
                <!-- view -->
                
                <script src="<?php echo MOD_URL . 'dashboard/assets/js/add_invoice_form_2.js'; ?>"></script>
                <div style="text-align: center;">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            
        </form>
    </section>
</div>
<!-- Invoice Report End -->