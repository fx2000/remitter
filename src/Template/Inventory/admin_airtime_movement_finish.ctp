<?php
$URL=Configure::read('Server.URL');
?>
<style>
.lbl
{
    padding-top:5px;
}
</style>
<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo 'Airtime Movement Status';?>
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
						<h2><i class="icon-list-alt"></i> Airtime Movement Status</h2>
		        </div>
                        <?php 
                        if($this->Session->read('success')==1 && !empty($data))
                        {
                        ?>
			<div class="box-content">
				 <?php echo $this->Form->create('',array('url'=>array('controller'=>'cpanel','action'=>'home'),'class'=>'form-horizontal','onload'=>'loadDefaultSource()'));?>
			         <fieldset>
                                                        <div class="control-group">
								  <label class="control-label">Movement Type</label>
								   <div class="controls">
                                                                        <?php
                                                                        if($data['movement_type'] == 1)
                                                                        {
                                                                            echo "<span class='lbl'>Retailer To Store</span>"; 
                                                                        }
                                                                        else
                                                                        {
                                                                            echo "<span class='lbl'>Store To Retailer</span>";
                                                                        }
                                                                        ?>
								  </div>
                                                        </div>
                                                        <div class="control-group">
								  <label class="control-label">Source</label>
								   <div class="controls lbl" style="float:left;margin-left:25px;">
									<?php echo $data['source'];?>
                                                                   </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Source Accounts</label>
								   <div class="controls lbl">
                                                                        <?php echo $data['source_account'];?>
                                                                   </div>
							</div>
                                                         <div class="control-group">
								  <label class="control-label">Destination</label>
								   <div class="controls lbl" style="float:left;margin-left:25px;">
									<?php echo $data['destination']?>
                                                                   </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Destination Accounts</label>
								   <div class="controls lbl">
                                                                        <?php echo $data['destination_account'];?>
                                                                   </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Amount</label>
								   <div class="controls lbl">
								    <?php echo '$'.$data['amount']?>
								  </div>
							</div>
                                                         <div class="control-group">
								  <label class="control-label">Document Number</label>
								   <div class="controls lbl">
								    <?php echo $data['document_no']?>
								  </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Notes</label>
								   <div class="controls lbl">
								    <?php echo $data['notes']?>
								  </div>
							</div>
                                                          <div class="control-group">
								  <label class="control-label">Movement Status</label>
								   <div class="controls lbl">
								    <?php 
                                                                        echo $this->Session->read('success')==1?'Done':'Failed';
                                                                    ?>
								  </div>
							</div>
							<div class="form-actions">
							  <?php echo $this->Form->Submit('Done',array('class'=>'btn btn-primary'));?>
                                                          <?php echo $this->Form->end();?>
							</div>
						    </div>
				</fieldset>
			</div>
                        <?php 
                            }
                            else {
                                
                                echo "<span style='margin-left:20px;'>* Airtime movement is not successfull.</span>";
                            }
                        ?>
		</div>
</div>