<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Edit Store',array('controller'=>'Store','action'=>'edit',$this->request->params['pass'][0],$this->request->params['pass'][1]));?>
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
						<h2><i class="icon-list-alt"></i> Edit Store</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',array('url'=>array('controller'=>'Store','action'=>'edit',$this->request->params['pass'][0],$this->request->params['pass'][1]),'class'=>'form-horizontal'));?>
			         <fieldset>
							<div class="control-group">
								  <label class="control-label">Name</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Store.name',array('type'=>'text','class'=>'input-large ','id'=>'name','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Name'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('name');f1.add( Validate.Presence);</script>
								  </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Phone No</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Store.phone_no',array('type'=>'text','class'=>'input-large ','id'=>'phone','div'=>false,'label'=>false,'maxlength'=>100,'data-rel'=>'tooltip','data-original-title'=>'Phone No'));?>
 								<script language="javascript" type="text/javascript">var f1 = new LiveValidation('phone');f1.add( Validate.Presence);</script>
								  </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Address</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Store.address',array('type'=>'textarea','class'=>'input-large ','id'=>'address','div'=>false,'label'=>false,'data-rel'=>'tooltip','data-original-title'=>'Address'));?>
                                                                    <script language="javascript" type="text/javascript">var f1 = new LiveValidation('address');f1.add( Validate.Presence);</script>
								  </div>
							</div>
							
							<div class="control-group">
								  <label class="control-label">City</label>
								   <div class="controls">
								    <?php echo $this->Form->input('Store.city_id', array('type' => 'select','options' => $cities,'empty'=>'Select City','id'=>"city",'label'=>false))?>
                                                                    <script language="javascript" type="text/javascript">var f1 = new LiveValidation('city');f1.add( Validate.Presence);</script>
								  </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Province</label>
								   <div class="controls">
									<?php echo $this->Form->input('Store.province_id', array('type' => 'select','options' => $provinces,'empty'=>'Select Province','id'=>"province",'label'=>false))?>
                                                                        <script language="javascript" type="text/javascript">var f1 = new LiveValidation('province');f1.add( Validate.Presence);</script>
                                                                   </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Country</label>
								   <div class="controls">
									<?php echo $this->Form->input('Store.country_id', array('type' => 'select','options' => $countries,'empty'=>'Select Country','id'=>"country",'label'=>false))?>
                                                                        <script language="javascript" type="text/javascript">var f1 = new LiveValidation('country');f1.add( Validate.Presence);</script>
                                                                   </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Permission</label>
								   <div class="controls">
									<label class="radio">
										<input type="radio" name="data[Store][permission]" id="permission1" value="1" <?php echo ($this->data['Store']['permission']==1)?'checked=true':''?> <?php echo ($this->data['Store']['permission']=='')?'checked=true':''?>>
										Web
									</label>
									<div style="clear:both"></div>
									<label class="radio">
										<input type="radio" name="data[Store][permission]" id="permission2" value="2" <?php echo ($this->data['Store']['permission']==2)?'checked=true':''?>>
										Mobile
									</label>
                                                                    <div style="clear:both"></div>
									<label class="radio">
										<input type="radio" name="data[Store][permission]" id="permission3" value="3" <?php echo ($this->data['Store']['permission']==3)?'checked=true':''?>>
										Both
									</label>
								  </div>
                                                        </div>
                                                       
                                                        <div class="control-group">
								  <label class="control-label">Retailer</label>
								   <div class="controls">
									<?php echo $this->Form->input('Store.retailer_id', array('type' => 'select','options' => $retailers,'empty'=>'Select Retailer','id'=>"retailer",'label'=>false))?>
                                                                        <script language="javascript" type="text/javascript">var f1 = new LiveValidation('retailer');f1.add( Validate.Presence);</script>
                                                                   </div>
							</div>
                                                        <div class="control-group">
							<label class="control-label">Status</label>
							<div class="controls">
							
							<?php echo $this->Form->input('Store.status',array('type'=>'checkbox','class'=>'iphone-toggle ','id'=>'status','div'=>false,'label'=>false,'data-no-uniform'=>'true'));?>
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