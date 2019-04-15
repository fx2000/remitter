<?php
//$URL=Configure::read('Server.URL');
?>
<script>
window.onload = function(){
    $("#source").empty();
    $("#source").append('<?php echo $retailers?>');
}
function loadAccount()
{
    if(document.getElementById('movement1').checked)
    {
        var path='<?php echo $URL?>/inventory/get_retailer_account/'+$('#source').val();
        var path1='<?php echo $URL?>/inventory/get_stores/'+$('#source').val();
    }
    else
    {
        var path='<?php echo $URL?>/inventory/get_store_accounts_by_store_id/'+$('#source').val();
        var path1='<?php echo $URL?>/inventory/get_retailer/'+$('#source').val();
    }
    $("#sloaderdiv").show();
		var request = $.ajax({
			url: path
			
		});
		request.done(function (response, textStatus, jqXHR){ //alert(response);
                    $('#saccounts').empty();
                    $('#saccounts').append(response);
                    $('#sloaderdiv').hide();
		});
		request.fail(function (jqXHR, textStatus, errorThrown){
			console.log(jqXHR);
                        $('#sloaderdiv').hide();
		});
                
                var request1 = $.ajax({
			url: path1
			
		});
		request1.done(function (response, textStatus, jqXHR){ //alert(response);
                    $('#destination').empty();
                    $('#destination').append(response);
		});
		request1.fail(function (jqXHR, textStatus, errorThrown){
			console.log(jqXHR);
		});
		
}

function loadDestinationAccount()
{
    if(document.getElementById('movement1').checked)
    {
        var path1='<?php echo $URL?>/inventory/get_store_accounts/'+$('#source').val()+'/'+$('#destination').val();
    }
    else
    {
        var path1='<?php echo $URL?>/inventory/get_retailer_account/'+$('#destination').val();
    }
    $("#dloaderdiv").show();
		var request = $.ajax({
			url: path1
			
		});
		request.done(function (response, textStatus, jqXHR){ //alert(response);
                    $('#daccounts').empty();
                    $('#daccounts').append(response);
                    $('#dloaderdiv').hide();
		});
		request.fail(function (jqXHR, textStatus, errorThrown){
			console.log(jqXHR);
                        $('#dloaderdiv').hide();
		});
}

function loadSource()
{
    $('#saccountsamt').val('');
    $('#daccountsamt').val('');
    $('#samtdiv').hide();
    $('#damtdiv').hide();
    if(document.getElementById('movement1').checked)
    {
        $('#daccounts').empty();
        $("#source").empty();
        $('#destination').empty();
        $('#saccounts').empty();
        $("#source").append('<?php echo $retailers?>');
        $('#daccounts').append("<option value=''>Select Account</option>");
        $('#saccounts').append("<option value=''>Select Account</option>");
        $('#destination').append("<option value=''>Select Destination</option>");
    }
    else
    {
        $('#daccounts').empty();
        $("#source").empty();
        $('#destination').empty();
        $('#saccounts').empty();
        $("#source").append('<?php echo $stores?>');
        $('#daccounts').append("<option value=''>Select Account</option>");
        $('#saccounts').append("<option value=''>Select Account</option>");
        $('#destination').append("<option value=''>Select Destination</option>");
    }
}

function getBalance()
{
    var path1='<?php echo $URL?>/inventory/get_source_account_balance/'+$('#saccounts').val();
    var request = $.ajax({
			url: path1
			
		});
		request.done(function (response, textStatus, jqXHR){ //alert(response);
                    $('#samtdiv').show();
                    $("#available_amount").val(response);
                    $('#saccountsamt').val(response);
		});
		request.fail(function (jqXHR, textStatus, errorThrown){
			console.log(jqXHR);
                        $('#dloaderdiv').hide();
		});
}

function getDestinationBalance()
{
    var path1='<?php echo $URL?>/inventory/get_source_account_balance/'+$('#daccounts').val();
    var request = $.ajax({
			url: path1
			
		});
		request.done(function (response, textStatus, jqXHR){ //alert(response);
                    $('#damtdiv').show();
                    $('#daccountsamt').val(response);
		});
		request.fail(function (jqXHR, textStatus, errorThrown){
			console.log(jqXHR);
                        $('#dloaderdiv').hide();
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
			<?php echo $this->Html->link('Airtime Movement',array('controller'=>'inventory','action'=>'airtime_movement'));?>
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
						<h2><i class="icon-list-alt"></i> Airtime Movement</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',array('url'=>array('controller'=>'inventory','action'=>'airtime_movement_confirm'),'class'=>'form-horizontal','onload'=>'loadDefaultSource()'));?>
			         <fieldset>
                                                        <div class="control-group">
								  <label class="control-label">Movement Type</label>
								   <div class="controls">
									<label class="radio">
										<input type="radio" name="AirtimeMovement[movement_type]" id="movement1" onclick="loadSource()" value="1" checked>
										Retailer To Store
									</label>
									<div style="clear:both"></div>
									<label class="radio">
										<input type="radio" name="AirtimeMovement[movement_type]" id="movement2" onclick="loadSource()" value="2">
										Store To Retailer
									</label>
								  </div>
                                                        </div>
                                                        <div class="control-group">
								  <label class="control-label">Source</label>
								   <div class="controls" style="float:left;margin-left:25px;">
									<?php echo $this->Form->input('AirtimeMovement.source_id', array('type' => 'select','empty'=>'Select Source','id'=>"source",'label'=>false,'onchange'=>'loadAccount()'))?>
                                                                        <script language="javascript" type="text/javascript">var f1 = new LiveValidation('source');f1.add( Validate.Presence);</script>
                                                                   </div>
                                                                  <div style="float:left;display:none;" id='sloaderdiv'>
                                                                        <img src="<?php echo 'img/loading.gif'?>">
                                                                    </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Source Accounts</label>
								   <div class="controls">
									<?php echo $this->Form->input('AirtimeMovement.source_account', array('type' => 'select','empty'=>'Select Account','id'=>"saccounts",'label'=>false,'onchange'=>'getBalance()'))?>
                                                                        <script language="javascript" type="text/javascript">var f1 = new LiveValidation('saccounts');f1.add( Validate.Presence);</script>
                                                                   </div>
							</div>
                                                        <div class="control-group" id="samtdiv" style="display: none;">
								  <label class="control-label">Source Account Amt.</label>
								   <div class="controls">
									<?php echo $this->Form->input('AirtimeMovement.source_account_amt', array('id'=>"saccountsamt",'label'=>false,'readonly' =>true))?>
                                                                        
                                                                   </div>
							</div>
                                                         <div class="control-group">
								  <label class="control-label">Destination</label>
								   <div class="controls" style="float:left;margin-left:25px;">
									<?php echo $this->Form->input('AirtimeMovement.destination_id', array('type' => 'select','empty'=>'Select Destination','id'=>"destination",'label'=>false,'onchange'=>'loadDestinationAccount()'))?>
                                                                        <script language="javascript" type="text/javascript">var f1 = new LiveValidation('destination');f1.add( Validate.Presence);</script>
                                                                   </div>
                                                                  <div style="float:left;display:none;" id='dloaderdiv'>
                                                                        <img src="<?php echo 'img/loading.gif'?>">
                                                                    </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Destination Accounts</label>
								   <div class="controls">
									<?php echo $this->Form->input('AirtimeMovement.destination_account', array('type' => 'select','empty'=>'Select Account','id'=>"daccounts",'label'=>false,'onchange'=>'getDestinationBalance()'))?>
                                                                        <script language="javascript" type="text/javascript">var f1 = new LiveValidation('daccounts');f1.add( Validate.Presence);</script>
                                                                   </div>
							</div>
                                                         <div class="control-group" id="damtdiv" style="display: none;">
								  <label class="control-label">Destination Account Amt.</label>
								   <div class="controls">
									<?php echo $this->Form->input('AirtimeMovement.destination_account_amt', array('id'=>"daccountsamt",'label'=>false,'readonly' =>true))?>
                                                                        
                                                                   </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Amount</label>
								   <div class="controls">
								    <?php echo $this->Form->input('AirtimeMovement.amount',array('type'=>'text','class'=>'input-large ','id'=>'amount','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Amount'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('amount');f1.add( Validate.Presence);f1.add( Validate.NumberValidFloat);f1.add( Validate.SourceBalance)</script>
								  </div>
							</div>
                                                         <div class="control-group">
								  <label class="control-label">Document Number</label>
								   <div class="controls">
								    <?php echo $this->Form->input('AirtimeMovement.document_no',array('type'=>'text','class'=>'input-large ','id'=>'docno','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Document Number'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('docno');f1.add( Validate.Presence);</script>
								  </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Notes</label>
								   <div class="controls">
								    <?php echo $this->Form->input('AirtimeMovement.notes',array('type'=>'text','class'=>'input-large ','id'=>'notes','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Notes'));?>
								  </div>
							</div>
                                     <input type="hidden" id="available_amount">
							<div class="form-actions">
							  <?php echo $this->Form->Submit('Submit',array('class'=>'btn btn-primary'));?>
							</div>
						    </div>
				</fieldset>
			</div>
		</div>
</div>