<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Retailer  Email  Delivery Report Settings',array('controller'=>'Setting','action'=>'report_setting'));?>
		</li>
	</ul>
</div>
	<?php if($this->Session->read('alert')!='') { ?>
<div class="alert <?php echo ($this->Session->read('success')==1)?'alert-success':'alert-error'?>">
	<button type="button" class="close" data-dismiss="alert">x</button>
	<strong>
	<?php 
		echo $this->Session->read('alert');
		$_SESSION['alert']='';
		?>
	</strong>
</div>
<?php } ?>
<style>
 .checkbox.inline + .checkbox.inline {
     margin-left: 0px;
}
</style>

<div class="row-fluid ">	
		<div class="box span12">
		       <div class="box-header well" data-original-title>
						<h2><i class="icon-list-alt"></i> Add Retailer  Email  Delivery Report Settings</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',array('url'=>array('controller'=>'Setting','action'=>'report_setting'),'class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
			         <fieldset>
							<div class="control-group">
									<label class="control-label">Retailer</label>
								   <div class="controls">
									<?php echo $this->Form->input('RetailerReportSetting.retailer_id', array('type' => 'select','options' => $retailers,'empty'=>'Select Retailer','id'=>"retailer",'label'=>false ))?>
									<script language="javascript" type="text/javascript">var f1 = new LiveValidation('retailer');f1.add( Validate.Presence);</script>
								  </div>
							</div>
							<div class="control-group">
									<label class="control-label">Reports</label>
									<div class="controls">
										  <label class="checkbox ">
											<?php echo $this->Form->input('RetailerReportSetting.trans_report',array('type'=>'checkbox','class'=>'input-large ','id'=>'trans_report','div'=>false,'label'=>false));?> Transaction Report
										  </label>
										   <label class="checkbox ">
											<?php echo $this->Form->input('RetailerReportSetting.sale_report',array('type'=>'checkbox','class'=>'input-large ','id'=>'sale_report','div'=>false,'label'=>false));?> Sale Detail
										  </label>
										   <label class="checkbox ">
											<?php echo $this->Form->input('RetailerReportSetting.retailer_sale_report',array('type'=>'checkbox','class'=>'input-large ','id'=>'retailer_sale_report','div'=>false,'label'=>false));?> Sale Detail(Retailer)
										  </label>
										   <label class="checkbox ">
											<?php echo $this->Form->input('RetailerReportSetting.store_sale_report',array('type'=>'checkbox','class'=>'input-large ','id'=>'store_sale_report','div'=>false,'label'=>false));?> Sale Detail(Store)
										  </label>
										   <label class="checkbox ">
											<?php echo $this->Form->input('RetailerReportSetting.user_sale_report',array('type'=>'checkbox','class'=>'input-large ','id'=>'user_sale_report','div'=>false,'label'=>false));?> Sale Detail(User)
										  </label>
										   <label class="checkbox ">
											<?php echo $this->Form->input('RetailerReportSetting.account_inventory_report',array('type'=>'checkbox','class'=>'input-large ','id'=>'account_inventory_report','div'=>false,'label'=>false));?> Inventory (Accounts)
										  </label>
										   <label class="checkbox ">
											<?php echo $this->Form->input('RetailerReportSetting.account_movement_report',array('type'=>'checkbox','class'=>'input-large ','id'=>'account_movement_report','div'=>false,'label'=>false));?> Inventory (Movements)
										  </label>
										   <label class="checkbox ">
											<?php echo $this->Form->input('RetailerReportSetting.account_deposit_report',array('type'=>'checkbox','class'=>'input-large ','id'=>'account_deposit_report','div'=>false,'label'=>false));?> Inventory (Deposits)
										  </label>
										
									</div>
                            </div>
                            <div class="control-group">
									<label class="control-label">Duration</label>
								   <div class="controls">
									<?php 
									$time = array('1'=>'Daily','2'=>'Weekly');
									echo $this->Form->input('RetailerReportSetting.time', array('type' => 'select','options' => $time,'empty'=>'Select Duration','id'=>"time",'label'=>false ))?>
									<script language="javascript" type="text/javascript">var f1 = new LiveValidation('time');f1.add( Validate.Presence);</script>
								  </div>
							</div>
							<div class="form-actions">
							  <?php echo $this->Form->Submit('Submit',array('class'=>'btn btn-primary'));?>
							</div>
						    </div>
				</fieldset>
			</div>
			
			
<div class="row-fluid ">		
		<div class="box span12">
		     <div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i>View Retailer  Email  Delivery Report Settings</h2>
		      </div>
		<div class="box-content">
                   <table class="table table-striped table-bordered bootstrap-datatable AccountDataTable">
			<thead>
				 <tr>
					<th>Retailer</th>
					<th >Transaction</th>
					<th>Sale Detail</th>
                    <th>Sale Detail(Retailer)</th>
                    <th>Sale Detail(Store)</th>
                    <th>Sale Detail(User)</th>                    
					<th>Inventory(Accounts)</th>
					<th>Inventory(Movements)</th>
					<th>Inventory(Deposits)</th>
					<th>Duration</th>
					<th>Action</th>                 
				</tr>
			</thead>
			 <tbody>
                                <?php if(!empty($settings)) {
                                       foreach($settings as $account){
 				?>
                                         <tr>
						<td><?php echo $account['Retailer']['name'];?></td>
						<td><?php echo ($account['RetailerReportSetting']['trans_report']==1)?'Yes':'No';?></td>
						<td><?php echo ($account['RetailerReportSetting']['sale_report']==1)?'Yes':'No';?></td>
						<td><?php echo ($account['RetailerReportSetting']['retailer_sale_report']==1)?'Yes':'No';?></td>
						<td><?php echo ($account['RetailerReportSetting']['store_sale_report']==1)?'Yes':'No';?></td>
						<td><?php echo ($account['RetailerReportSetting']['user_sale_report']==1)?'Yes':'No';?></td>
						<td><?php echo ($account['RetailerReportSetting']['account_inventory_report']==1)?'Yes':'No';?></td>
						<td><?php echo ($account['RetailerReportSetting']['account_movement_report']==1)?'Yes':'No';?></td>
						<td><?php echo ($account['RetailerReportSetting']['account_deposit_report']==1)?'Yes':'No';?></td>
						<td><?php echo ($account['RetailerReportSetting']['time']==1)?'Daily':'Weekly';?></td>
                                               
						<td class="center">
							<?php 
									echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">Edit</span> ',array('controller'=>'Setting','action'=>'report_setting',base64_encode($account['RetailerReportSetting']['id'])),array('class'=>'btn btn-small','escape'=>false));
									echo $this->html->link('<i class="icon-trash icon-black"></i><span class="hidden-phone">Delete</span> ',array('controller'=>'Setting','action'=>'delete_report_setting',base64_encode($account['RetailerReportSetting']['id'])),array('class'=>'btn btn-small','escape'=>false));
									
								?>
                         </td>
					</tr>	
                                <?php }
				  }	
				?>			
			</tbody>   
		   <table>
		</div>
	</div>
</div>
		</div>
		
		
		
</div>


