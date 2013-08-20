


<p>Indiquez la date d'un jour.
Puis cliquez sur les créneaux à supprimer.
</p>

<div>
	<?php if (isset($url_prev_day) && isset($url_next_day))
		echo '<a href="'.$url_prev_day.'">un jour avant</a>
			  <a href="'.$url_next_day.'">un jour Après</a>';
	?>
</div>


<style>
	.error {
		color:red;
	}
	.msg {
		color:green;
	}
	/* Mise en forme simple pour les tableaux */
table {
  margin: 0;
  border: 1px solid gray;
  border-collapse: collapse;
  border-spacing: 0;
}
table td, table th {
  padding: 4px;
  border: 1px solid #ccc;
  vertical-align: top;
}
</style>

<?php echo form_open($url_form_post, 'data-ajax="false"'); ?>
	<div class='msg'>
		<?php echo $msg; ?>
	</div>
	<div class='error'>
		<?php echo $error; ?>
	</div>
	
	<div>
		<div class='error'>
			<?php echo form_error('day'); ?>
		</div>
		<div data-role="fieldcontain">
			<label for='day'>Date</label>
			<input type="date" 
					name="day"
					placeholder="Jour/Mois/Année"
					value='<?php echo form_prep(set_value('day', $val_day)); ?>'
					/>
		</div>
	</div>
<?php
	echo form_submit('submit', 'Valider', 'data-inline="true" data-theme="b"');
	echo form_close();
?>

<p class="separator"></p>


<table>
<?php

	if (count($slots) == 0)
		echo '<p>Pas de créneaux.</p>';

	foreach($slots as $s) {
		echo '<tr>';
			echo '<td>';
				echo '<a href="'.$base_url_rm.$s['id'].$basep_url_rm.'">Supprimer</a>';
			echo '</td>';
			echo '<td>';
				echo $s['date']->format('l j F Y');
			echo '</td>';
			echo '<td>';
				echo $s['date']->format('H:i');
			echo '</td>';
			echo '<td>';
				echo $s['p.fname'];
			echo '</td>';
			echo '<td>';
				echo $s['p.name'];
			echo '</td>';
			echo '<td>';
				echo $s['p.phone'];
			echo '</td>';

		
		echo '</tr>';
	}
?>
</table>


