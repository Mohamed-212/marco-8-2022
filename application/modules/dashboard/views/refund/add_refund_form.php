<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
    $randStr = bin2hex(random_bytes(20));
?>
<!-- Customer js php -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/customer_add_refund.js.php?t=<?=$randStr?>"></script>
<!-- Product invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/json/product_invoice.js.php?t=<?=$randStr?>"></script>
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
                                <div class="col-sm-6" id="payment_from_1">
                                    <div class="form-group row">
                                        <label for="customer_name" class="col-sm-4 col-form-label"><?php echo display('customer_name') ?> <i class="text-danger">*</i></label>
                                        <div class="col-sm-8">
                                            <input type="text" size="100" value="<?php echo html_escape($customer['customer_name']); ?>" name="customer_name" required class="customerSelection form-control" placeholder='<?php echo display('customer_name_or_phone'); ?>' id="customer_name" autocomplete="off" />
                                            <input id="SchoolHiddenId" value="<?php echo html_escape($customer['customer_id']) ?>" class="customer_hidden_value" type="hidden" name="customer_id" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label"><?php echo display('invoice_no') ?>:</label>
                                        <div class="col-sm-8">
                                            <!-- <input type="text" class="form-control" name="invoice_no" id="invoice_no" value="<?php echo set_value('invoice_no', @$_GET['invoice_no']) ?>" placeholder='<?php echo display('invoice_no') ?>'> -->
                                            <input type="hidden" class="form-control" name="invoice_id" id="invoice_id" value="">
                                            <select class="form-control" name="invoice_no" id="invoice_no">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="payment_type" class="col-sm-4 col-form-label"><?php echo display('payment_type') ?>
                                            <i class="text-danger">*</i></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="payment_type" id="payment_type">
                                                <option value="1" selected><?= display('bank_list') ?></option>
                                                <option value="2"><?= display('customer_balance') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6" id="bank_list_container">
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

                                            <th class="text-center" id="trans" data-fit="<?=display('fit')?>" data-warranty="<?=display('no warranty')?>"  data-damaged="<?=display('damaged')?>"><?php echo display('status') ?></th>

                                            <th class="text-center"><?php echo display('available_quantity') ?> <i class="text-danger">*</i></th>
                                            <th class="text-center"><?php echo display('quantity') ?> <i class="text-danger">*</i></th>

                                        </tr>
                                    </thead>
                                    <tbody id="addinvoiceItem">

                                    </tbody>
                                    <tfoot>




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