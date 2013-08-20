
<style>
	.error {
		color:red;
	}
</style>


<p>Entrez votre mot de passe actuel puis 
deux fois le nouveau mot de passe.
</p>

<?php echo form_open($url_form_post, 'data-ajax="false"'); ?>

	<div class='error'>
		<?php echo $change_error; ?>
	</div>
	
	<div>
		<div class='error'>
			<?php echo form_error('password'); ?>
		</div>
		<div data-role="fieldcontain">
			<label class="label" for='password'>Ancien mot de passe</label><br/>
			<input	type='password'
					name='password'
					value=''
					required />
			</div>
	</div>
	
	<p>Pour rappel, le mot de passe doit être composé d'au moins
cinq caractères.</p>
	
	<div>
		<div class='error'>
			<?php echo form_error('new_password'); ?>
		</div>
		<div data-role="fieldcontain">
			<label class="label" for='new_password'>Nouveau mot de passe</label><br/>
			<input	type='password'
					name='new_password'
					value=''
					required />
		</div>
	</div>
	
	
	<div>
		<div class='error'>
			<?php echo form_error('new_conf_password'); ?>
		</div>
		<div data-role="fieldcontain">
			<label class="label" for='new_conf_password'>Confirmation</label><br/>
			<input	type='password'
					name='new_conf_password'
					value=''
					required />
			</div>
	</div>

<?php
	echo form_submit('submit', 'Valider', 'data-theme="b"');
	echo form_close();
?>

