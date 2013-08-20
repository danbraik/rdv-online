


<ul data-role="listview" data-divider-theme="b" data-inset="true">
            <li data-role="list-divider" role="heading">
                Nouveau patient
            </li>
            <li data-theme="c">
                


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
							autocomplete='on'
							placeholder='Prénom'
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
							required />
				</div>
				
			<?php	
				echo form_submit('submit', 'Valider', 'data-theme="b"');
				echo form_close();
			?>
				
		</div>



            </li>
            <li data-role="list-divider" role="heading">
                Patient existant
            </li>
            <li data-theme="c">
                <div>


<?php echo form_open($url_form_search, 'data-ajax="false"'); ?>
	<div class='error'>
		<?php echo $error; ?>
	</div>
	
	<div>
		<div class='error'>
			<?php echo form_error('name'); ?>
		</div>
		<div data-role="fieldcontain">
			<label for='name'>Nom</label>
			<input	type='search'
					name='name'
					autocomplete='on'
					value='<?php echo form_prep(set_value('name')); ?>'
					required
					/>
		</div>
	</div>
<?php
	echo form_submit('submit', 'Rechercher', ' data-theme="b"');
	echo form_close();
?>




<ul data-role="listview" data-divider-theme="b" data-inset="true">
	<?php

		foreach($members as $patient) {
			echo '<li data-theme="c">
					<a href="'.$base_url_take.$patient['id'].'">';
						
				echo $patient['fname'].' '.$patient['name'].' ';
				echo '('.$patient['account']['mail'].')';
						
			echo '</a>
				</li>';
			
			
		}
	?>
</ul>



</div>
            </li>
        </ul>
