<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Notification',array('controller'=>'Setting','action'=>'notification'));?>
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
						<h2><i class="icon-list-alt"></i> Notification</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',array('url'=>array('controller'=>'Setting','action'=>'notification'),'class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
			         <fieldset>
							<div class="control-group">
								  <label class="control-label">Server Name</label>
								   <div class="controls">
								   <?php echo $this->Form->input('SmtpSetting.server_name',array('type'=>'text','class'=>'input-large ','id'=>'servername','div'=>false,'label'=>false,'data-original-title'=>'Server Name','placeholder'=>'Server Name'));?>
                                                                    <?php echo $this->Form->input('SmtpSetting.id',array('type'=>'hidden','value'=>$this->request->data['SmtpSetting']['id'],'class'=>'input-large ','id'=>'row_id','div'=>false,'label'=>false));?>
                                                                    <script language="javascript" type="text/javascript">var f1 = new LiveValidation('servername');f1.add( Validate.Presence);f1.add( Validate.passwordchange);</script>
                                                                </div>
                                                        </div>
							<div class="control-group">
								  <label class="control-label">Port</label>
								   <div class="controls">
								    <?php echo $this->Form->input('SmtpSetting.port',array('type'=>'text','class'=>'input-large ','id'=>'port','div'=>false,'label'=>false,'maxlength'=>100,'data-rel'=>'tooltip','data-original-title'=>'Port','placeholder'=>'Port'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('port');f1.add( Validate.Presence);f1.add( Validate.NumberValid)</script>
								  </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Username</label>
								   <div class="controls">
								   <?php echo $this->Form->input('SmtpSetting.username',array('type'=>'text','class'=>'input-large ','id'=>'username','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Username','placeholder'=>'Username'));?>
                                                                    <script language="javascript" type="text/javascript">var f1 = new LiveValidation('username');f1.add( Validate.Presence);</script>
								  </div>
							</div>
                                                          <div class="control-group">
							  <label class="control-label" for="textarea2">Password</label>
							  <div class="controls">
                                                              <?php echo $this->Form->input('SmtpSetting.password',array('type'=>'password','class'=>'input-large ','id'=>'password','div'=>false,'label'=>false,'placeholder'=>'Password'));?>
								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('password');f1.add( Validate.Presence);f1.add( Validate.passwordchange);</script>
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