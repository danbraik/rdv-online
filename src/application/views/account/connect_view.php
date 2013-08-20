<p>
	Entrez l'adresse mail et le mot de passe
	que vous aviez choisis à l'inscription.
</p>

<style>
	.error {
		color:red;
	}
	
</style>

<?php echo form_open($post_url, 'data-ajax="false"'); ?>

	<div class='error'>
		<?php echo $connect_error; ?>
	</div>

	<div>
		<div class='error'>
			<?php echo form_error('mail'); ?>
		</div>
		
		<div data-role="fieldcontain">
			<label class="label" for="mail">Email</label><br/>
			<input	type='email'
					name='mail'
					autocomplete='on'
					value='<?php echo form_prep(set_value('mail')); ?>'
					required
					/> <!--autofocus-->
		</div>
	</div>

	<div>
		<div class='error'>
			<?php echo form_error('password'); ?>
		</div>
		
		<div data-role="fieldcontain">
			<label class="label" for="mail">Mot de passe</label><br/>
			<input	type='password' 
					name='password'
					autocomplete='off' 
					value=''
					required />
			<br/>
			<a href="<?php echo $url_forgotten; ?>" >Mot de passe oublié</a>
		</div>
		
	</div>

<?php
	echo form_submit('submit', 'S\'authentifier', 'data-theme="b"');
	echo form_close();
?>

<br/>
<br/>


