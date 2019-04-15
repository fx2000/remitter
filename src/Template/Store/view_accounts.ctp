<div>
		<ul class="breadcrumb">
			<li>
				<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
			</li>
			<li>/</li>
			<li>
				<?php echo $this->Html->link('Store Accounts',array('controller'=>'Store','action'=>'view_accounts',$this->request->params['pass'][0]));?>
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
						<h2><i class="icon-user"></i><?php echo ' Store '.$store['Store']['name']?> Accounts</h2>
		      </div>
		<div class="box-content">
                   <table class="table table-striped table-bordered bootstrap-datatable StoreAccountDataTable">
			<thead>
				 <tr>
					<th class="hidden-phone ">Account Id</th>
					<th>Account Type</th>
                                        <!--<th>Credit Limit</th>-->
                                        <th>Amount</th>
                                        <th>Operator Assigned</th>
				</tr>
			</thead>
			 <tbody>
                                <?php if(!empty($storesacct)) {
                                       foreach($storesacct as $storeacct){
                                           $remaining = 6-strlen($storeacct['Account']['id']);
                                            $accountId = '';
                                            for($i=0;$i<$remaining;$i++)
                                            $accountId .= '0';
                                            $accountId .= $storeacct['Account']['id'];
 				?>
                                         <tr>
								<td><?php echo $accountId;?></td>
						<td class="hidden-phone"><?php echo $storeacct['Account']['account_type']?></td>
                                                <!--<td class="hidden-phone"><?php //echo $storeacct['Account']['credit_limit']?></td>-->
                                                <td><?php echo '$'.$storeacct['Account']['amount']?></td>
                                                <td><?php 
                                                        if(!empty($storeacct['Account']['operators']))
                                                        {
                                                                echo implode(",",$storeacct['Account']['operators']);
                                                        }
                                                        else
                                                        {
                                                            echo 'None';
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
