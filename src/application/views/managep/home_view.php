
<br/>
<a	href='<?php echo $url_add_patient; ?>' 
	data-role="button"
	data-icon="plus" 
	data-iconpos="right">
		Ajouter un membre
</a>
<br/>

<ul data-role="listview" 
		data-divider-theme="b" data-inset="true"
		class="ui-listview ui-listview-inset ui-corner-all">


		<li data-role="list-divider" 
			role="heading"
			class="ui-li ui-li-divider ui-btn ui-bar-b ui-corner-top 
					ui-btn-down-undefined ui-btn-up-undefined">
			Membres enregistrés
		</li>


<?php 

if (count($members) == 0) {
	echo '<li><p>Pas de membres. Veuillez en créer un.</p></li>';
} else {

		foreach ($members as $patients): ?>           
			
			<li data-theme="c"
				class="ui-li ui-li-static">
				<div class="ui-grid-a">
					
					<div class="ui-block-a" style="vertical-align:middle;">
						<div 	
								style="padding-top:6.5%;">
							<?php echo $patients['fname'] ?>
							<?php echo $patients['name'] ?>
						</div>
					</div>

					<div class="ui-block-b">
						<div data-role="controlgroup" >
							
							<a 	href='<?php echo $burl_modify.$patients['id']; ?>'
								data-role="button" 
								data-mini="true">
									Modifier
							</a>
							
							<a 	href='<?php echo $burl_remove.$patients['id']; ?>'
								data-role="button"
								data-mini="true"
								data-ajax="false">
									Supprimer
							</a>
						</div>
					</div>
				</div><!-- /grid-b -->
			</li>

<?php 	
		endforeach ;
	}
?>
	
	</ul>
