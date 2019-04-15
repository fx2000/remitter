<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Add SlideShow Image',array('controller'=>'AppSetting','action'=>'add_slideshow_img'));?>
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
						<h2><i class="icon-list-alt"></i> Add SlideShow Image</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',['class'=>'form-horizontal','enctype'=>'multipart/form-data']);?>
			         <fieldset>
							<div class="control-group">
								  <label class="control-label">Choose image</label>
								   <div class="controls">
								    <input type="file" id="file" name="Slideshow[file]" class="file" >
                                                                    <script language="javascript" type="text/javascript">var f1 = new LiveValidation('file');f1.add( Validate.Presence);</script>
								  </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Time ( in seconds )</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Slideshow.time',array('type'=>'text','class'=>'input-large ','id'=>'time','div'=>false,'label'=>false,'maxlength'=>100,'data-rel'=>'tooltip','data-original-title'=>'Time'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('time');f1.add( Validate.Presence);f1.add( Validate.NumberValid);f1.add( Validate.Minute);</script>
								  </div>
							</div>
                                                        <div class="control-group">
							<label class="control-label">Status</label>
							<div class="controls">
							
							<?php echo $this->Form->input('Slideshow.status',array('type'=>'checkbox','checked'=>true,'class'=>'iphone-toggle ','id'=>'status','div'=>false,'label'=>false,'data-no-uniform'=>'true'));?>
							</div>
                                                        </div>
							<div class="form-actions">
							  <?php echo $this->Form->Submit('Submit',array('class'=>'btn btn-primary'));?>
							</div>
						    </div>
				</fieldset>
			</div>
		</div>
</div>