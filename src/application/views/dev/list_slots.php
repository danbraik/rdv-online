
<div style='font-family:mono;'>
	<? echo $txt; ?>
</div>

<div>
	<p>
	<?	
		$old_day = 0;
		foreach($slots as $slot) {
			$day = (int)((int)($slot['slot']['id']) / 10000);
			if ($day != $old_day) {
				$old_day = $day;
				echo '</p><p>';
			}
			echo '<a href="'. 
				'/dev/slots/take_rdv/'.$slot['slot']['id'].'/'. '1'
				.'">'.substr($slot['slot']['datetm_start'],11).'</a>';
			if (isset($slot['patient'])) {
				echo ' ';
				displayPatientOnLine($slot['patient']);
			}
			echo '<br/>';;
		} ?>
		</p>
</div>
