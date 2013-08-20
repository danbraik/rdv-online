
<style>
	.error {
		color:red;
	}
</style>

<?php echo form_open($url_form_post, 'data-ajax="false"'); ?>

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
				value='<?php echo form_prep(set_value('fname', $fname)); ?>'
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
				value='<?php echo form_prep(set_value('name', $name)); ?>'
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
				value='<?php echo form_prep(set_value('phone', $phone)); ?>'
				required/>
	</div>

<?php
	echo form_submit('submit', 'Valider', 'data-theme="b"');
	echo form_close();
?>


