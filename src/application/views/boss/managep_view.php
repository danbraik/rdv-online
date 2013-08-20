<a data-role="button" href="<?php echo $url_back; ?>">Retour au panneau d'admin</a>




<style>
	.error {
		color:red;
	}
	
	/* Mise en forme simple pour les tableaux */
	table {
	  margin: 0;
	  border: 1px solid gray;
	  border-collapse: collapse;
	  border-spacing: 0;
	  width:100%;
	}
	table td, table th {
	  padding: 4px;
	  border: 1px solid #ccc;
	  vertical-align: top;
	}
	
	td.date {
		width:22%;
	}
	td.url_modify {
		width:2%;
	}
	td.url_rm {
		width:7%;
	}
</style>




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
	echo form_submit('submit', 'Rechercher', ' data-inline="true" data-theme="b"');
	echo form_close();
?>



<table>
	<tr>
		<th>Prénom</th>
		<th>Nom</th>
		<th>Téléphone</th>
		<th>Mail</th>
		<th>Prochain Rdv</th>
		<th>Patient</th>
		<th>Rdv</th>
	</tr>
	
	<?php

		foreach($members as $patient) {
			echo '<tr>';
						
				echo '<td>'.$patient['fname'].'</td>';
				echo '<td>'.$patient['name'].'</td>';
				echo '<td>'.$patient['phone'].'</td>';
				echo '<td>'.$patient['account']['mail'].'</td>';
				
				if (isset($patient['next']['date']))
					echo '<td class="date">'.$patient['next']['date']->format('l j F Y \à H:i').'</td>';
				else
					echo '<td class="date"></td>';
				
				echo '<td class="url_modify"><a href="'.$base_url_modify_p.$patient['id'].'">Modifier</a></td>';
				
				if (isset($patient['next']['id']))
					echo '<td class="url_rm"><a href="'.$base_url_rm_s.$patient['next']['id'].'">Supp Rdv</a></td>';
				else
					echo '<td class="url_rm"></td>';
						
			echo '</tr>';
			
			
		}
	?>

</table>



