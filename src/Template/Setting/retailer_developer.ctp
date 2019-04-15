

<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Manage Retailer Developer',array('controller'=>'Setting','action'=>'report_setting'));?>
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
<style>
 .checkbox.inline + .checkbox.inline {
     margin-left: 0px;
}
</style>
<script>

function getStores()
{
    if($('#retailer').val() != '')
        var path='<?php echo $URL?>/inventory/get_stores/'+$('#retailer').val();
    else
        var path='<?php echo $URL?>/inventory/get_stores/0';
	       console.log(path);
    var request = $.ajax({
			url: path,
			success: function(data) {
				//alert(data);
				$('#stores').empty();
				$('#stores').append(data);
			  },
			
		});
	
	request.fail(function (jqXHR, textStatus, errorThrown){
			console.log(jqXHR);
                        
		});
}
</script>
<div class="row-fluid ">	
		<div class="box span12">
		       <div class="box-header well" data-original-title>
						<h2><i class="icon-list-alt"></i> Add Store Developer</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',['class'=>'form-horizontal','enctype'=>'multipart/form-data']);?>
			         <fieldset>
							<div class="control-group">
									<label class="control-label">Retailer</label>
								   <div class="controls">
									<?php echo $this->Form->input('Developer.retailer_id', array('type' => 'select','options' => $retailers,'empty'=>'Select Retailer','id'=>"retailer",'label'=>false, 'onchange' => 'getStores()' ))?>
									<script language="javascript" type="text/javascript">var f1 = new LiveValidation('retailer');f1.add( Validate.Presence);</script>
								  </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Store</label>
								  <div class="controls">
									<?php echo $this->Form->input('Developer.store_id', array('type' => 'select','empty'=>'Select Store','id'=>"stores",'label'=>false ))?>
								  </div>
							</div>
							<div class="form-actions">
							  <?php echo $this->Form->Submit('Submit',array('class'=>'btn btn-primary'));?>
							</div>
						    </div>
				</fieldset>
			</div>
			
			
<div class="row-fluid ">		
		<div class="box span12">
		     <div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i>View Developer</h2>
		      </div>
		<div class="box-content">
                   <table class="table table-striped table-bordered bootstrap-datatable">
			<thead>
				 <tr>
					<th>Retailer</th>
					<th >Store</th>
					<th>Developer Key</th>
                    <th>Secret Password</th>
					<th>Action</th>                 
				</tr>
			</thead>
			 <tbody>
                                <?php if(!empty($settings)) {
                                       foreach($settings as $account){
 				?>
                        <tr>
							<td><?php echo $account['Retailer']['name'];?></td>
							<td><?php echo $account['Store']['name'];?></td>
							<td><?php echo $account['developer_key'];?></td>
							<td><?php echo $account['secret_key'];?></td>
                                               
							<td class="center">
								<?php 
										echo $this->html->link('<i class="icon-trash icon-black"></i><span class="hidden-phone">Delete</span> ',array('controller'=>'Setting','action'=>'delete_developer',base64_encode($account['id'])),array('class'=>'btn btn-small del_rec','escape'=>false));	
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


