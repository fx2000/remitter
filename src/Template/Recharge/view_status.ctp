<?php //debug($Admindata);?>

 	<div>
		<ul class="breadcrumb">
			<li>
				<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
			</li>
			<li>/</li>
			<li>
				<?php echo $this->Html->link('Recharge Status',array('controller'=>'recharge','action'=>'status'));?>
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
						<h2><i class="icon-user"></i> Recharge Detail</h2>
		      </div>
		<div class="box-content">
			 <?php echo $this->Form->create('',array('url'=>array('controller'=>'recharge','action'=>'generateNewRecharge'),'class'=>'form-horizontal'));?>
                   	<fieldset>
				<div class="control-group">
					<label class="control-label">Transaction ID</label>
					<div class="controls">
						<span style="margin-top: 6px;float: left;" ><?php echo $rechageStaus['reference_no'];?></span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Mobile No.</label>
					<div class="controls">
						<span style="margin-top: 6px;float: left;" ><?php echo $rechageStaus['mobile_no'];?></span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Operator</label>
					<div class="controls">
						<span style="margin-top: 6px;float: left;" ><?php echo $rechageStaus['Operator']['name'];?></span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Name of User</label>
					<div class="controls">
						<span style="margin-top: 6px;float: left;" ><?php echo $rechageStaus['User']['name'];?></span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Date</label>
					<div class="controls">
						<span style="margin-top: 6px;float: left;" ><?php echo date('d M, Y',strtotime($rechageStaus['datetime']));?></span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Status</label>
					<div class="controls">
						<span style="margin-top: 6px;float: left;" ><?php echo ($rechageStaus['status']==1)?'Success':'Failed';?></span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Message</label>
					<div class="controls">
						<span style="margin-top: 6px;float: left;" ><?php echo $rechageStaus['response_msg'];?></span>
					</div>
				</div>
			</fieldset>
			<?php echo $this->Form->end();?>
		</div>
	</div>
</div>
