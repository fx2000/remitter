<?php
//$URL=Configure::read('Server.URL');
?>
<script>
function loadAccount()
{
    var path='<?php echo $URL?>/inventory/get_retailer_account/'+$('#retailer').val();
    $("#loaderdiv").show();
		var request = $.ajax({
			url: path
			
		});
		request.done(function (response, textStatus, jqXHR){ //alert(response);
                    $('#accounts').empty();
                    $('#accounts').append(response);
                    $('#loaderdiv').hide();
		});
		request.fail(function (jqXHR, textStatus, errorThrown){
			console.log(jqXHR);
                        $('#loaderdiv').hide();
		});
		
}
</script>

<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Deposit Amount',array('controller'=>'inventory','action'=>'addsub_amount'));?>
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
						<h2><i class="icon-list-alt"></i> Deposit Amount</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',['class'=>'form-horizontal']);?>
			         <fieldset>
                                                        <div class="control-group">
								  <label class="control-label">Retailers</label>
								   <div class="controls" style="float:left;margin-left:25px;">
									<?php echo $this->Form->input('RetailerAccountDeposit.retailer_id', array('type' => 'select','options' => $retailers,'empty'=>'Select Retailer','id'=>"retailer",'label'=>false,'onchange'=>'loadAccount()'))?>
                                                                        <script language="javascript" type="text/javascript">var f1 = new LiveValidation('retailer');f1.add( Validate.Presence);</script>
                                                                   </div>
                                                                  <div style="float:left;display:none;" id='loaderdiv'>
                                                                        <img src="<?php echo 'img/loading.gif'?>">
                                                                    </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Accounts</label>
								   <div class="controls">
									<?php echo $this->Form->input('RetailerAccountDeposit.account_id', array('type' => 'select','empty'=>'Select Account','id'=>"accounts",'label'=>false))?>
                                                                        <script language="javascript" type="text/javascript">var f1 = new LiveValidation('accounts');f1.add( Validate.Presence);</script>
                                                                   </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Add/Subtract</label>
								   <div class="controls">
									<label class="radio">
										<input type="radio" name="RetailerAccountDeposit[operation]" id="operation1" value="1" checked>
										Add
									</label>
									<div style="clear:both"></div>
									<label class="radio">
										<input type="radio" name="RetailerAccountDeposit[operation]" id="operation2" value="2">
										Subtract
									</label>
								  </div>
                                                        </div>
                                                        <div class="control-group">
								  <label class="control-label">Amount</label>
								   <div class="controls">
								    <?php echo $this->Form->input('RetailerAccountDeposit.amount',array('type'=>'text','class'=>'input-large ','id'=>'amount','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Amount'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('amount');f1.add( Validate.Presence);f1.add( Validate.NumberValidFloat)</script>
								  </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Document Number</label>
								   <div class="controls">
								    <?php echo $this->Form->input('RetailerAccountDeposit.document_no',array('type'=>'text','class'=>'input-large ','id'=>'docno','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Document Number'));?>
                                                                    <script language="javascript" type="text/javascript">var f1 = new LiveValidation('docno');f1.add( Validate.Presence);</script>
								  </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Notes</label>
								   <div class="controls">
								    <?php echo $this->Form->input('RetailerAccountDeposit.notes',array('type'=>'text','class'=>'input-large ','id'=>'notes','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Notes'));?>
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