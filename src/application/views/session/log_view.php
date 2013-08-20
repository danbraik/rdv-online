<h2>Connection</h2>

<?php echo $error; ?>

<?php echo form_open($post_url) ?>

	<label for="login">Login</label> 
	<input type="text" name="login" /><br />

	<label for="passwd">Password</label> 
	<input type="password" name="passwd" /><br />
	
	<input type="submit" name="submit" value="Log in" /> 

</form> 
