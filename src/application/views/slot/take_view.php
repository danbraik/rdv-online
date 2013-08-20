
<p>
	<?php if ($taken) : 
		$btn_lbl = 'Autre rendez vous le même jour'; ?>
		Le rendez-vous du
</p>
		<div style="text-align:center;vertical-align:text-top;">
			<strong>
				<?php
					echo $s_start->format('j F Y \à H:i');
				?>
			</strong>
		</div>
<p>	
		pour 
		<strong>
			<?php echo $p_fname.' '.$p_name; ?>
		</strong>
		
		a bien été enregistré.
		<br/>
		Un seul rendez-vous peut être en cours pour un membre.
		
		Vous pouvez toutefois prendre un autre rendez-vous pour un 
		autre membre de la famille.
	<?php else : 
		$btn_lbl = 'Choisir un nouvel horaire';?>
		<strong>Ouuups !</strong><br/>
		Le rendez-vous vient tout juste d'être pris par une
		autre personne !
		Consultez les autres créneaux disponibles ;)
	<?php endif; ?>
</p>


<a href="<?php echo $url_prev_day; ?>" data-role="button" data-ajax="false">
	<?php echo $btn_lbl; ?>
</a>

<!--
<a href="<?php echo $url_end; ?>" data-role="button" data-ajax="false">
	Aller au tableau de bord
</a>
-->
