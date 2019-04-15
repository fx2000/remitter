<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Edit Product',array('controller'=>'AppSetting','action'=>'edit_products',$this->request->params['pass'][0]));?>
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
		<div class="box span12">
		       <div class="box-header well" data-original-title>
						<h2><i class="icon-list-alt"></i> Edit Product</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',['class'=>'form-horizontal','enctype'=>'multipart/form-data']);?>
			         <fieldset>
							<div class="control-group">
								  <label class="control-label">Select Operator</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Product.operator_id', array('type' => 'select','options' => $operators,'empty'=>'Select Operator','id'=>"operator",'label'=>false))?>
								  <script language="javascript" type="text/javascript">var f1 = new LiveValidation('operator');f1.add( Validate.Presence);</script>
                                                                   </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Amount</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Product.amount',array('type'=>'text','class'=>'input-large ','id'=>'amount','div'=>false,'label'=>false,'maxlength'=>100,'data-rel'=>'tooltip','data-original-title'=>'Amount'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('amount');f1.add( Validate.Presence);f1.add( Validate.NumberValidFloat)</script>
								  </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Choose Barcode Image</label>
								   <div class="controls">
								    <input type="file" id="barcodeimg" name="Product[file]" class="file" >
                                                                    
								  </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Barcode</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Product.barcode_no',array('type'=>'text','class'=>'input-large ','id'=>'barcodeno','div'=>false,'label'=>false,'maxlength'=>100,'data-rel'=>'tooltip','data-original-title'=>'Barcode'));?>
                                                                    <script language="javascript" type="text/javascript">var f1 = new LiveValidation('barcodeno');f1.add( Validate.Presence);f1.add( Validate.BarcodeVal);f1.add( Validate.NumberValid);</script>
								  </div>
							</div>
                                                        <div class="control-group">
							<label class="control-label">Status</label>
							<div class="controls">
							
							<?php echo $this->Form->input('Product.status',array('type'=>'checkbox','class'=>'iphone-toggle ','id'=>'status','div'=>false,'label'=>false,'data-no-uniform'=>'true'));?>
							</div>
                                                        </div>
							<div class="form-actions">
							  <?php echo $this->Form->Submit('Submit',array('class'=>'btn btn-primary'));?>
							</div>
						    </div>
				</fieldset>
			</div>
		</div>
</div>