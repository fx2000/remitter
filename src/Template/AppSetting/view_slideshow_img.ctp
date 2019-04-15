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
				<?php echo $this->Html->link('Slideshow Images',array('controller'=>'AppSetting','action'=>'view_slideshow_img'));?>
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
						<h2>Slideshow Images</h2>
		      </div>
		<div class="box-content">
                   <table class="table table-striped table-bordered bootstrap-datatable SlideShowdatatable">
			<thead>
				 <tr>
					<th>Image</th>
                                        <th class="hidden-phone ">Time ( in seconds )</th>
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
                                <?php if(!empty($images)) {
                                       foreach($images as $img){
 				?>
                                         <tr>
                                             <td><img src="<?php echo $URL.$img->image_path ?>" alt="image" width="50" height=""></td>
					     <td class="hidden-phone"><?php echo $img->time;?></td>
                                                                <td class="hidden-phone">
                                                                    <?php 
                                                                    if($img->status==1)
                                                                        echo "<span class='label label-success'>Active</span>";
                                                                    else
                                                                        echo "<span class='label label-success' style='background:#ff0000;'>Inactive</span>";
                                                                    ?>
                                                                </td>
                                                                <?php 
                                                                        if($user_type != 3)
                                                                        {
                                                                            ?>
								<td class="center">
                                          				<?php
                                                                            echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">Edit</span> ',array('controller'=>'AppSetting','action'=>'edit_slideshow_img',base64_encode($img->id)),array('class'=>'btn btn-small','escape'=>false));
                                                                            echo $this->html->link('<i class="icon-trash icon-black"></i></i><span class="hidden-phone">Delete</span>',array('controller'=>'AppSetting','action'=>'delete_slideshow_img',base64_encode($img->id)),array('class'=>'btn btn-small del_rec','escape'=>false)); 
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
