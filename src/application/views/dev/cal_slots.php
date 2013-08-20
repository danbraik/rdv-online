
<style>
	.day {
		float:left;
		display:inline-block;
		width:14%;
		
	}
</style>


<div style='font-family:mono;'>
	<? echo $txt; ?>
</div>

<div>
	<div class='day'>
	<?	
		$old_day = 0;
		foreach($slots as $slot) {
			$day = (int)((int)($slot['slot']['id']) / 10000);
			if ($day != $old_day) {
				$old_day = $day;
				echo '</div><div class="day">';
			}
			echo '<a href="'. 
				'/dev/slots/take_rdv/'.$slot['slot']['id'].'/'. '1'
				.'">'. dispSlotCell($slot) .'</a>';
			
			echo '<br/>';;
		} ?>
		</div>
</div>
