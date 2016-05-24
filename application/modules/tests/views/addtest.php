<?php 
$this->load->view('header');
?>
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2 class="panel-title">Add Test</h2>
		</div>
		<form class="form" action="<?=base_url();?>tests/addtest" method="POST">
			<div class="panel-body">
				<?php foreach($fields as $fieldName=>$fieldInfo):?>
		    	<div class="form-group">
			    	<div class="input-group">
					  <input type="<?=$fieldInfo['type'];?>" class="form-control" placeholder="<?=$fieldInfo['label'];?>" name="<?=$fieldName;?>"  value="<?php echo set_value($fieldName); ?>" required />
					  <span class="input-group-addon" ><?=$fieldInfo['label'];?></span>
					</div>
					<?php echo form_error($fieldName); ?>
				</div>
				<?php endforeach;?>
				<div class="form-group">
					<input type="hidden" name="addTest" value="1" />
					<button class="btn btn-primary" type="submit">Add</button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php
$this->load->view('footer');
?>