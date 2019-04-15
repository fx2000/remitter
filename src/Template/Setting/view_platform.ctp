<?php
?>
 	<div>
		<ul class="breadcrumb">
			<li>
				<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
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
						<h2><i class="icon-user"></i> Mobile Topup Platform</h2>
		      </div>
		<div class="box-content">
                   <table class="table table-striped table-bordered bootstrap-datatable Platformdatatable">
			<thead>
				 <tr>
					<th>Operator</th>
                                        <th>Product Id</th>
					<th class="hidden-phone ">IP Address</th>
                                        <th>Port</th>
					<th>Username</th>
                                        <th>Action</th>
				</tr>
			</thead>
			 <tbody>
                                <?php if(!empty($operators_credentials)) {
                                       foreach($operators_credentials as $cred){
 				?>
                                         <tr>
						<td><?php echo $cred['name'];?></td>
                                                <td><?php echo $cred['OperatorCredential']['product_id']!=''?$cred['OperatorCredential']['product_id']:'N/A';?></td>
						<td class="hidden-phone">
                                                    <?php echo $cred['OperatorCredential']['ip_address']!=''?$cred['OperatorCredential']['ip_address']:'N/A'?>
						</td>
                                                <td class="hidden-phone">
                                                    <?php echo $cred['OperatorCredential']['port']!=''?$cred['OperatorCredential']['port']:'N/A'?>
						</td>
                                                <td><?php echo $cred['OperatorCredential']['username']!=''?$cred['OperatorCredential']['username']:'N/A';?></td>
						<td class="center">
							<?php echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">Edit</span> ',array('controller'=>'Setting','action'=>'edit_platform',base64_encode($cred['id'])),array('class'=>'btn btn-small','escape'=>false))?>
                                                        <?php echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">Change Password</span> ',array('controller'=>'setting','action'=>'change_password',base64_encode($cred['id'])),array('class'=>'btn btn-small','escape'=>false));?>
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
