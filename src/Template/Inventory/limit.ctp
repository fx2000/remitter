<?php ?>
<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Set Limit',array('controller'=>'inventory','action'=>'limit'));?>
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
				<h2><i class="icon-list-alt"></i> Set Limit</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',['class'=>'form-horizontal']);?>
			         <fieldset>
				<?php
				if(!empty($this->request->data))
				{
					foreach($this->request->data AS $Operator) 
					{
				?>      
					<div class="control-group">
							<label class="control-label">Minimum amount for <?php echo $Operator->name;?></label>
							<div class="controls"> 
							<div class="input-append">
								<div style="float:left"><?php echo $this->Form->input('Operator.min_balance'.$Operator->id,array('type'=>'text','class'=>'input-large ','id'=>'minLimit_'.$Operator->id,'div'=>false,'label'=>false,'maxlength'=>'50','data-rel'=>'tooltip','data-original-title'=>'Minimum amount for '.$Operator->name.'','value'=>@round($Operator->minimum_balance,2)));?></div>
							</div>
							
							<script language="javascript" type="text/javascript">var f1 = new LiveValidation('minLimit_<?php echo $Operator['Operator']['id'];?>');f1.add( Validate.Presence);f1.add( Validate.NumberValidFloat);</script>
							</div>
					</div>
				<?php
					}
				}
				?>
							<?php
								
							?>
							<div class="form-actions">
							  <?php echo $this->Form->Submit('Submit',array('class'=>'btn btn-primary'));?>
							</div>
						    </div>
				</fieldset>
			</div>
		</div>
</div>