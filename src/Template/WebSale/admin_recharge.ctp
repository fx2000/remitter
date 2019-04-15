<script>

function showBalance()
{
    $('#acctbalancediv').show();
    var $acct_balance = $.parseJSON('<?php echo json_encode($stores_balance); ?>');
    $('#acctbalance').val($acct_balance[$("#operator").val()]);
    var topupamount = document.getElementById('amount');
    topupamount.setAttribute('max' , $acct_balance[$("#operator").val()]);
}

</script>
<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Recharge',array('controller'=>'WebSale','action'=>'recharge'));?>
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
						<h2><i class="icon-list-alt"></i> Recharge</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',array('url'=>array('controller'=>'WebSale','action'=>'recharge_confirmation'),'class'=>'form-horizontal'));?>
			         <fieldset>
                                                        <div class="control-group">
								  <label class="control-label">Operator</label>
								   <div class="controls">
									<?php 
                                                                            if($user_operation_model == 2)
                                                                            {
                                                                                echo $this->Form->input('Recharge.operator_id', array('type' => 'select','options' => $operators,'empty'=>'Select Operator','id'=>"operator",'label'=>false,'onchange' => 'showBalance()'));
                                                                            }
                                                                            else
                                                                            {
                                                                                echo $this->Form->input('Recharge.operator_id', array('type' => 'select','options' => $operators,'empty'=>'Select Operator','id'=>"operator",'label'=>false,'onchange' => 'showBalance()'));
                                                                            }
                                                                        ?>
                                                                        <script language="javascript" type="text/javascript">var f1 = new LiveValidation('operator');f1.add( Validate.Presence);</script>
                                                                   </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Phone No</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Recharge.phone_no',array('type'=>'text','class'=>'input-large ','id'=>'phone','div'=>false,'label'=>false,'maxlength'=>100,'data-rel'=>'tooltip','data-original-title'=>'Phone No'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('phone');f1.add( Validate.Presence);f1.add( Validate.NumberValid);f1.add( Validate.NumberOfChar);</script>
								  </div>
							</div>
							<div class="control-group">
								  <label class="control-label"> Confirm Phone No</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Recharge.cphone_no',array('type'=>'text','class'=>'input-large ','id'=>'cphone','div'=>false,'label'=>false,'maxlength'=>100,'data-rel'=>'tooltip','data-original-title'=>'Confirm Phone No'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('cphone');f1.add( Validate.Presence);f1.add( Validate.NumberValid);f1.add( Validate.NumberOfChar);f1.add( Validate.phonematch )</script>
								  </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Topup Amount</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Recharge.amount',array('type'=>'text','class'=>'input-large ','id'=>'amount','div'=>false,'label'=>false,'maxlength'=>100,'data-rel'=>'tooltip','data-original-title'=>'Topup Amount'));?>
                                                                    <script language="javascript" type="text/javascript">var f1 = new LiveValidation('amount');f1.add( Validate.Presence);f1.add( Validate.NumberValid);f1.add( Validate.TopupAmount);</script>
								  </div>
							</div>
                                                        <?php 
                                                                            $acctbalance = '';
                                                                            if($user_operation_model == 2)
                                                                            {
                                                                                if($this->request->data['Recharge']['operator_id']!='')
                                                                                {
                                                                                    $display = 'block';
                                                                                    $acctbalance = $stores_balance[$this->request->data['Recharge']['operator_id']];
                                                                                }
                                                                                else 
                                                                                {
                                                                                    $display = 'none';
                                                                                }
                                                                            }
                                                                            else
                                                                            {
                                                                                $display = 'none';
                                                                            }
                                                        ?>
                                                        <div class="control-group" id="acctbalancediv" style='display:<?php echo $display?>'>
								  <label class="control-label">Available Amount</label>
								   <div class="controls">
								    <input type="text" id="acctbalance" readonly='true' class='input-large' value="<?php echo $acctbalance?>">
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
