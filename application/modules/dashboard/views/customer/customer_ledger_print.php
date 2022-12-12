<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Customer Ledger Start -->
<style type="text/css">
	* {
		font-family: 'Roboto', sans-serif;
	}

	@media print {
		table tbody tr:nth-child(even) td {
			/* background-color: #f9f9f9 !important;
            -webkit-print-color-adjust: exact; */
		}

		.panel-body {
			/* font-size: 10px; */
		}

		img:not(.show) {
			display: none;
		}

		.content-header,
		.logo,
		.panel-footer,
		.main-header,
		.main-sidebar {
			display: none;
		}

		.cominfo_div {
			display: inline-block;
			width: 30%;
		}

		.cus_div {
			display: inline-block;
			margin-left: 4%;
			width: 25%;
			margin-top: 6%
		}

		.qr_div {
			display: width: 10%;
		}

		.width_30p {
			width: 30%;
		}

		.width_70p {
			width: 70%;
		}

		.thead tr,
		.borderd {
			border: 2px solid orange !important;
			color: orange !important;
		}

		.colored>tbody>tr>th,
		.colored>tbody>tr>td {
			border-top: 1px solid orange;
			border-color: orange !important;
			color: orange !important;
		}

		.line-height {
			line-height: .5rem !important;
		}

		#toTop,
		footer,
		.btn.back-top,
		.hide-me,
		.pace,
		.pace-activity {
			display: none;
		}

		div.divFooter {
			position: fixed;
			bottom: 0;
		}

		.empty-footer {
			height: 100px
		}

		.footerr {
			position: fixed;
			height: 100px;
		}

		.footerr {
			bottom: 35px;
		}
	}

	.thead tr,
	.borderd {
		border: 2px solid orange !important;
		color: orange !important;
	}

	.thead tr th {
		color: orange !important;
	}

	.colored>tbody>tr>th,
	.colored>tbody>tr>td {
		border-top: 1px solid orange;
		border-color: orange !important;
		color: orange !important;
	}

	.thead tr th {
		text-transform: uppercase;
	}

	.line-height {
		line-height: 1rem;
	}
</style>
<style>
	.payment_type+.select2,
	.account+.select2 {
		margin-top: 10px;
		width: 180px !important;
	}

	.account_no {
		width: 180px;
	}

	@media print {

		img,
		.content-header,
		.alert,
		.main-header,
		.panel-heading.ui-sortable-handle,
		.footer-btns,
		footer,
		.footer,
		#toTop,
		footer,
		.btn.back-top,
		.hide-me,
		.pace,
		.pace-activity,
		form,
		.print-none {
			display: none !important;
		}

		.hideme table,
		.hideme tr,
		.hideme td,
		.hideme th {
			border: none !important;
		}

		.hideme .table>tbody>tr>td,
		.hideme .table>tbody>tr>th,
		.hideme .table>tfoot>tr>td,
		.hideme .table>tfoot>tr>th,
		.hideme .table>thead>tr>td,
		.hideme .table>thead>tr>th {
			padding: 4px;
		}

		.hideme .form-group {
			margin-bottom: 5px;
		}

		#paid_amountt,
		#total-still {
			background-color: #811fdb47;
			background-color: #811fdb47 !important;
			-webkit-print-color-adjust: exact;
			/* font-weight: bold; */
			padding: 3px 5px;
			border-radius: 6px;
		}
	}


	@media screen {
		.hideme {
			display: none;
		}

		#paid_amountt,
		#total-still {
			background-color: #811fdb47;
			/* font-weight: bold; */
			padding: 3px 5px;
			border-radius: 6px;
		}
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<div class="header-icon">
			<i class="pe-7s-note2"></i>
		</div>
		<div class="header-title">
			<h1><?php echo display('customer_ledger') ?></h1>
			<small><?php echo display('manage_customer_ledger') ?></small>
			<ol class="breadcrumb">
				<li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
				<li><a href="#"><?php echo display('customer') ?></a></li>
				<li class="active"><?php echo display('customer_ledger') ?></li>
			</ol>
		</div>
	</section>

	<!-- Customer information -->
	<section class="content">
		<div class="row print-none">
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<?php echo form_open("dashboard/Ccustomer/customer_ledger_report", array('class' => 'form-inline')); ?>
						<div class="form-group">
							<label for="customer_id"><?php echo display('customer') ?><span class="text-danger">*</span>:</label>
							<select class="form-control" name="customer_id" id="customer_id" required>
								<?php foreach ($customers_list as $cust) : ?>
									<option value=""></option>
									<option value="<?= $cust['customer_id'] ?>" <?= isset($customer_id) && $customer_id == $cust['customer_id'] ? 'selected' : '' ?>><?= $cust['customer_name'] ?></option>
								<?php endforeach ?>
							</select>
						</div>
						<div class="form-group">
							<label for="from_date"><?php echo display('from_date') ?><span class="text-danger">*</span>:</label>
							<input type="text" class="form-control datepicker2" autocomplete="off" placeholder="<?php echo display('from_date'); ?>" name="from_date" value="<?= $this->input->post('from_date', TRUE); ?>" required>
						</div>
						<div class="form-group">
							<label for="to_date"><?php echo display('to_date') ?><span class="text-danger">*</span>:</label>
							<input type="text" class="form-control datepicker2" autocomplete="off" placeholder="<?php echo display('to_date'); ?>" name="to_date" value="<?= $this->input->post('to_date', TRUE); ?>" required>
						</div>
						<button type="submit" class="btn btn-success"><?php echo display('search') ?></button>
						<button type="submit" formaction="<?= base_url('dashboard/Ccustomer/customer_ledger_print') ?>" class="btn btn-primary"><?php echo display('print') ?></button>
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
							<h4><?php echo display('customer_ledger') ?></h4>
						</div>
					</div>
					<div class="panel-body">
						<table class="table " style="border: 0;" border="0">
							<thead>
								<tr>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="2" style="padding: 0;">
										<div class="" style="width: 100%">
											<img class="show" src="<?= base_url() ?>/assets/img/header.png" style="width: 100%;height: auto;" />
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 30px;padding-top: 50px;">
										<div class="">
											<?php echo display('customer_name') ?>:&nbsp;&nbsp; <h4 style="display: inline;"><?php echo html_escape($customer_name); ?></h4>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 30px;">
										<div class="">
											<?php echo display('from_date') ?>:&nbsp;&nbsp; <?= $this->input->post('from_date', TRUE); ?>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-left: 30px;">
										<div class="">
											<?php echo display('to_date') ?>:&nbsp;&nbsp; <?= $this->input->post('to_date', TRUE); ?>
										</div>
									</td>
								</tr>
								<tr>
									<div class="table-responsive">
										<table id="" class="table table-bordered table-striped table-hover">
											<thead>
												<tr>
													<th><?php echo display('date') ?></th>
													<th><?php echo display('transaction_type') ?></th>
													<!-- <th class="hide-me"><?php echo display('receipt_no') ?></th>
													<th class="hide-me"><?php echo display('description') ?></th> -->
													<th class="text-right mr_20"><?php echo display('debit') ?></th>
													<th class="text-right mr_20"><?php echo display('credit') ?></th>
													<th class="text-right mr_20"><?php echo display('balance') ?></th>
												</tr>
											</thead>
											<tbody>
												<?php

												if ($ledger) {
												?>
													<?php foreach ($ledger as $v_ledger) { ?>
														<tr>
												<td>
													<!-- doc no -->
													<?php
													if ($v_ledger['voucher'] == 'Pb') {
														echo "Pb / " . $v_ledger['c_id'];
													} elseif ($v_ledger['voucher'] == 'Rdv') {
														echo "Rdv / " . $v_ledger['c_id'];
													} elseif ($v_ledger['voucher'] == 'Rcv') {
														echo "Rcv / " . $v_ledger['c_id'];
													} elseif ($v_ledger['voucher'] == 'SalRe') {
														echo "SalRe / " . $v_ledger['c_id'];
													} elseif ($v_ledger['voucher'] == 'Sal') {
														echo "Sall / " . $v_ledger['c_id'];
													} else {
														echo "Pb / " . $v_ledger['c_id'];
													}
													?>
												</td>
												<td class="text-right">
													<?php
													echo (($position == 0) ? $currency . ' ' . (float)$v_ledger['debit'] : (float)$v_ledger['debit'] . ' ' . $currency) ?>
												</td>
												<td class="text-right"> <?php echo (($position == 0) ? $currency . ' ' . (float)$v_ledger['credit'] : (float)$v_ledger['credit'] . ' ' . $currency) ?></td>
												<td class="text-right"> <?php echo (($position == 0) ? $currency . ' ' . $v_ledger['balance'] : $v_ledger['balance'] . ' ' . $currency) ?></td>
												<td><?php echo date('d-m-Y', strtotime($v_ledger['cl_created_at'])); ?></td>
												<td dir="rtl" align="center">
													<?php echo str_replace('PLHH', $v_ledger['c_id'], $v_ledger['details']);?>
												</td>
											</tr>

												<?php
													}
												}
												?>
											</tbody>
										</table>
									</div>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<th>
										<div class="empty-footer"></div>
									</th>
								</tr>
							</tfoot>
						</table>
						<div class="footerr row position-relative">
							<div class="col-xs-12 divFoote" style="background-image: url();">
								<img class="show" src="<?= base_url() ?>/assets/img/footer.png" style="width: 100%;height: auto;" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<a class="btn btn-info print-btn" href="javascript:void(0)" onclick="window.print()"><span class="fa fa-print"></span>
		</div>

	</section>
</div>
<!-- Customer Ledger End  -->
<script>
	$(document).ready(function() {
		$(".datepicker2").datepicker({
			dateFormat: "dd-mm-yy"
		});
	});
</script>