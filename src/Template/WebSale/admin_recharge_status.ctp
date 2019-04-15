<?php
$URL = Configure::Read('Server.URL');
?>
<style>
    .lbl
    {
        padding-top:5px;
    }
</style>
<script>
    function printPopupCenter(transactionId) {
    var url = '<?php echo $URL?>admin/WebSale/recharge_print/'+transactionId
    var left = (screen.width/2)-(400/2);
    var top = (screen.height/2)-(400/2);
    return window.open(url, "Transaction Detail", 'resizable=0,toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+400+', height='+400+', top='+top+', left='+left);
    }
</script>
<div>
	<ul class="breadcrumb">
		<li>
			<?php echo $this->Html->link('Home',array('controller'=>'cpanel','action'=>'home'));?>
		</li>
		<li>/</li>
		<li>
			<?php echo $this->Html->link('Recharge Status',array('controller'=>'WebSale','action'=>'do_recharge'));?>
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
						<h2><i class="icon-list-alt"></i> Recharge Confirmation</h2>
		        </div>
			<div class="box-content">
<?php $data = $this->request->data['Recharge'];?>
				 <?php echo $this->Form->create('',array('url'=>array('controller'=>'WebSale','action'=>'recharge'),'class'=>'form-horizontal'));?>
			         <fieldset>
                                                        <div class="control-group">
								  <label class="control-label">Operator : </label>
								   <div class="controls lbl" >
									<?php echo $data['operator_id'];?>
                                                                   </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Phone No : </label>
								   <div class="controls lbl">
								    <?php echo $data['phone_no'];?>
								  </div>
							</div>
							<div class="control-group">
								  <label class="control-label">Topup Amount : </label>
								   <div class="controls lbl">
								    $<?php echo $data['amount'];?>
								  </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">Transaction Id : </label>
								   <div class="controls lbl">
								    <?php echo $data['transaction_id'];?>
								  </div>
							</div>
                                                        <div class="control-group">
								  <label class="control-label">DateTime : </label>
								   <div class="controls lbl">
								    <?php echo $data['datetime'];?>
								  </div>
							</div>
                                                        <?php
                                                            if($data['recharge_status'] == 0)
                                                            {
                                                        ?>
                                                        <div class="control-group">
								  <label class="control-label">New Amount : </label>
								   <div class="controls lbl">
								    <?php echo '$'.$data['new_amt'];?>
								  </div>
							</div>
                                                        <?php
                                                            }
                                                            if($data['recharge_status'] != 0)
                                                            {
                                                        ?>
                                                        <div class="control-group">
								  <label class="control-label ">Error Code : </label>
								   <div class="controls lbl">
								    <?php echo $data['recharge_status'];?>
								  </div>
							</div>
                                                        <?php 
                                                            }   
                                                        ?>
							<div class="form-actions">
                                                            <button type="button" class="btn btn-primary" style="float:left;" onclick="printPopupCenter(<?php echo $data['transaction_id']?>)">Print</button>
							  <?php echo $this->Form->Submit('Finish',array('class'=>'btn btn-primary','style'=>'float:left;margin-left:20px;'));?>
							</div>
						    </div>
				</fieldset>
			</div>
		</div>
</div>