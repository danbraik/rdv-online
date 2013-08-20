<p>
	Entrez l'adresse mail du compte.
	
</p>

<style>
	.error {
		color:red;
	}
</style>

<?php echo form_open($url_form_post, 'data-ajax="false"'); ?>

	<div class='error'>
		<?php echo $error; ?>
	</div>

	<div>
		<div class='error'>
			<?php echo form_error('mail'); ?>
		</div>
		<input	type='email'
				name='mail'
				autocomplete='on'
				placeholder='Email'
				value='<?php echo form_prep(set_value('mail')); ?>'
				required
				/> <!--autofocus-->
	</div>


<?php
	echo form_submit('submit', 'Valider', 'data-theme="b"');
	echo form_close();

?>

