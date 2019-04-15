<?php 
$user_type = $this->Session->read('user_type');
?> 
<div>
		<ul class="breadcrumb">
			<li>
				<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
			</li>
                        <li> / </li>
                        <li>
			<?php echo $this->Html->link('View Store',array('controller'=>'Store','action'=>'view',base64_encode(-1)));?>
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
						<h2><i class="icon-user"></i> View Store</h2>
		      </div>
		<div class="box-content">
                   <table class="table table-striped table-bordered bootstrap-datatable Storedatatable">
			<thead>
				 <tr>
                                     <th>Id</th>
					<th class="hidden-phone ">Store Name</th>
					<th >Phone No</th>
                                        <th>Address</th>
					<th>City</th>
                                        <th>Country</th>
                                        <th>Province</th>
                                        <th>Retailer</th>
                                        <th>Permission</th>
                                        <th>Status</th>
                                        <?php
                                        if($user_type !=3)
                                        {
                                        ?>
                                        <th>Action</th>
                                        <?php
                                        }
                                        ?>
				</tr>
			</thead>
			 <tbody>
                                <?php if(!empty($stores)) {
                                       foreach($stores as $store){
                                           $remaining = 6-strlen($store['Store']['id']);
                                            $storeId = '';
                                            for($i=0;$i<$remaining;$i++)
                                            $storeId .= '0';
                                            $storeId .= $store['Store']['id'];
 				?>
                                         <tr>
                                                <td><?php echo $storeId;?></td>
						<td><?php echo $store['Store']['name'];?></td>
						<td class="hidden-phone"><?php echo $store['Store']['phone_no']?></td>
                                                <td><?php echo $store['Store']['address']?></td>
                                                <td><?php echo $store['Store']['city_id']?></td>
                                                <td><?php echo $store['Store']['country_id']?></td>
                                                <td><?php echo $store['Store']['province_id']?></td>
                                                <td><?php echo $store['Store']['retailer_id']?></td>
                                                <td><?php echo $store['Store']['permission']?></td>
                                                <td><?php 
                                                                    if($store['Store']['status']==1)
                                                                        echo "<span class='label label-success'>Active</span>";
                                                                    else
                                                                        echo "<span class='label label-success' style='background:#ff0000;'>Inactive</span>";
                                                                    ?></td>
						<td class="center">
							<?php 
                                                        if($user_type != 3 && $this->Session->read('assigned_to')!=$store['Store']['id'])
                                                        {
                                                        echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">Edit</span> ',array('controller'=>'Store','action'=>'edit',base64_encode($store['Store']['id']),$this->request->params['pass'][0]),array('class'=>'btn btn-small','escape'=>false))?>
                                                        <?php 
                                                            if($user_type != 2 && $this->Session->read('assigned_to')!=$store['Store']['id'])
                                                            {
                                                                echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">Delete</span> ',array('controller'=>'Store','action'=>'delete',base64_encode($store['Store']['id']),$this->request->params['pass'][0]),array('class'=>'btn btn-small del_rec','escape'=>false));
                                                            }
                                                        }
                                                        ?>
                                                        <?php 
                                                            if($store['Store']['operation_model'] == 2)
                                                            {
                                                                echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">View Accounts</span> ',array('controller'=>'Store','action'=>'view_accounts',base64_encode($store['Store']['id'])),array('class'=>'btn btn-small','escape'=>false));
                                                            }
                                                        ?>
                                                        <?php echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">View Users</span> ',array('controller'=>'user','action'=>'view',base64_encode($store['Store']['id']),'S'),array('class'=>'btn btn-small','escape'=>false));?>
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
