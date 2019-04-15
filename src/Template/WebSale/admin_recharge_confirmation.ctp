<script>
    function inactive()
    {
        $('#inactivediv').show();
    }
</script>
<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Recharge Confirmation',array('controller'=>'WebSale','action'=>'recharge'));?>
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
						<h2><i class="icon-list-alt"></i> Recharge Confirmation</h2>
		        </div>
			<div class="box-content">
<?php $data = $this->request->data['Recharge'];?>
				 <?php echo $this->Form->create('',array('url'=>array('controller'=>'WebSale','action'=>'do_recharge'),'class'=>'form-horizontal'));?>
			       <div class="control-group">* Please check your recharge information.</div>
 <fieldset>
                                                        <div class="control-group">
								  <label class="control-label">Operator : </label>
								   <div class="controls">
                                                                        <?php echo $this->Form->input('Recharge.operator', array('type' => 'text','label'=>false,'readonly'=>true))?>
                                                                       <?php echo $this->Form->input('Recharge.operator_id', array('type' => 'hidden','id'=>"operatorid",'label'=>false))?>
                                                                   </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Phone No : </label>
								   <div class="controls">
                                                                        <?php echo $this->Form->input('Recharge.phone_no', array('type' => 'text','readonly'=>true,'id'=>"phone",'label'=>false))?>
								  </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Topup Amount : </label>
								   <div class="controls">
                                                                        <?php echo $this->Form->input('Recharge.amount', array('type' => 'text','readonly'=>true,'id'=>"amount",'label'=>false))?>
								  </div>
							</div>
							<div class="form-actions">
							  <?php echo $this->Form->Submit('Done',array('class'=>'btn btn-primary','style'=>'float:left;','onclick' => 'inactive()'));?>
                                                          <?php echo $this->Form->end();?>
<?php echo $this->Form->create('',array('url'=>array('controller'=>'WebSale','action'=>'recharge'),'class'=>'form-horizontal'));?>
<?php echo $this->Form->input('Recharge.operator_id', array('type' => 'hidden','id'=>"operatorhidden",'label'=>false))?>
<?php echo $this->Form->input('Recharge.amount', array('type' => 'hidden','id'=>"amount",'label'=>false))?>
<?php echo $this->Form->input('Recharge.phone_no', array('type' => 'hidden','id'=>"phone",'label'=>false))?>
<?php echo $this->Form->Submit('Edit',array('class'=>'btn btn-primary','style'=>'float:left;margin-left:20px;'));?>
<?php echo $this->Form->end();?>
							</div>
						    </div>
				</fieldset>
			</div>
		</div>
</div>
<div style="width: 100%; background:rgba(0, 0, 0, 0.6); position: absolute;height:100%;color:#f86927;font-size: 16px;font-weight: 600;margin-left: -2%;margin-top: -8%;display: none;" id="inactivediv">
    <div style="margin-top:20%; margin-left: 38%;">
        <?php echo $this->Html->image('loading.gif');?>
        Please wait recharge is in progress.........
    </div> 
</div>