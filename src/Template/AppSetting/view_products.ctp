<?php 
$user_type = $this->Session->read('user_type');
?>
<div>
		<ul class="breadcrumb">
			<li>
				<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
			</li>
			<li>/</li>
			<li>
				<?php echo $this->Html->link('Products',array('controller'=>'AppSetting','action'=>'view_products'));?>
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
						<h2>Products</h2>
		      </div>
		<div class="box-content">
                   <table class="table table-striped table-bordered bootstrap-datatable Productdatatable">
			<thead>
				 <tr>
                                     <th style="display: none;">Id</th>
					<th>Barcode Image</th><th>Barcode No.</th>
                                        <th class="hidden-phone ">Operator</th>
					<th class="hidden-phone ">Amount</th>
                                        <th class="hidden-phone ">Status</th>
                                        <?php 
                                        if($user_type != 3)
                                        {
                                        ?>
                                            <th>Actions</th>
                                        <?php 
                                        }
                                        ?>
				</tr>
			</thead>
			 <tbody>
                                <?php if(!empty($products)) {
                                       foreach($products as $product){
 				?>
                                         <tr>
                                             <td style="display: none;"><?php echo $product->id;?></td>
                                             <td><img src="<?php echo $URL.$product->barcode_image ?>" alt="image" width="100px" height="50px"></td>
						<td class="hidden-phone"><?php echo $product->barcode_no!=''?$product->barcode_no:'N/A';?></td>		
                                             <td class="hidden-phone"><?php echo $product->operator_id;?></td>
                                                                <td class="hidden-phone">$<?php echo $product->amount;?></td>
                                                                <td class="hidden-phone">
                                                                    <?php 
                                                                    if($product->status==1)
                                                                        echo "<span class='label label-success'>Active</span>";
                                                                    else
                                                                        echo "<span class='label label-success' style='background:#ff0000;'>Inactive</span>";
                                                                    ?>
                                                                </td>
                                                              
                                                                
								<td class="center">
                                                                      <?php 
                                                                if($user_type != 3)
                                                                {
                                                                  echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">Edit</span> ',array('controller'=>'AppSetting','action'=>'edit_products',base64_encode($product->id)),array('class'=>'btn btn-small','escape'=>false));
                                                                  echo $this->html->link('<i class="icon-trash icon-black"></i></i><span class="hidden-phone">Delete</span>',array('controller'=>'AppSetting','action'=>'delete_products',base64_encode($product->id)),array('class'=>'btn btn-small del_rec','escape'=>false)); 
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
