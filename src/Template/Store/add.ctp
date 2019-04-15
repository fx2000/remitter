<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Add Store',array('controller'=>'Store','action'=>'add'));?>
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
						<h2><i class="icon-list-alt"></i> Add Store</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',['class'=>'form-horizontal']);?>
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
								    <?php echo $this->Form->input('Store.city_id', array('type' => 'select','options' => $cities,'default'=>'1','id'=>"city",'label'=>false))?>
                                                                    <script language="javascript" type="text/javascript">var f1 = new LiveValidation('city');f1.add( Validate.Presence);</script>
								  </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Province</label>
								   <div class="controls">
									<?php echo $this->Form->input('Store.province_id', array('type' => 'select','options' => $provinces,'default'=>'1','id'=>"province",'label'=>false))?>
                                                                        <script language="javascript" type="text/javascript">var f1 = new LiveValidation('province');f1.add( Validate.Presence);</script>
                                                                   </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Country</label>
								   <div class="controls">
									<?php echo $this->Form->input('Store.country_id', array('type' => 'select','options' => $countries,'default'=>'1','id'=>"country",'label'=>false))?>
                                                                        <script language="javascript" type="text/javascript">var f1 = new LiveValidation('country');f1.add( Validate.Presence);</script>
                                                                   </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Permission</label>
								   <div class="controls">
									<label class="radio">
										<input type="radio" name="Store[permission]" id="permission1" value="1" <?php echo ($this->request->data['Store']['permission']==1)?'checked=true':''?> <?php echo ($this->request->data['Store']['permission']=='')?'checked=true':''?>>
										Web
									</label>
									<div style="clear:both"></div>
									<label class="radio">
										<input type="radio" name="Store[permission]" id="permission2" value="2" <?php echo ($this->request->data['Store']['permission']==2)?'checked=true':''?>>
										Mobile
									</label>
                                                                    <div style="clear:both"></div>
									<label class="radio">
										<input type="radio" name="Store[permission]" id="permission3" value="3" <?php echo ($this->request->data['Store']['permission']==3)?'checked=true':''?>>
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
							<div class="form-actions">
							  <?php echo $this->Form->Submit('Submit',array('class'=>'btn btn-primary'));?>
							</div>
						    </div>
				</fieldset>
			</div>
		</div>
</div>