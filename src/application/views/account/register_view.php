
<p>
Grâce au compte familial, vous allez pouvoir gérer les rendez-vous de tous
les membres de votre famille avec les mêmes identifiants de connexion !
</p>

<style>
	.error {
		color:red;
	}
</style>

<?php echo form_open($post_url, 'data-ajax="false"'); ?>

	<div class='error'>
		<?php echo $register_error; ?>
	</div>
	<div>
		<p>Commencez par indiquer votre adresse mail, elle servira 
		à vous identifier et à obtenir un nouveau mot de passe en cas d'oubli.</p>
		<div class='error'>
			<?php echo form_error('mail'); ?>
		</div>
		<div data-role="fieldcontain">
			<label class="label" for='mail'>Email</label><br/>
			<input	type='email' 
					name='mail' 
					value='<?php echo form_prep(set_value('mail')); ?>'
					required />
		</div>
	</div>

	<p>Choisissez un mot de passe d'au moins 5
	caractères. 
	</p>
	<div>
		<div class='error'>
			<?php echo form_error('password'); ?>
		</div>
		<div data-role="fieldcontain">
			<label class="label" for='password'>Mot de passe</label><br/>
			<input	type='password' 
					name='password' 
					value=''
					required />
		</div>
	</div>
	
	<div>
		<div class='error'>
			<?php echo form_error('password_conf'); ?>
		</div>
		<div data-role="fieldcontain">
			<label class="label" for='password_conf'>Confirmation du mot de passe</label><br/>
			<input	type='password' 
					name='password_conf'
					value=''
					required/>
		</div>
	</div>

<?php
	echo form_submit('submit', 'S\'inscrire', 'data-theme="b"');
	echo form_close();
