<style>
	.error {
		color:red;
	}
</style>

	<div class='error'>
		<?php echo $error; ?>
	</div>


<p>
	<?php if ($error=='') : ?>
		Le nouveau mot de passe vous a été envoyé par mail.
	
	<?php endif; ?>
</p>


<a href="<?php echo $url_next; ?>" data-role="button">Connexion</a>
