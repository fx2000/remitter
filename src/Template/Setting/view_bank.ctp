<?php 
	$user_type = $this->request->session()->read('user_type');
	if ($this->request->session()->read('alert') != '') {
?>
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
<div class="breadcrumb">
	<?php echo $this->Html->link('Dashboard / ',array('controller'=>'cpanel','action'=>'home'));?>
	<?php echo $this->Html->link('Banks' ,array('controller'=>'setting','action'=>'viewBank'));?>
</div>
<div>
<?php echo $this->Html->link("<i class='fas fa-plus'></i> Add Bank", 
		array('controller' => 'setting','action'=> 'addBank'), 
		array('class' => 'btn btn-success btn-simple btn-link pull-right', 'rel'=>'tooltip', 'escape' => false)) 
?>
</div>
<div class="row-fluid ">		
	<div class="box span12">
		<div class="box-header well" data-original-title>
		<h4><i class="fas fa-bank"></i> <?php echo "Banks"; ?></h4>
		</div>
		<div class="box-content">
            <table class="table table-striped table-bordered bootstrap-datatable salesretailerdatatable">
				<thead>
				 	<tr>
                        <th>Id</th>
                        <th class="hidden-phone">Country</th>
                        <th class="hidden-phone">Name</th>
                        <th class="hidden-phone">Status</th>
						<th class="hidden-phone">Options</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($banks AS $bank){ ?>
							<tr>
                                <td>
                                    <?php echo ($bank->id); ?>
                                </td>
                                <td>
                                    <?php echo ($bank->country); ?>
                                </td>
								<td>
									<?php echo $this->Html->link($bank->name, 
										array('controller' => 'setting','action'=> 'editBank', base64_encode($bank->id))); 
									?>
								</td>
								<td style="align-item: center;">
									<?php echo '',($bank->status == 1 ? "<i class='fas fa-check'></i>" : 
										"<i class='fas fa-times'></i>");
									?>
								</td>
								<td>
									<?php echo $this->Html->link("<i class='fas fa-pencil-alt'></i> Edit", 
                                        array('controller' => 'user','action'=> 'edit', 
                                            base64_encode($bank->id)), 
                                        array('class' => 'btn btn-primary btn-round btn-sm', 
                                            'rel'=>'tooltip', 'escape' => false)) 
									?>
									<?php echo $this->Html->link("<i class='fas fa-trash'></i> Delete", 
                                        array('controller' => 'user','action'=> 'delete', 
                                            base64_encode($bank->id)), 
                                        array('class' => 'btn btn-danger btn-round btn-sm', 
                                            'rel'=>'tooltip', 'escape' => false)) 
									?>
								</td>
							</tr>
					<?php	
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
