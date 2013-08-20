<h2>Create</h2>

<?php echo $error; ?>

<?php echo form_open('session/create') ?>

	<label for="login">Login</label> 
	<input type="text" name="login" /><br />

	<label for="passwd">Password</label> 
	<input type="password" name="passwd" /><br />
		
	<input type="submit" name="submit" value="Inscription" /> 

</form>  
