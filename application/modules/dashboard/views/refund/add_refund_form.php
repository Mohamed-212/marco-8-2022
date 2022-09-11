<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Customer js php -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/customer.js.php"></script>
<!-- Product invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/product_invoice.js.php"></script>
<!-- Invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/invoice.js" type="text/javascript"></script>

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
                                        <label for="product_id" class="col-sm-4 col-form-label"><?php echo display('product') ?>
                                            <i class="text-danger">*</i></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="product_id" required="required" name="product_id">
                                               
                                            </select>
                                        </div>
                                    </div>
                                </div>                            
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="variant_id" class="col-sm-4 col-form-label"><?php echo display('variant') ?>
                                            <i class="text-danger">*</i></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="variant_id" required="required" name="variant_id">

                                            </select>
                                        </div>
                                    </div>
                                </div>                            
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="status" class="col-sm-4 col-form-label"><?php echo display('status') ?>
                                            <i class="text-danger">*</i></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="status" required="required" name="status">
                                                <option value="0"><?php echo display('fit') ?></option>
                                                <option value="1"><?php echo display('damaged') ?></option>
                                                <option value="2"><?php echo display('no warranty') ?></option>
                                            
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="quantity" class="col-sm-4 col-form-label"><?php echo display('quantity') ?>
                                            <i class="text-danger">*</i></label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" id="quantity" required="required" min="1" value="1" max="1" name="quantity">                                            
                                        </div>
                                    </div>
                                </div>
                            
                            
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