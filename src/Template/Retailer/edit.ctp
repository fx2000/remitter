<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Edit Retailer',array('controller'=>'Retailer','action'=>'edit',$this->request->params['pass'][0]));?>
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
						<h2><i class="icon-list-alt"></i> Edit Retailer</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',array('url'=>array('controller'=>'Retailer','action'=>'edit',$this->request->params['pass'][0]),'class'=>'form-horizontal'));?>
			         <fieldset>
							<div class="control-group">
								  <label class="control-label">Name</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Retailer.name',array('type'=>'text','class'=>'input-large ','id'=>'name','div'=>false,'label'=>false,'data-rel'=>'tooltip','maxlength'=>'255','data-original-title'=>'Name'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('name');f1.add( Validate.Presence);</script>
								  </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Email</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Retailer.email',array('type'=>'text','class'=>'input-large ','id'=>'email','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Email','maxlength'=>'255'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('email');f1.add( Validate.Presence);f1.add( Validate.Email);</script>
								  </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Phone No</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Retailer.phone_no',array('type'=>'text','class'=>'input-large ','id'=>'phone','div'=>false,'label'=>false,'maxlength'=>100,'data-rel'=>'tooltip','data-original-title'=>'Phone No'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('phone');f1.add( Validate.Presence);</script>
								  </div>
							</div>
							<div class="control-group">
								  <label class="control-label">RUC</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Retailer.RUC',array('type'=>'text','class'=>'input-large ','id'=>'ruc','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'RUC','maxlength'=>'255'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('ruc');f1.add( Validate.Presence);</script>
								  </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Address</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Retailer.address',array('type'=>'textarea','class'=>'input-large','id'=>'address','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Address','maxlength'=>'255'));?>
                                    <script language="javascript" type="text/javascript">var f1 = new LiveValidation('address');f1.add( Validate.Presence);</script>                               
								  </div>
							</div>
							
							<div class="control-group">
								  <label class="control-label">City</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Retailer.city_id', array('type' => 'select','options' => $cities,'empty'=>'Select City','id'=>"city",'label'=>false))?>
                                                                    <script language="javascript" type="text/javascript">var f1 = new LiveValidation('city');f1.add( Validate.Presence);</script>
								  </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Province</label>
								   <div class="controls">
									<?php echo $this->Form->input('Retailer.province_id', array('type' => 'select','options' => $provinces,'empty'=>'Select Province','id'=>"province",'label'=>false))?>
                                                                        <script language="javascript" type="text/javascript">var f1 = new LiveValidation('province');f1.add( Validate.Presence);</script>
                                                                   </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Country</label>
								   <div class="controls">
									<?php echo $this->Form->input('Retailer.country_id', array('type' => 'select','options' => $countries,'empty'=>'Select Country','id'=>"country",'label'=>false))?>
                                                                        <script language="javascript" type="text/javascript">var f1 = new LiveValidation('country');f1.add( Validate.Presence);</script>
                                                                   </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Operational Model</label>
								   <div class="controls">
									<label class="radio">
										<input type="radio" name="Retailer[operation_model]" id="model1" value="1" <?php echo ($this->request->data['Retailer']['operation_model']==1)?'checked=true':''?> <?php echo ($this->request->data['Retailer']['operation_model']=='')?'checked=true':''?>>
										Shared
									</label>
									<div style="clear:both"></div>
									<label class="radio">
										<input type="radio" name="Retailer[operation_model]" id="model2" value="2" <?php echo ($this->request->data['Retailer']['operation_model']==2)?'checked=true':''?>>
										Individual
									</label>
								  </div>
							</div>
							<div class="control-group">
								<label class="control-label">Status</label>
								<div class="controls">
								
								<?php echo $this->Form->input('Retailer.status',array('type'=>'checkbox','class'=>'iphone-toggle ','id'=>'status','div'=>false,'label'=>false,'data-no-uniform'=>'true'));?>
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
