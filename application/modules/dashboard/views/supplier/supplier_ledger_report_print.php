<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Supplier Ledger Start -->
<div class="content-wrapper">
    <!-- Supplier information -->
    <section class="content">

        <!-- Supplier select -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php echo form_open('dashboard/Csupplier/supplier_ledger_report', array('class' => 'form-inline')); ?>
                        <div class="form-group">
                            <label for="supplier_name"><?php echo display('select_supplier') ?><span class="text-danger">*</span>:</label>
                            <select class="form-control" name="supplier_id" id="supplier_id">
                                <?php foreach ($suppliers_list as $sub) : ?>
                                    <option value=""></option>
                                    <option value="<?= $sub['supplier_id'] ?>" <?= $supplier_id == $sub['supplier_id'] ? 'selected' : '' ?>><?= $sub['supplier_name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="from_date"><?php echo display('from_date') ?><span class="text-danger">*</span>:</label>
                            <input type="text" class="form-control datepicker2" autocomplete="off" placeholder="<?php echo display('from_date'); ?>" name="from_date" value="<?= $from_date ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="to_date"><?php echo display('to_date') ?><span class="text-danger">*</span>:</label>
                            <input type="text" class="form-control datepicker2" autocomplete="off" placeholder="<?php echo display('to_date'); ?>" name="to_date" value="<?= $to_date ?>" required>
                        </div>
                        <button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
                        <button type="submit" formaction="<?= base_url('dashboard/Ccustomer/customer_ledger_print') ?>" class="btn btn-primary"><?php echo display('print') ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if ($supplier_name) {
        ?>

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-bd lobidrag">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4><?php echo display('supplier_information') ?></h4>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="ft_left">
                                <h4>{supplier_name}</h4>
                            </div>
                            <div class="ft_right mr_100">
                                <table class="table table-striped table-condensed table-bordered">
                                    <tr>
                                        <td> <?php echo display('debit_ammount') ?> </td>
                                        <td> <?php
                                                if ($total_debit) {
                                                    echo (($position == 0) ? "$currency {total_debit}" : "{total_debit} $currency");
                                                } else {
                                                    echo (($position == 0) ? "$currency 00" : " 00 $currency");
                                                }
                                                ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo display('credit_ammount') ?></td>
                                        <td class="balance_txt">
                                            <?php
                                            if ($total_credit) {
                                                echo (($position == 0) ? "$currency {total_credit}" : "{total_credit} $currency");
                                            } else {
                                                echo (($position == 0) ? "$currency 00" : " 00 $currency");
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo display('balance_ammount') ?> </td>
                                        <td class="balance_txt">
                                            <?php
                                            if ($total_balance) {
                                                echo (($position == 0) ? "$currency {total_balance}" : "{total_balance} $currency");
                                            } else {
                                                echo (($position == 0) ? "$currency 00" : " 00 $currency");
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manage Supplier -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-bd lobidrag">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h4><?php echo display('supplier_ledger') ?></h4>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="dataTableExample2" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo display('date') ?></th>
                                            <th><?php echo display('transaction_type') ?></th>
                                            <th class="text-right mr_20"><?php echo display('debit') ?></th>
                                            <th class="text-right mr_20"><?php echo display('credit') ?></th>
                                            <th class="text-right mr_20"><?php echo display('balance') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($ledger) {
                                            $total_sup_debit = $total_sup_credit = $total_sup_balance = 0;
                                            foreach ($ledger as $key => $ledger) {
                                                $invoice = $this->db->select('invoice')->from('product_purchase')->where('purchase_id', $ledger['purchase_id'])->get()->row();
                                        ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <?= date('d-m-Y', strtotime($ledger['sl_created_at'])) ?>
                                                    </td>
                                                    <td>
                                                        <?php if (empty($invoice)) : ?>
                                                            <?php
                                                            if (empty($ledger['voucher'])) {
                                                                echo display('previous_balance');
                                                            } else {
                                                                if ($ledger['voucher'] == 'JV') {
                                                                    echo display('journal_voucher');
                                                                }
                                                            }
                                                            ?>
                                                        <?php else : ?>
                                                            <a href="<?php echo base_url() . 'dashboard/Cpurchase/purchase_details_data/' . $ledger['purchase_id']; ?>"><?php echo $invoice->invoice; ?>
                                                                <i class="fa fa-tasks pull-right" aria-hidden="true"></i></a>
                                                        <?php endif ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <?php $total_sup_debit += ((empty($ledger['debit'])) ? 0 : $ledger['debit']);
                                                        echo (($position == 0) ? $currency . " " . ((empty($ledger['debit'])) ? 0 : $ledger['debit']) : ((empty($ledger['debit'])) ? 0 : $ledger['debit']) . " " . $currency)
                                                        ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <?php $total_sup_credit += ((empty($ledger['credit'])) ? 0 : $ledger['credit']);
                                                        echo (($position == 0) ? $currency . " " . $ledger['credit'] : $ledger['credit'] . " " . $currency)
                                                        ?>
                                                    </td>
                                                    <td class="text-right">
                                                        <?php $total_sup_balance += ((empty($ledger['balance'])) ? 0 : $ledger['balance']);
                                                        echo (($position == 0) ? $currency . " " . $ledger['balance'] : $ledger['balance'] . " " . $currency)
                                                        ?>
                                                    </td>
                                                    <!-- <td><?php
                                                                if (!empty($transaction_info)) {
                                                                    echo html_escape($transaction_info->Narration);
                                                                }
                                                                ?>
                                                    </td> -->
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>--</td>
                                            <th class="text-center"><?= display('grand_total') ?></th>
                                            <th class="text-center"><?php echo html_escape($total_sup_debit); ?></th>
                                            <th class="text-center"><?php echo html_escape($total_sup_credit); ?></th>
                                            <th class="text-center"><?php echo html_escape($total_sup_debit - $total_sup_credit); ?></th>
                                        </tr>
                                    </tfoot>
                                <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </section>
</div>
<!-- Supplier Ledger End -->
<script>
    $(document).ready(function() {
        $(".datepicker2").datepicker({
            dateFormat: "dd-mm-yy"
        });
    });
</script>