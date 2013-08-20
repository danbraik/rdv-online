<?php



	$am = array();
	$am_hours = array();
	$am_max = 0;
	
	$pm = array();
	$pm_hours = array();
	$pm_max = 0;

	// separate am and pm slots
	foreach($slots as $slot) {
		$hour = intval((intval($slot['id']) % 10000) / 100);
		//$minute = intval($slot['id']) % 100;
		
		if ($hour <= 12) {
			if (!isset($am[$hour])) {
				$am[$hour] = array();
				$am_hours[$hour] = 0;
			}
			array_push($am[$hour], $slot);
			$am_hours[$hour]++;
			
			if ($am_max < $am_hours[$hour])
				$am_max = $am_hours[$hour];
			
			
		} else { // ************
			
			
			if (!isset($pm[$hour])) {
				$pm[$hour] = array();
				$pm_hours[$hour] = 0;
			}
			array_push($pm[$hour], $slot);
			$pm_hours[$hour]++;
			
			if ($pm_max < $pm_hours[$hour])
				$pm_max = $pm_hours[$hour];
			
		}
	}
?>


<style>
	 table {
		 width:100%;
		 text-align:center;
	 }
	 
	 table tr td {
		 height:2em;
	 }
</style>


<h2>
	<?php echo 'Horaires du <br/>'.$day->format('j F Y'); ?>
</h2>

<div data-role="collapsible-set" data-theme="b" data-content-theme="d">
            <div data-role="collapsible" 
					<?php if ($am_max > 0) echo 'data-collapsed="false"'; ?>
            >
                <h3>
                    Matin
                </h3>

<?php
	echo '<table>';
	for($i=0;$i < $am_max; ++$i) {
		echo '<tr>';
		foreach(array_keys($am_hours) as $hour) {
			echo '<td>';
			if ($am_hours[$hour] > $i) {
				//echo $i;
				$slot = $am[$hour][$i];
				echo anchor($b_url.$slot['id'], date_format(date_create($slot['datetm_start']), 'H:i'));
			}
			echo '</td>';
		}
		echo '</tr>';
	}
	echo '</table>';

?>



                
                
                
            </div>
            <div data-role="collapsible" 
				 <?php if ($am_max == 0) echo 'data-collapsed="false"'; ?>
			>
                <h3>
                    Après-midi
                </h3>
<?php
	echo '<table style="width:100%;text-align:center;">';
	for($i=0;$i < $pm_max; ++$i) {
		echo '<tr>';
		foreach(array_keys($pm_hours) as $hour) {
			echo '<td>';
			if ($pm_hours[$hour] > $i) {
				//echo $i;
				$slot = $pm[$hour][$i];
				echo anchor($b_url.$slot['id'], date_format(date_create($slot['datetm_start']), 'H:i'));
			}
			echo '</td>';
		}
		echo '</tr>';
	}
	echo '</table>';

?>        
            </div>
        </div>
