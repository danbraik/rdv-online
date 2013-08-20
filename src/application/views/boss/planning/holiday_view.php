<a data-role="button" href="<?php echo $url_back; ?>">Retour</a>




<p>Tous les rendez-vous et créneaux entre les deux dates indiquées
seront supprimés.
Un mail sera envoyé aux patients dont le rendez-vous est annulé.
</p>







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
	
	<div>
		<div class='error'>
			<?php echo form_error('start'); ?>
		</div>
		<div data-role="fieldcontain">
			<label for='start'>Début plage</label>
			<input type="date" 
					name="start"
					placeholder="Jour/Mois/Année"
					value='<?php echo form_prep(set_value('start')); ?>'
					/>
		</div>
		
		
		
		
		<div class='error'>
			<?php echo form_error('end'); ?>
		</div>
		<div data-role="fieldcontain">
			<label for='end'>Fin plage</label>
			<input type="date" 
					name="end"
					placeholder="Jour/Mois/Année"
					value='<?php echo form_prep(set_value('end')); ?>'
					/>
		</div>
		
		
	</div>
<?php
	echo form_submit('submit', 'Valider', 'data-theme="b"');
	echo form_close();
?>



<?php

	if (isset($out)) {
		echo '<h2>Rendez-vous automatiquement annulés.</h2>';
		
		echo $out;
		
	}
