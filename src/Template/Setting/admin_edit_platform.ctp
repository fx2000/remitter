<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Edit Platform',array('controller'=>'Setting','action'=>'edit_platform',$this->request->params['pass'][0]));?>
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
						<h2><i class="icon-list-alt"></i> Edit Platform</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',array('url'=>array('controller'=>'Setting','action'=>'edit_platform',$this->request->params['pass'][0]),'class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
			         <fieldset>
							<div class="control-group">
								  <label class="control-label">Operator</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Operator.name',array('type'=>'text','class'=>'input-large ','readonly'=>'readonly','id'=>'operator','div'=>false,'label'=>false,'maxlength'=>100,'data-rel'=>'tooltip','data-original-title'=>'Operator'));?>

                                                                    <?php echo $this->Form->input('OperatorCredential.operator_id',array('type'=>'hidden','value'=>$this->request->data['Operator']['id'],'class'=>'input-large ','readonly'=>'readonly','id'=>'operator_id','div'=>false,'label'=>false,'maxlength'=>100,'data-rel'=>'tooltip','data-original-title'=>'Operator'));?>
								  <script language="javascript" type="text/javascript">var f1 = new LiveValidation('operator');f1.add( Validate.Presence);</script>
                                                                   </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Product Id</label>
								   <div class="controls">
								    <?php echo $this->Form->input('OperatorCredential.product_id',array('type'=>'text','class'=>'input-large ','id'=>'product_id','div'=>false,'label'=>false,'maxlength'=>100,'data-rel'=>'tooltip','data-original-title'=>'Product Id'));?>
								  <script language="javascript" type="text/javascript">var f1 = new LiveValidation('product_id');f1.add( Validate.Presence);f1.add( Validate.NumberValid);</script>
                                                                   </div>
							</div>
							<div class="control-group">
								  <label class="control-label">IP Address</label>
								   <div class="controls">
								    <?php echo $this->Form->input('OperatorCredential.ip_address',array('type'=>'text','class'=>'input-large ','id'=>'ipaddress','div'=>false,'label'=>false,'maxlength'=>100,'data-rel'=>'tooltip','data-original-title'=>'IP Address'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('ipaddress');f1.add( Validate.Presence);f1.add(Validate.ValidIP)</script>
								  </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Port</label>
								   <div class="controls">
								    <?php echo $this->Form->input('OperatorCredential.port',array('type'=>'text','class'=>'input-large ','id'=>'port','div'=>false,'label'=>false,'maxlength'=>100,'data-rel'=>'tooltip','data-original-title'=>'Port'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('port');f1.add( Validate.Presence);f1.add( Validate.NumberValid)</script>
								  </div>
							</div>
                                                        <div class="control-group">
							<label class="control-label">Username</label>
							<div class="controls">
							
							<?php echo $this->Form->input('OperatorCredential.username',array('type'=>'text','class'=>'input-large ','id'=>'username','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Username'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('username');f1.add( Validate.Presence);</script>
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