
      


<style>
	.error {
		color:red;
	}
</style>


<div>
	<?php echo form_open($url_form_post); ?>
		
		<div>
			<div class='error'>
				<?php echo form_error('fname'); ?>
			</div>
			<input	type='text'
					name='fname'
					placeholder='Prénom'
					value='<?php echo form_prep(set_value('fname', $p_fname)); ?>'
					required />
		</div>
		
		<div>
			<div class='error'>
				<?php echo form_error('name'); ?>
			</div>
			<input	type='text'
					name='name'
					placeholder='Nom'
					value='<?php echo form_prep(set_value('name', $p_name)); ?>'
					required />
		</div>
		
		<div>
			<div class='error'>
				<?php echo form_error('phone'); ?>
			</div>
			<input	type='tel'
					name='phone'
					placeholder='Téléphone'
					value='<?php echo form_prep(set_value('phone', $p_phone)); ?>'
					required />
		</div>
		
	<?php	
		echo form_submit('submit', 'Valider', 'data-theme="b"');
		echo form_close();
	?>
	
</div>
            
