<?php 
$user_type = $this->request->session()->read('user_type');
//echo '<pre>';
//print_r($retailers);
?>
 	<div>
		<ul class="breadcrumb">
			<li>
				<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
			</li>
                        <li> / </li>
                        <li>
			<?php echo $this->Html->link('View Retailer',array('controller'=>'Retailer','action'=>'view'));?>
                        </li>
		</ul>
	</div>
	<?php if($this->request->session()->read('alert')!='') { ?>
	<div class="alert <?php echo ($this->request->session()->read('success')==1)?'alert-success':'alert-error'?>">
		<button type="button" class="close" data-dismiss="alert">x</button>
		<strong>
		<?php 
			echo $this->request->session()->read('alert');
			$_SESSION['alert']='';
			?>
		</strong>
	</div>
	<?php } ?>
	<div class="row-fluid ">		
		<div class="box span12">
		     <div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> View Retailer</h2>
		      </div>
		<div class="box-content">
                   <table class="table table-striped table-bordered bootstrap-datatable Retailerdatatable">
			<thead>
				 <tr>
                                     <th>Id</th>
					<th class="hidden-phone ">Name</th>
					<th >Phone No</th>
                                        <th>RUC</th>
                                        <th>Address</th>
					<th>City</th>
                                        <th>Country</th>
                                        <th>Province</th>
                                        <th>Operational Model</th>
                                        <th>Status</th>
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
                                <?php if(!empty($retailers)) {
                                       foreach($retailers as $retailer){
                                            $remaining = 6-strlen($retailer['id']);
                                            $retailerId = '';
                                            for($i=0;$i<$remaining;$i++)
                                            $retailerId .= '0';
                                            $retailerId .= $retailer['id'];
 				?>
                                         <tr>
                                             <td><?php echo $retailerId;?></td>
						<td><?php echo $retailer['name'];?></td>
						<td class="hidden-phone"><?php echo $retailer['phone_no']?></td>
                                                <td class="hidden-phone"><?php echo $retailer['RUC']?></td>
                                                <td><?php echo $retailer['address']?></td>
                                                <td><?php echo $retailer['city_id']?></td>
                                                <td><?php echo $retailer['country_id']?></td>
                                                <td><?php echo $retailer['province_id']?></td>
                                                <td><?php echo $retailer['operation_model']?></td>
                                                <td><?php 
                                                                    if($retailer['status']==1)
                                                                        echo "<span class='label label-success'>Active</span>";
                                                                    else
                                                                        echo "<span class='label label-success' style='background:#ff0000;'>Inactive</span>";
                                                                    ?></td>
						<td class="center">
							<?php 
                                                        if($user_type != 3 && $user_type != 4)
                                                        {
                                                        echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">Edit</span> ',array('controller'=>'Retailer','action'=>'edit',base64_encode($retailer['id'])),array('class'=>'btn btn-small','escape'=>false))?>
                                                        <?php 
                                                            if($user_type != 2 && $user_type != 4)
                                                            {
                                                                echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">Delete</span> ',array('controller'=>'Retailer','action'=>'delete',base64_encode($retailer['id'])),array('class'=>'btn btn-small del_rec','escape'=>false));
                                                            }
                                                        }
                                                        ?>
                                                        <?php echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">View Users</span> ',array('controller'=>'user','action'=>'view',base64_encode($retailer['id']),'R'),array('class'=>'btn btn-small','escape'=>false));?>
                                                        <?php 
                                                                echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">Manage Accounts</span> ',array('controller'=>'Retailer','action'=>'manage_accounts',base64_encode($retailer['id'])),array('class'=>'btn btn-small','escape'=>false));
                                                                echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">View Stores</span> ',array('controller'=>'store','action'=>'view',base64_encode($retailer['id'])),array('class'=>'btn btn-small','escape'=>false));
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
