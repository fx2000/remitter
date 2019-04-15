<?php

?>
<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo 'Airtime Movement Confirmation';?>
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
						<h2><i class="icon-list-alt"></i> Airtime Movement Confirmation</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',array('url'=>array('controller'=>'inventory','action'=>'airtime_movement_done'),'class'=>'form-horizontal','onload'=>'loadDefaultSource()'));?>
			         <fieldset>
                                                        <div class="control-group">
								  <label class="control-label">Movement Type</label>
								   <div class="controls">
                                                                        <?php
                                                                        if($this->request->data['AirtimeMovement']['movement_type'] == 1)
                                                                        {
                                                                            echo "Retailer To Store"; 
                                                                        }
                                                                        else
                                                                        {
                                                                            echo "Store To Retailer";
                                                                        }
                                                                        ?>
                                                                        <?php echo $this->Form->input('AirtimeMovement.movement_type',array('type'=>'hidden','class'=>'input-large ','id'=>'movement_type','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Movement Type'));?>
								  </div>
                                                        </div>
                                                        <div class="control-group">
								  <label class="control-label">Source</label>
								   <div class="controls" style="float:left;margin-left:25px;">
									<?php echo $this->Form->input('AirtimeMovement.source',array('type'=>'text','class'=>'input-large ','id'=>'source','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Source' ,'readonly'=>true));?>
                                                                        <?php echo $this->Form->input('AirtimeMovement.source_id',array('type'=>'hidden','class'=>'input-large ','id'=>'sourceid','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Source' ,'readonly'=>true));?>
                                                                   </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Source Accounts</label>
								   <div class="controls">
                                                                        <?php echo $this->Form->input('AirtimeMovement.source_account_id',array('type'=>'text','class'=>'input-large ','id'=>'sourceacctid','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Source Account','readonly'=>true));?>
									<?php echo $this->Form->input('AirtimeMovement.source_account',array('type'=>'hidden','class'=>'input-large ','id'=>'sourceacct','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Source Account'));?>
                                                                   </div>
							</div>
                                                         <div class="control-group" id="samtdiv">
								  <label class="control-label">Source Account Amt.</label>
								   <div class="controls">
									<?php echo $this->Form->input('AirtimeMovement.source_account_amt', array('id'=>"saccountsamt",'label'=>false,'readonly' =>true))?>
                                                                        
                                                                   </div>
							</div>
                                                         <div class="control-group">
								  <label class="control-label">Destination</label>
								   <div class="controls" style="float:left;margin-left:25px;">
									<?php echo $this->Form->input('AirtimeMovement.destination',array('type'=>'text','class'=>'input-large ','id'=>'destination','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Destination' ,'readonly'=>true));?>
                                                                        <?php echo $this->Form->input('AirtimeMovement.destination_id',array('type'=>'hidden','class'=>'input-large ','id'=>'destinationid','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Destination'));?>
                                                                   </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Destination Accounts</label>
								   <div class="controls">
                                                                            <?php echo $this->Form->input('AirtimeMovement.destination_account_id',array('type'=>'text','class'=>'input-large ','id'=>'destinationacctid','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Destination Account','readonly'=>true));?>
                                                                            <?php echo $this->Form->input('AirtimeMovement.destination_account',array('type'=>'hidden','class'=>'input-large ','id'=>'destinationacct','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Destination Account'));?>
                                                                   </div>
							</div>
                                                         <div class="control-group" id="damtdiv">
								  <label class="control-label">Destination Account Amt.</label>
								   <div class="controls">
									<?php echo $this->Form->input('AirtimeMovement.destination_account_amt', array('id'=>"daccountsamt",'label'=>false,'readonly' =>true))?>
                                                                        
                                                                   </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Amount</label>
								   <div class="controls">
								    <?php echo $this->Form->input('AirtimeMovement.amount',array('type'=>'text','class'=>'input-large ','id'=>'amount','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Amount','readonly'=>true));?>
								  </div>
							</div>
                                                         <div class="control-group">
								  <label class="control-label">Document Number</label>
								   <div class="controls">
								    <?php echo $this->Form->input('AirtimeMovement.document_no',array('type'=>'text','class'=>'input-large ','id'=>'docno','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Document Number','readonly'=>true));?>
								  </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Notes</label>
								   <div class="controls">
								    <?php echo $this->Form->input('AirtimeMovement.notes',array('type'=>'text','class'=>'input-large ','id'=>'notes','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Notes','readonly'=>true));?>
								  </div>
							</div>
							<div class="form-actions">
							  <?php echo $this->Form->Submit('Done',array('class'=>'btn btn-primary','style'=>'float:left;'));?>
                                                          <?php echo $this->Form->end();
                                                          echo $this->Form->create('',array('url'=>array('controller'=>'inventory','action'=>'airtime_movement_edit'),'class'=>'form-horizontal'));
                                                          echo $this->Form->input('AirtimeMovement.movement_type',array('type'=>'hidden','class'=>'input-large ','id'=>'hmovement_type','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Movement Type'));
                                                          echo $this->Form->input('AirtimeMovement.source_id',array('type'=>'hidden','class'=>'input-large ','id'=>'hsourceid','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Source' ,'readonly'=>true));
                                                          echo $this->Form->input('AirtimeMovement.source_account',array('type'=>'hidden','class'=>'input-large ','id'=>'hsourceacct','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Source Account'));
                                                          echo $this->Form->input('AirtimeMovement.destination_id',array('type'=>'hidden','class'=>'input-large ','id'=>'hdestinationid','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Destination'));
                                                          echo $this->Form->input('AirtimeMovement.destination_account',array('type'=>'hidden','class'=>'input-large ','id'=>'hdestinationacct','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Destination Account'));
                                                          echo $this->Form->input('AirtimeMovement.amount',array('type'=>'hidden','class'=>'input-large ','id'=>'hamount','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Amount'));
                                                          echo $this->Form->input('AirtimeMovement.document_no',array('type'=>'hidden','class'=>'input-large ','id'=>'hdocno','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Document Number'));
                                                          echo $this->Form->input('AirtimeMovement.notes',array('type'=>'hidden','class'=>'input-large ','id'=>'hnotes','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Notes'));
                                                          echo $this->Form->input('AirtimeMovement.destination_account_amt', array('type'=>'hidden','id'=>"daccountsamt",'label'=>false,'readonly' =>true));
                                                          echo $this->Form->input('AirtimeMovement.source_account_amt', array('type'=>'hidden','id'=>"saccountsamt",'label'=>false,'readonly' =>true));
                                                          echo $this->Form->Submit('Edit',array('class'=>'btn btn-primary','style'=>'float:left;margin-left:20px;'));
                                                          echo $this->Form->end();
                                                          ?>
                                                            
                                                            
							</div>
						    </div>
				</fieldset>
			</div>
		</div>
</div>