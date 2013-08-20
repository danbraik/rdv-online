
<style>
	.error {
		color:red;
	}
	.msg {
		color:green;
	}

</style>
	
<div class='error'>
	<?php echo $error; ?>
</div>

<p>
	Le rendez-vous du <strong><?php echo $date->format('l j F Y \à H:i'); ?></strong> pour 
	<strong>
	<?php echo $p_fname.' '.$p_name; ?>
	</strong> (<?php echo $p_phone; ?>)
	a bien été annulé.
</p>
<p>
	<div class='msg'>
		<?php echo $msg; ?>
	</div>
</p>
	


<a data-role="button" href="<?php echo $url_next; ?>">Continuer</a>


