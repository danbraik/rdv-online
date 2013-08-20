
<p>
	<?php if ($taken) : 
		$button_lbl = 'Prendre un autre rendez vous le même jour'; ?>
		Le rendez-vous du
		<strong>
		<?php
			echo $s_start->format('j F Y \à H:i');
		?>
		</strong>
		pour 
		<strong>
		<?php echo $p_fname.' '.$p_name; ?>
		</strong>
		a bien été enregistré.
		
	<?php else : 
		$button_lbl = 'Choisir un autre horaire'; ?>
		<strong>Ouuups !</strong><br/>
		Le rendez-vous vient tout juste d'être pris par une
		autre personne !
		Consultez les autres créneaux disponibles ;)
	<?php endif; ?>
</p>


<a href="<?php echo $url_prev_day; ?>" data-role="button" data-ajax="false">
	<?php echo $button_lbl; ?>
</a>

<a href="<?php echo $url_end; ?>" data-role="button" data-ajax="false">
	Retour au tableau d'administration
</a>
