<?php 
$this->load->view('header');
?>


<div class="container">
  <div class="row">
  	<div class="col-md-8 col-md-offset-2">
  		<div class="panel panel-default">
  			<div class="panel-heading">
			    <h3 class="panel-title">Login to the Pathology Lab</h3>
			  </div>
		  <div class="panel-body">
		    <form class="form" action="login" method="POST">
		    	<div class="form-group">
			    	<div class="input-group">
					  <input type="email" class="form-control" placeholder="Username/email address" name="username" required>
					  <span class="input-group-addon" >johndoe@exmaple.com</span>
					</div>
					<?php echo form_error('username'); ?>
				</div>
				<div class="form-group">
					<div class="input-group">
					  <input type="password" class="form-control" placeholder="Password" name = "password" required>
					  <span class="input-group-addon">****</span>
					</div>
					<?php echo form_error('password'); ?>
				</div>
				<?php if(isset($invalidLoginDetails)) :?>
					<div class="alert alert-danger" role="alert">
					  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					  <span class="sr-only">Error:</span>
					  You've entered some invalid login details.
					</div>
				<?php endif; ?>
				<div class="form-group">
					<input type="hidden" name="login" value="1" />
					<button class="btn btn-primary" type="submit">Login</button>
				</div>
		    </form>
		  </div>
		</div>
  	</div>
  </div>
  
</div>

<?php
$this->load->view('footer');
?>