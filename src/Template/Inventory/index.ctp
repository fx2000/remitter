<?php 
$user_type = $this->Session->read('user_type');
?>
<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Inventory',array('controller'=>'inventory','action'=>'index'));?>
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
<div class="row-fluid ">
	<?php 
	$i=0;
	//print_r($userdata);
	foreach($userdata AS $data) {?>	
	<?php if($i==0 || $i%3==0) {
	?>
	<div class="row-fluid">
	<?php } ?>
	

		
		<div class="box span4">
			<div class="box-header well" data-original-title>
				<h2><i class="icon-th"></i> <?php echo $data->name?></h2>
			</div>
			<div class="box-content"  style="font-size:15px;">
                  		<h1><center>$<?php echo number_format($data->balance, 2, '.', '');?></center></h1>
                  	</div>
		</div><!--/span-->
		
	<?php if($i==2 || ($i-2)%3==0) {
	?>
	</div>
	<?php } ?>
	<?php $i++; }?>	
		
		<div class="box span4">
			<div class="box-header well" data-original-title>
				<h2><i class="icon-th"></i>Retailer Balance</h2>
			</div>
			<div class="box-content"  style="font-size:15px;">
                  		<h1><center>$<?php echo round($retaileramt,2)?></center></h1>
                  	</div>
		</div>
	<?php 
        if($user_type != 3)
        {
        ?>
	<div class="row-fluid">
		<div class="box span12">
			<div class="box-header well" data-original-title>
				<h2><i class="icon-th"></i> Add Airtime</h2>
			</div>
			<div class="box-content"  style="font-size:15px;">
                  		<?php echo $this->Form->create('',['class'=>'form-horizontal','name'=>'add_prepaid']);?>
				<div class="control-group">
					<label class="control-label">Operator</label>
					<div class="controls"> 
						<select name="Inventory[operator]" id="operator" class="input-medium" data-rel=tooltip data-original-title='Operator'>
							<option value="">Operator</option>
							<?php
								foreach($Operatordata AS $key=>$operator)
								{
									
									echo "<option value='".$key."'>".$operator."</option>";
								}
							?>
						</select>
						<script language="javascript" type="text/javascript">var f1 = new LiveValidation('operator');f1.add( Validate.Presence);</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Amount</label>
					<div class="controls"> 
						<input type="text" class="input-medium" id="amount" name="Inventory[amount]" data-rel='tooltip' data-original-title='Amount' placeholder="Amount" >&nbsp;
						<script language="javascript" type="text/javascript">var f1 = new LiveValidation('amount');f1.add( Validate.Presence);f1.add( Validate.NumberValidFloat);</script>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Document Number</label>
					<div class="controls"> 
						<input type="text" class="input-medium" id="document_no" name="Inventory[document_no]" data-rel='tooltip' data-original-title='Document Number' placeholder="Document Number" maxlength="255" >&nbsp;
						<script language="javascript" type="text/javascript">var f1 = new LiveValidation('document_no');f1.add( Validate.Presence);</script>
					</div>
				</div>	
				<div class="form-actions">
					<?php echo $this->Form->Button('Submit',array('class'=>'btn btn-primary','div'=>false));?>&nbsp;&nbsp;
				</div>
				<?php echo $this->Form->end();?>
                  	</div>
			
		</div><!--/span-->
			
	</div>
    
	<?php 
        }
        if(!empty($AccHistory)) { ?>
	<div class="row-fluid">
		<div class="box span12">
			<div class="box-header well" data-original-title>
				<h2><i class="icon-th"></i> Airtime Purchase History</h2>
			</div>
			<div class="box-content"  style="font-size:15px;">
                  		<table class="table table-striped table-bordered bootstrap-datatable">
					<thead>
						<tr>
							<th>Operator</th>
							<th>Amount</th>
							<th>Document Number</th>
							<th class="hidden-phone ">DateTime</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($AccHistory as $val){
						?>
							<tr>
								<td><?php echo $val['Operator']['name'];?></td>
								<td>$<?php echo $val['AirtimePurchaseHistory']['amount'];?></td>
								<td><?php echo $val['AirtimePurchaseHistory']['document_no'];?></td>
								<td  class="hidden-phone"><?php echo ($val['AirtimePurchaseHistory']['datetime']!='0000-00-00 00:00:00')?date('Y-m-d h:i:s A',strtotime($val['AirtimePurchaseHistory']['datetime'])):'N/A';?></td>
										
							</tr>	
						<?php 
						}	
						?>			
					</tbody>   
				<table>
                  	</div>
			<div class="box-content" >
				<?php 
                                if($user_type != 3)
                                echo $this->html->link('<i class="icon-download-alt icon-white"></i><span class="hidden-phone"> Export Purchase History in CSV</span> ',array('controller'=>'inventory','action'=>'exportHistory'),array('class'=>'btn btn-primary','escape'=>false));?>
			</div>
			
		</div><!--/span-->
			
	</div>
	
	<?php } ?>
</div>
