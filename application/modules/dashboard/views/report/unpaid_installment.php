<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Stock List Start -->
<div class="content-wrapper">
	<section class="content-header">
	    <div class="header-icon">
	        <i class="pe-7s-note2"></i>
	    </div>
	    <div class="header-title">
	        <h1><?php echo display('unpaid_installment') ?></h1>
	        <small><?php echo display('unpaid_installment') ?></small>
	        <ol class="breadcrumb">
	            <li><a href="#"><i class="pe-7s-home"></i> <?php echo display('home') ?></a></li>
	            <li><a href="#"><?php echo display('report') ?></a></li>
	            <li class="active"><?php echo display('unpaid_installment') ?></li>
	        </ol>
	    </div>
	</section>

	<section class="content">

		<div class="row">
            <div class="col-sm-12">
                <!-- <div class="column">
                	<?php if($this->permission->check_label('stock_report_supplier_wise')->read()->access()){ ?>
                  <a href="<?php echo base_url('dashboard/Creport/stock_report_supplier_wise')?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"></i> <?php echo display('stock_report_supplier_wise')?></a>  
                  	<?php }?>
                  	<?php if($this->permission->check_label('stock_report_product_wise')->read()->access()){ ?>
                  <a href="<?php echo base_url('dashboard/Creport/stock_report_product_wise')?>" class="btn btn-success m-b-5 m-r-2"><i class="ti-align-justify"></i> <?php echo display('stock_report_product_wise')?></a>  
	                <?php }?>
                </div> -->
            </div>
        </div>


		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel panel-bd lobidrag">
		            <div class="panel-heading">
		                <div class="panel-title">
		                    <h4><?php echo display('out_of_stock') ?></h4>
		                </div>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive mt_10">
		                    <table id="" class="table table-bordered table-striped table-hover">
		                        <thead>
									<tr>
										<th class="text-center"><?php echo display('sl') ?></th>
										<th class="text-center"><?php echo display('customer_name') ?></th>
										<th class="text-center"><?php echo display('invoice_no') ?></th>
										<th class="text-center"><?php echo display('payment_amount') ?></th>
										<th class="text-center"><?php echo display('due_date') ?></th>
									</tr>
								</thead>
								<tbody>
								<?php
									if ($unpaid_installments) {
								?>
                                <?php foreach ($unpaid_installments as $d) : $d = (object) $d;?>
                                    <tr class="text-center">
										<td ><?=$d->sl?></td>
										<td>
											<a href="<?php echo base_url().'dashboard/Ccustomer/customerledger/' . $d->customer_id; ?>">
											<?=$d->customer_name?>
											</a>	
										</td>
										<td>
                                            <a href="<?=base_url()?>dashboard/Cinvoice/invoice_inserted_data/<?=$d->invoice_id?>">
                                            <?=$d->invoice?>
                                        </a>
                                            </td>
										<td>
                                            <a href="<?=base_url()?>dashboard/Cinstallment/installment_update_form/<?=$d->invoice_id?>">
                                                <?=$d->amount?>
                                            </a>
                                        </td>
										<td><?=$d->due_date?></td>
									</tr>
                                <?php endforeach ?>
								
								<?php
									}
								?>
								</tbody>
		                    </table>
		                </div>
			            
		                <div class="text-center">
		                	 <?php if (isset($link)) { echo $link ;} ?>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</section>
</div>
<!-- Stock List End -->