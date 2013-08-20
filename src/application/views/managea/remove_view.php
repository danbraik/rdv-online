<p><strong>Attention, si vous supprimez votre compte,
tous les rendez-vous seront automatiquement annulés.
Tous les membres seront supprimés et vous ne pourrez plus
vous connecter avec vos identifiants.
</strong>
<br/>
Afin de reprendre rendez-vous, vous devrez créer un nouveau
compte.
</p>
<p>
Si vous êtes certain de vouloir supprimer le compte,
entrez votre mot de passe et validez. Vous 
serez renvoyés sur la page d'accueil.
</p>


<style>
	.error {
		color:red;
	}
</style>


<?php echo form_open($url_form_post, 'data-ajax="false"'); ?>

	<div class='error'>
		<?php echo $change_error; ?>
	</div>
	
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
	
<?php
	echo form_submit('submit', 'Supprimer le compte', 'data-theme="a"');
	echo form_close();
?>


<a href="<?php echo $url_back;?>" data-role="button">Annuler</a>

