
<h2>
	<?php echo 'Rendez-vous du <br/>'.$date->format('j F Y \à H\hi'); ?>
</h2>
<p>Créez ou choisissez le membre pour lequel vous prenez ce rendez-vous.</p>

<style>
	.error {
		color:red;
	}
</style>


<div data-role="collapsible-set" data-theme="b" data-content-theme="d">
	<div data-role="collapsible">
		<h3>
			Nouveau membre
		</h3>
		<div>
			<?php echo form_open($form_post_url); ?>
				<div class='error'>
					<?php echo $create_error; ?>
				</div>
				<div>
					<div class='error'>
						<?php echo form_error('fname'); ?>
					</div>
					<div data-role="fieldcontain">
			<label class="label" for='fname'>Prénom</label><br/>
					<input	type='text'
							name='fname'
							autocomplete='on'
							value='<?php echo form_prep(set_value('fname')); ?>'
							required/>
					</div>
				</div>
				
				<div>
					<div class='error'>
						<?php echo form_error('name'); ?>
					</div>
					<div data-role="fieldcontain">
			<label class="label" for='name'>Nom</label><br/>
					<input	type='text'
							name='name'
							autocomplete='on'
							value='<?php echo form_prep(set_value('name', $name)); ?>'
							required/>
					</div>
				</div>
				
				<div>
					<div class='error'>
						<?php echo form_error('phone'); ?>
					</div>
					<div data-role="fieldcontain">
					<label class="label" for='phone'>Téléphone</label><br/>
						<input	type='tel'
								name='phone'
								autocomplete='on'
								value='<?php echo form_prep(set_value('phone', $phone)); ?>'
								required />
					</div>
				</div>
				
			<?php	
				echo form_submit('submit', 'Valider', 'data-theme="b"');
				echo form_close();
			?>
				
		</div>
	</div>
	<div data-role="collapsible" data-collapsed="false">
		<h3>
			Membres enregistrés
		</h3>
		<div>
			<ul data-role="listview" data-divider-theme="b" data-inset="true">	
				<?php
					foreach($members as $patient) {
						
						echo '<li ' ;
						echo ($patient['enabled']) ? '' : 'class="ui-disabled"';
						echo    ' data-theme="c">'.
								'<a href="'.$post_url.$patient['id'].'">'.
								$patient['fname'].' '.$patient['name'].
							'</a></li>';
					}
				?>
			</ul>
		</div>
	</div>
</div>


	
	
		
	





