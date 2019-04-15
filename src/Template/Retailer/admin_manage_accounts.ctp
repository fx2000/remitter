<?php 
$user_type = $this->Session->read('user_type');
?>
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
			<?php echo $this->Html->link('Manage Accounts',array('controller'=>'Retailer','action'=>'manage_accounts',$this->request->params['pass'][0]));?>
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
<?php } 
if($user_type != 3)
{
?>
<div class="row-fluid ">	
		<div class="box span12">
		       <div class="box-header well" data-original-title>
						<h2><i class="icon-list-alt"></i> Add Account</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',array('url'=>array('controller'=>'Retailer','action'=>'manage_accounts',$this->request->params['pass'][0]),'class'=>'form-horizontal'));?>
			         <fieldset>
                                                        <div class="control-group">
								  <label class="control-label">Account Type</label>
								   <div class="controls">
                                                                   <?php
                                                                        if($retailer['Retailer']['operation_model'] == 1)
                                                                        {
                                                                   ?>
									<label class="radio">
										<input type="radio" name="data[Account][account_type]" onclick="ShowField()" id="account1" value="1" <?php echo ($this->data['Account']['account_type']==1)?'checked=true':''?>   > 
										Postpaid
									</label>
									<div style="clear:both"></div>
                                                                        <?php 
                                                                            }
                                                                        ?>
									<label class="radio">
										<input type="radio" name="data[Account][account_type]" onclick="ShowField()" id="account2" value="2" <?php echo ($this->data['Account']['account_type']==2)?'checked=true':''?> <?php echo ($this->data['Account']['account_type']=='')?'checked=true':''?>>
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
								   <div class="controls" id="creditelement">
								    <?php echo $this->Form->input('Account.credit_limit',array('type'=>'text','class'=>'input-large ','onkeypress'=>'return numbersonly(event)','id'=>'credit_limit','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Credit Limit'));?>
								  </div>
							</div>
							<div class="form-actions">
							  <?php echo $this->Form->Submit('Submit',array('class'=>'btn btn-primary'));?>
							</div>
						    </div>
				</fieldset>

<?php 
}
?>
<div class="row-fluid ">		
		<div class="box span12">
		     <div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i><?php echo ' Retailer <b>'.$retailer['Retailer']['name'];?></b> Accounts</h2>
		      </div>
		<div class="box-content">
                   <table class="table table-striped table-bordered bootstrap-datatable AccountDataTable">
			<thead>
				 <tr>
					<th class="hidden-phone ">Account Id</th>
					<th>Account Type</th>
                                         <?php
                                                                if($retailer['Retailer']['operation_model'] == 1)
                                                                {
                                                                    $display3 = 'block';
                                                                }
                                                                else
                                                                {
                                                                    $display3 = 'none';
                                                                }
                                                            ?>
                                        <th style="display: <?php echo $display3?>">Credit Limit</th>
                                        <th>Amount</th>
                                        <th>Operation Model</th>
                                        <?php
                                            if($user_type != 3)
                                            {
                                            ?>
                                        <th>Action</th>
                                        <?php 
                                        }
                                        ?>
				</tr>
			</thead>
			 <tbody>
                                <?php if(!empty($accounts)) {
                                       foreach($accounts as $account){
                                           $remaining = 6-strlen($account['Account']['id']);
                                            $accountId = '';
                                            for($i=0;$i<$remaining;$i++)
                                            $accountId .= '0';
                                            $accountId .= $account['Account']['id'];
 				?>
                                         <tr>
						<td><?php echo $accountId;?></td>
						<td class="hidden-phone"><?php echo $account['Account']['account_type']?></td>
                                                 <?php
                                                                if($retailer['Retailer']['operation_model'] == 1)
                                                                {
                                                                    $display1 = 'block';
                                                                }
                                                                else
                                                                {
                                                                    $display1 = 'none';
                                                                }
                                                                //echo '<pre>';
                                                                //print_r($account);
                                                                //echo $account['Account']['credit_limit']-$account['Account']['amount']
                                                            ?>
                                                <td class="hidden-phone" style="display: <?php echo $display1?>"><?php echo $account['Account']['credit_limit']?></td>
                                                <td>$<?php echo $account['Account']['amount']?></td>
                                                <td><?php echo $account['Account']['operation_model']?></td>
						<td class="center">
							<?php 
                                                        if($user_type != 3)
                                                        {
                                                            if($account['Account']['account_type'] != 'Prepaid')
                                                            echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">Edit</span> ',array('controller'=>'Retailer','action'=>'edit_account',base64_encode($account['Account']['id'])),array('class'=>'btn btn-small','escape'=>false));
                                                            echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">Delete</span> ',array('controller'=>'Retailer','action'=>'delete_account',base64_encode($account['Account']['id'])),array('class'=>'btn btn-small del_rec','escape'=>false));
                                                            echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">Assign Operators</span> ',array('controller'=>'Retailer','action'=>'assign_operator',base64_encode($account['Account']['id'])),array('class'=>'btn btn-small','escape'=>false));
                                                        }
                                                        ?>
                                                 </td>
					</tr>	
                                <?php }
				  }	
				?>			
			</tbody>   
		   <table>
		</div>
	</div>
</div>
			</div>
		</div>
</div>