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
						<h2><i class="icon-user"></i> Operator</h2>
		      </div>
		<div class="box-content">
                   <table class="table table-striped table-bordered bootstrap-datatable Userdatatable">
			<thead>
				 <tr>
					<th>Name</th>
					<th class="hidden-phone ">Status</th>
					<th>Actions</th>
				</tr>
			</thead>
			 <tbody>
                                <?php if(!empty($this->request->data)) {
                                       foreach($this->request->data as $val){
 				?>
                                         <tr>
						<td><?php echo $val->name;?></td>
						<td class="hidden-phone">
							<?php if($val->status==1)
								echo "<span class='label label-success'>ON</span>";
								else
								echo "<span class='label label-warning'>OFF</span>";
								?>
							
						</td>
						<td class="center">
							<?php if($val->status==0) {echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">ON</span> ',array('controller'=>'setting','action'=>'operator_change',base64_encode($val->id),1),array('class'=>'btn btn-small','escape'=>false));}?>
							<?php if($val->status==1) {echo $this->html->link('<i class="icon-trash icon-black"></i></i><span class="hidden-phone">OFF</span>               ',array('controller'=>'setting','action'=>'operator_change',base64_encode($val->id),0),array('class'=>'btn btn-small','escape'=>false)); }?>
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
