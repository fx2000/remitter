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
	<?php echo $this->Html->link('Settings' ,array('controller'=>'setting','action'=>'viewSetting'));?>
</div>
<div>
<?php echo $this->Html->link("<i class='fas fa-plus'></i> Add Setting", 
		array('controller' => 'setting','action'=> 'addSetting'), 
		array('class' => 'btn btn-success btn-simple btn-link pull-right', 'rel'=>'tooltip', 'escape' => false)) 
?>
</div>
<div class="row-fluid ">		
	<div class="box span12">
		<div class="box-header well" data-original-title>
		<h4><i class="fas fa-engine"></i> <?php echo "Settings"; ?></h4>
		</div>
		<div class="box-content">
            <table class="table table-striped table-bordered bootstrap-datatable salesretailerdatatable">
				<thead>
				 	<tr>
                        <th>Id</th>
                        <th class="hidden-phone">Country</th>
                        <th class="hidden-phone">Tax</th>
                        <th class="hidden-phone">Rate</th>
                        <th class="hidden-phone">Fee</th>
                        <th class="hidden-phone">Status</th>
						<th class="hidden-phone">Options</th>                        
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($settings AS $setting){ ?>
							<tr>
                                <td>
                                    <?php echo ($setting->id); ?>
                                </td>
                                <td>
                                    <?php echo ($setting->country); ?>
                                </td>
								<td>
									<?php echo ($setting->tax); ?>
                                </td>
                                <td>
									<?php echo ($setting->rate); ?>
                                </td>
                                <td>
									<?php echo ($setting->fee); ?>
								</td>
								<td style="align-item: center;">
									<?php echo '',($setting->status == 1 ? "<i class='fas fa-check'></i>" : 
										"<i class='fas fa-times'></i>");
									?>
								</td>
								<td>
									<?php echo $this->Html->link("<i class='fas fa-pencil-alt'></i> Edit", 
                                        array('controller' => 'Setting','action'=> 'editSetting', 
                                            base64_encode($setting->id)), 
                                        array('class' => 'btn btn-primary btn-round btn-sm', 
                                            'rel'=>'tooltip', 'escape' => false)) 
									?>
									<?php echo $this->Html->link("<i class='fas fa-trash'></i> Delete", 
                                        array('controller' => 'user','action'=> 'delete', 
                                            base64_encode($setting->id)), 
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
