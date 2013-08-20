

<style>
	.vertical-centered {
		padding-top: 7%;
	}
	
	
	.ui-grid-b .ui-block-a { width: 42%; }
	.ui-grid-b .ui-block-b { width: 40%; } 
	.ui-grid-b .ui-block-c { width: 18%;
		text-align:right;} 
</style>

<br/>

<a  data-role="button"
	data-icon="plus" 
	data-iconpos="right"
	data-theme="e"	
	href="<?php echo $link_take_slot; ?>">
	Prendre rendez-vous
</a>

<br/>

<div data-role="controlgroup" >
	<a href="<?php echo $link_manage_patients; ?>" data-role="button" data-icon="gear">Gérer les membres</a>
	<a href="<?php echo $link_manage_account; ?>" data-role="button" data-icon="gear">Gérer le compte</a>
</div>


<?php
if (count($next_slots) == 0):
?>

	<p><em>Pas de rendez-vous prochainement.</em></p>

<?php
else:
?>

	<ul data-role="listview" 
		data-divider-theme="b" data-inset="true"
		class="ui-listview ui-listview-inset ui-corner-all">


		<li data-role="list-divider" 
			role="heading"
			class="ui-li ui-li-divider ui-btn ui-bar-b ui-corner-top 
					ui-btn-down-undefined ui-btn-up-undefined">
			Prochains rendez vous
		</li>


<?php 

		foreach ($next_slots as $slot_item): ?>           
			
			<li data-theme="c"
				class="ui-li ui-li-static">
				<div class="ui-grid-b">
					
					<div class="ui-block-a">
						<div class="vertical-centered">
							<?php echo $slot_item['fname'] ?>
							<?php echo $slot_item['name'] ?>
						</div>
					</div>

					<div class="ui-block-b">
						<div class="vertical-centered">
							<?php 
								echo $slot_item['start']
										->format('d F H\hi');
							?>
						</div>
					</div>

					<div class="ui-block-c">
						<div data-role="controlgroup" >
							
							<a href='<?php echo $burl_cancel.$slot_item['id']; ?>'
								data-role="button"
								data-icon="delete"
								data-iconpos="notext" 
								data-mini="true"
								data-inline="true"></a>
						</div>
					</div>
				</div><!-- /grid-b -->
			</li>

<?php 	
		endforeach ?>
	
	</ul>
<?php endif ?>
