
<p>
Indiquez les informations du nouveau membre.
</p>

<style>
	.error {
		color:red;
	}
</style>

<?php echo form_open($post_url); ?>

	<div class='error'>
		<?php echo $create_error; ?>
	</div>
	
	<div>
		<div class='error'>
			<?php echo form_error('fname'); ?>
		</div>
		<input	type='text'
				name='fname'
				autocomplete='on'
				placeholder='Prénom'
				value=''
				required/>
	</div>
	
	

	<div>
		<div class='error'>
			<?php echo form_error('name'); ?>
		</div>
		<input	type='text'
				name='name'
				autocomplete='on'
				placeholder='Nom'
				value='<?php echo $name; ?>'
				required/>
	</div>

		
	
	<div>
		<div class='error'>
			<?php echo form_error('phone'); ?>
		</div>
		<input	type='tel'
				name='phone'
				autocomplete='on'
				placeholder='Téléphone'
				value='<?php echo $phone; ?>'
				required/>
	</div>

<?php
	echo form_submit('submit', 'Valider', 'data-theme="b"');
	echo form_close();
