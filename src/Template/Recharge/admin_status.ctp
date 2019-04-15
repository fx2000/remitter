<?php ?>
<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Rechage Status',array('controller'=>'recharge','action'=>'status'));?>
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
						<h2><i class="icon-list-alt"></i> Check Status</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',array('url'=>array('controller'=>'recharge','action'=>'viewStatus'),'class'=>'form-horizontal'));?>
			         <fieldset>
							<div class="control-group">
								  <label class="control-label">Transaction ID</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Recharge.transcation_id',array('type'=>'text','class'=>'input-large ','id'=>'transcation_id','div'=>false,'label'=>false,'maxlength'=>100,'data-rel'=>'tooltip','data-original-title'=>'Transaction ID'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('transcation_id');f1.add( Validate.Presence);</script>
								  </div>
							</div>
							<div class="control-group">
									<label class="control-label">Operator</label>
									<div class="controls">
									 <?php 
									echo $this->Form->select('Recharge.operator',$Operatordata,array('empty'=>'Select','id'=>'operator','div'=>false,'label'=>false, 'data-rel'=>'tooltip','data-original-title'=>'Operator'));?>
 									<script language="javascript" type="text/javascript">var f1 = new LiveValidation('operator');f1.add( Validate.Presence);</script>
									</div>
							</div>
							<div class="form-actions">
							  <?php echo $this->Form->Submit('Submit',array('class'=>'btn btn-primary'));?>
							</div>
						    </div>
				</fieldset>
				 <?php echo $this->Form->end();?>
			</div>
		</div>
</div>