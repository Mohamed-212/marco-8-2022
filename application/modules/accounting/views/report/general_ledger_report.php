<script src="<?php

                use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Offset;

                echo MOD_URL . 'dashboard/assets/js/print.js'; ?>"></script>
<!-- Sales Report Start -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="header-icon"><i class="pe-7s-note2"></i></div>
        <div class="header-title">
            <h1><?php echo display('general_ledger') ?></h1>
            <small><?php echo display('general_ledger_form') ?></small>
            <ol class="breadcrumb">
                <li><a href="index.html"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
                <li><a href="#"><?php echo display('accounts') ?></a></li>
                <li><a href="#"><?php echo display('account_reports') ?></a></li>
                <li class="active"><?php echo display('general_ledger') ?></li>
            </ol>
        </div>
    </section>
    <section class="content">
        <!-- General Ledger report -->
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bd lobidrag">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h4><?php echo display('general_ledger_report') ?> </h4>
                        </div>
                    </div>
                    <div class="panel-body" id="printableArea">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="g_l_date_range">
                                    <div class="col-md-12 text-right">
                                        <address>
                                            <strong><?php echo html_escape($company_info[0]['company_name']); ?></strong><br>
                                            <?php echo html_escape($company_info[0]['email']); ?>
                                        </address>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div id="purchase_div">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th><?php echo display('sl') ?></th>
                                                    <th><?php echo display('transection_date') ?></th>
                                                    <th><?php echo display('head_code') ?></th>
                                                    <?php if ($chkIsTransction) { ?>
                                                    <th><?php echo display('particulars') ?></th>
                                                    <?php } ?>
                                                    <th><?php echo display('debit') ?></th>
                                                    <th><?php echo display('credit') ?></th>
                                                    <th><?php echo display('balance') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $TotalCredit = 0;
                                                $TotalDebit = 0;
                                                $CurBalance = $prebalance;
                                                foreach ($HeadName2 as $key => $data) {
                                                ?>
                                                <tr>
                                                    <td height="25" align="center"><?php echo ++$key; ?></td>
                                                    <td align="center"><?php echo html_escape($data['VDate']); ?></td>
                                                    <td align="center"><?php echo html_escape($data['COAID']); ?></td>
                                                    <?php if ($chkIsTransction) { ?>
                                                    <td align="center"><?php echo html_escape($data['Narration']); ?>
                                                    </td>
                                                    <?php } ?>
                                                    <td align="right">
                                                        <?php echo number_format($data['Debit'], 2, '.', ','); ?></td>
                                                    <td align="right">
                                                        <?php echo number_format($data['Credit'], 2, '.', ','); ?></td>
                                                    <?php
                                                        $TotalDebit += $data['Debit'];
                                                        $CurBalance += $data['Debit'];
                                                        $TotalCredit += $data['Credit'];
                                                        $CurBalance -= $data['Credit'];
                                                        ?>
                                                    <td align="right">
                                                        <?php echo number_format($CurBalance, 2, '.', ','); ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr class="table_data">
                                                    <?php
                                                    if ($chkIsTransction)
                                                        $colspan = 4;
                                                    else
                                                        $colspan = 3;
                                                    ?>
                                                    <td colspan="<?php echo $colspan; ?>" align="right">
                                                        <strong><?php echo display('total') ?></strong>
                                                    </td>
                                                    <td align="right">
                                                        <strong><?php echo number_format($TotalDebit, 2, '.', ','); ?></strong>
                                                    </td>
                                                    <td align="right">
                                                        <strong><?php echo number_format($TotalCredit, 2, '.', ','); ?></strong>
                                                    </td>
                                                    <td align="right">
                                                        <strong><?php echo number_format($CurBalance, 2, '.', ','); ?></strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                            <div class="g_l_balance">
                                                <div class="col-sm-12">
                                                    <h3 class="g_v_h_balance">Pre Balance:
                                                        <?php echo number_format($prebalance, 2, '.', ','); ?></h3>
                                                </div>
                                                <div class="col-sm-12">
                                                    <h3 class="g_v_h_balance">Current Balance:
                                                        <?php echo number_format($CurBalance, 2, '.', ','); ?></h3>
                                                </div>
                                            </div>
                                            <div class="g_l_date_range">
                                                <div class="col-sm-12">
                                                    <h3 class="g_v_h_date_range text-center">General Ledger
                                                        of-<?php echo html_escape($ledger[0]['HeadName']) ?>(On
                                                        <?php echo html_escape($dateRange) ?>)</h3>
                                                </div>
                                            </div>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row g_l_date_range">
                        <div class="col-sm-12">
                            <div class="text-center" id="print">
                                <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print"
                                    onclick="printPageDiv('printableArea')">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- General Ledger Report End -->