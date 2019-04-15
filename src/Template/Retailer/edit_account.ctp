<script>
function ShowField()
{
    if(document.getElementById("account1").checked)
    {
        $("#creditdiv").show();
    }
    else if(document.getElementById("account2").checked)
    {
        $("#creditdiv").hide();
    }
}
function numbersonly(e){
    var unicode=e.charCode? e.charCode : e.keyCode
    if (unicode!=8){ //if the key isn't the backspace key (which we should allow)
        if (unicode<48||unicode>57) //if not a number
            return false //disable key press
    }
}
</script>

<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Edit Account',array('controller'=>'Retailer','action'=>'edit_account',$this->request->params['pass'][0]));?>
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
						<h2><i class="icon-list-alt"></i> Edit Account</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',['class'=>'form-horizontal']);?>
			         <fieldset>
                                     <?php 
                                            $remaining = 6-strlen($this->request->data['Account']['id']);
                                            $accountId = '';
                                            for($i=0;$i<$remaining;$i++)
                                            $accountId .= '0';
                                            $accountId .= $this->request->data['Account']['id'];
                                     ?>
							<div class="control-group">
								  <label class="control-label">Account Id</label>
								   <div class="controls">
                                                                       <input type="text" id="accout_id" value="<?php echo $accountId?>" readonly="true">
								    <?php echo $this->Form->input('Account.id',array('type'=>'hidden','class'=>'input-large ','id'=>'account_id','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Account Id','readonly'=>true));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('account_id');f1.add( Validate.Presence);</script>
								  </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Account Type</label>
								   <div class="controls">
									<label class="radio">
                                                                        <?php
                                                                                if($retailer->operation_model == 1)
                                                                        {
                                                                   ?>
										<input type="radio" name="Account[account_type]" onclick="ShowField()" id="account1" value="1" <?php echo ($this->request->data['Account']['account_type']==1)?'checked=true':''?> <?php echo ($this->request->data['Account']['account_type']=='')?'checked=true':''?>  > 
										Postpaid
									</label>
									<div style="clear:both"></div>
                                                                        <?php 
                                                                            }
                                                                        ?>
									<label class="radio">
										<input type="radio" name="Account[account_type]" onclick="ShowField()" id="account2" value="2" <?php echo ($this->request->data['Account']['account_type']==2)?'checked=true':''?> >
										Prepaid
									</label>
								  </div>
                                                        </div>
                                                        <?php
                                                            if($this->request->data['Account']['account_type'] == 1)
                                                            {
                                                                $display = 'block';
                                                            }
                                                            else
                                                            {
                                                                $display = 'none';
                                                            }
                                                        ?>
                                                        <div class="control-group" id="creditdiv" style="display:<?php echo $display;?>">
								  <label class="control-label">Credit Limit</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Account.credit_limit',array('type'=>'text','class'=>'input-large ','onkeypress'=>'return numbersonly(event)','id'=>'credit_limit','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Credit Limit'));?>
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