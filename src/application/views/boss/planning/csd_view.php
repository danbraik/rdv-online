

<p class="synopsis">
	
		Entrez la date du jour
		et la plage horaire
		pour créer des créneaux horaires.
	
</p>







<style>
	.error {
		color:red;
	}
	.msg {
		color:green;
		font-size:2em;
	}
	/* Mise en forme simple pour les tableaux */
table {
  margin: 0;
  border: 1px solid gray; /* Pas de bordure = "none". */
  border-collapse: collapse; /* Valeur par défaut: "separate". */
  border-spacing: 0;
}
table td, table th {
  padding: 4px; /* Pas de retrait autour du texte = "0". */
  border: 1px solid #ccc; /* Pas de bordure = "none". */
  vertical-align: top; /* Valeur par défaut: "middle" */
}
</style>

<?php echo form_open($url_form_post, 'data-ajax="false"'); ?>
	<div class='msg'>
		<?php echo $msg; ?>
	</div>
	<div class='error'>
		<?php echo $error; ?>
	</div>
	<br/>
	
	<div>
		<div class='error'>
			<?php echo form_error('day'); ?>
		</div>
		<div data-role="fieldcontain">
			<label for='day'>Jour concerné (Jour/Mois/Année, ex: 25/12/2013)</label><br/>
			<input type="date" 
					name="day"
					placeholder="Jour/Mois/Année"
					value='<?php echo form_prep(set_value('day')); ?>'
					/>
		</div>
		
		
		
		<div class='error'>
			<?php echo form_error('begin'); ?>
		</div>
		<div data-role="fieldcontain">
			<label for='begin'>Début plage (Heures:Minutes, ex: 15:30)</label>
			<input type="time" 
					name="begin"
					placeholder="Heures:Minutes"
					value='<?php echo form_prep(set_value('begin')); ?>'
					/>
		</div>
		
		
		<p>La fin de la plage marque la fin du dernier rendez-vous.
			Par exemple si le dernier rendez-vous est à 18:30, alors il faut écrire 18:45.
			(dans le cas où un rendez-vous dure 15 minutes)</p>
		<div class='error'>
			<?php echo form_error('end'); ?>
		</div>
		<div data-role="fieldcontain">
			<label for='end'>Fin plage (Heures:Minutes, ex: 16:15)</label>
			<input type="time" 
					name="end"
					placeholder="Heures:Minutes"
					value='<?php echo form_prep(set_value('end')); ?>'
					/>
		</div>
		
		<div class='error'>
			<?php echo form_error('duration'); ?>
		</div>
		<div data-role="fieldcontain">
			<label for='duration'>Durée d'un rendez-vous (Minutes, ex: 15)</label>
			<input type="time" 
					name="duration"
					placeholder="Minutes"
					value='<?php echo form_prep(set_value('duration', '15')); ?>'
					/>
		</div>
		
		
	</div>
<?php
	echo form_submit('submit', 'Valider', 'data-theme="b"');
	echo form_close();
?>



