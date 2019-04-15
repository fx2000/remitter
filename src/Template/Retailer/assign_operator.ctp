<script>
    function checkSelection()
    {
        var checkedVals = $('.operator:checkbox:checked').map(function() {
            return this.value;
        }).get();
        if(checkedVals == '')
        {
            alert('Please select an operator to assign.');
            return false;
        }
        return true;
    }
</script>
<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Assign Operators',array('controller'=>'Retailer','action'=>'assign_operator',$this->request->params['pass'][0]));?>
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
						<h2><i class="icon-list-alt"></i> Assign Operators</h2>
		        </div>
			<div class="box-content">
				 <?php echo $this->Form->create('',['class'=>'form-horizontal']);?>
			         <fieldset>
                                            <div class="control-group">
                                                        <?php 
                                                            if(!empty($operators))
                                                            {
                                                        ?>
								  <label class="control-label">Operators</label>
                                                                  <?php
								  //echo "<pre>";print_r($operators);
                                                                    foreach($operators As $operator)
                                                                    { 
                                                                        if($operator->status == 1)
                                                                        {
                                                                  ?>
								  <div class="controls">
									<label class="radio">
                                                                            <input type="checkbox" name="AccountOperator[operator_id][]" id="operator<?php echo $operator->id?>" value="<?php echo $operator->id?>" class="operator"> 
										<?php echo $operator->name;?>
									</label>
								  </div>
                                                                 <?php 
                                                                        }
                                                                    }
                                                            }
                                                            else
                                                            {
                                                                echo "There is no operator to assign.";
                                                            }
                                                                 ?>
                                                        </div>
                                                        <?php 
                                                            if(!empty($operators))
                                                            {
                                                        ?>
                                                                <div class="form-actions">
                                                                    <?php echo $this->Form->Submit('Assign',array('class'=>'btn btn-primary','onclick'=>'return checkSelection()'));?>
                                                                </div>
                                                        <?php 
                                                            }
                                                        ?>
						    </div>
				</fieldset>
<div class="row-fluid ">		
		<div class="box span12">
		     <div class="box-header well" data-original-title>
			<h2><i class="icon-user"></i>Assigned Operators</h2>
		      </div>
		<div class="box-content">
                   <table class="table table-striped table-bordered bootstrap-datatable AssignedOperatorDataTable">
			<thead>
                            <tr>
				<th class="hidden-phone ">Operator</th>
                                <th>Action</th>
                            </tr>
			</thead>
			 <tbody>
                                <?php if(!empty($account_operators)) {
                                        foreach($account_operators as $account_operator){
 				?>
                                            <tr>
                                                <td><?php echo $all_operators[$account_operator->operator_id];?></td>
                                                <td class="center">
                                                    <?php echo $this->html->link('<i class="icon-edit icon-black"></i><span class="hidden-phone">Delete</span> ',array('controller'=>'Retailer','action'=>'delete_account_operator',base64_encode($account_operator->id)),array('class'=>'btn btn-small del_rec','escape'=>false));?>
                                                </td>
                                            </tr>	
                                <?php 
                                        }
                                    }	
				?>			
			</tbody>   
		   <table>
		</div>
	</div>
</div>
			</div>
		</div>
</div>