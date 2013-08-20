<style>
	
	.ui-grid-a .ui-block-a { width: 20%; }
	.ui-grid-a .ui-block-b { width: 80%; } 
	
	.ui-grid-b .ui-block-a { width: 42%; }
	.ui-grid-b .ui-block-b { width: 40%; } 
	.ui-grid-b .ui-block-c { width: 18%;
		text-align:right;} 
	
	.ui-grid-c .ui-block-a { width: 25%; }
	.ui-grid-c .ui-block-b { width: 25%; }
	.ui-grid-c .ui-block-c { width: 25%; }
	.ui-grid-c .ui-block-d { width: 25%; }
		
	.no-slot {
		background-color:white;
	}
	.free-slot {
		background-color:#AAFFAA;
	}
	.taken-slot {
		background-color:blue;
	}
	
	table {
		width:100%;
	}
	
	table th.lbl_day {
		//font-size:0.7em;
	}
	
	table tr td.slot {
		width:14%;
		height:14px;
	}

	table tr td.hours {
		width:6%;
		text-align:right;
		font-size:.78em;
	}
</style>

<div class="ui-grid-a">
	<div class="ui-block-a" style="border-right:1px solid black;padding-top:3%;">
		
		<a  data-role="button" href="<?php echo $url_managep; ?>">
			Gestion patients
		</a>
		
		<br/>
		
		<a  data-role="button" href="<?php echo $url_planning; ?>">
			Gestion planning
		</a>
		
		
		
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		
		
		<fieldset style="border:1px dotted black">
		<?php echo form_open($url_form_post, 'data-ajax="false"'); ?>
			<div>
				<div class='error'>
					<?php echo form_error('day'); ?>
				</div>
				<div data-role="fieldcontain">
					<label for='day'>Date</label>
					<input type="date" 
							name="day"
							placeholder="Jour/Mois/Année"
							value='<?php echo form_prep(set_value('day')); ?>'
							/>
				</div>
			</div>
		<?php
			echo form_submit('submit', 'Aller au jour', 'data-icon="forward"  data-iconpos="right" data-theme="e"');
			echo form_close();
		?>
		</fieldset>
		
		<br/>
		<br/>
		
		<a  data-role="button" href="<?php echo $url_goto_today; ?>">
			Aller à aujourd'hui
		</a>
		
		<a  data-role="button" href="<?php echo $url_goto_first_free; ?>">
			Aller au premier<br/>
			créneau libre
		</a>
		
		
		<br/>
		
		<br/>
		
		<br/>
		
		
	</div>
	<!-- ********************************************************** -->
	<div class="ui-block-b">
			




		<fieldset class="ui-grid-c" >
			<div class="ui-block-a">
				<a href="<?php echo $url_prev_month;?>" data-role="button"
				data-icon="arrow-l" data-pos="left" data-mini="true" >1 mois avant</a>
			</div>
			<div class="ui-block-b">
				<a href="<?php echo $url_prev;?>" data-role="button"
				data-icon="arrow-l" data-pos="left" data-mini="true">7 jours avant</a>
			</div>
			<div class="ui-block-c">
				<a href="<?php echo $url_next;?>" data-role="button"
				data-icon="arrow-r" data-pos="right" data-mini="true">7 jours Après</a>
			</div>	 
			<div class="ui-block-d">
				<a href="<?php echo $url_next_month;?>" data-role="button"
				data-icon="arrow-r" data-pos="right" data-mini="true">1 mois Après</a>
			</div>	 			
		</fieldset>


		<?php

		echo '<table>';
		echo '<tr>';
		echo '<th><em>'.$year.'</em></th>';
		foreach ($cal_header as $head_day) {
			echo '<th class="lbl_day">';
				echo '<a style="color:black;" data-ajax="false"
					href="'.$head_day['url'].'"  target="_blank" >'.
					$head_day['date']->format('l j\<\b\r\/\>F').'</a>';
			echo '</th>';
		}
		echo '</tr>';


		foreach ($cal_content as $row_hour) {
			echo '<tr>';
			
			echo '<td class="hours">';
				$time = strval($row_hour['time']);
				$tm = substr($time, -2);
				$th = substr($time, 0, strlen($time)-2);
				
				echo $th.':'.$tm;
			echo '</td>';
			
			
			foreach($row_hour['slots'] as $slot) {
				
				if ($slot === 0)
					$type = 0;
				else if ($slot['id_patient'] !== NULL)
					$type = 1;
				else $type = 2;
				
				echo '<td' ;
					if ($type == 0)
						echo ' class="slot no-slot" ';
					else if ($type == 1)
						echo ' class="slot taken-slot" ';
					else
						echo ' class="slot free-slot" ';
					echo '>';
					
					if ($type == 2) {
						echo '<a href="'.$base_url_choose.$slot['id'].'" >';
							echo '<div style="height:100%;width:100%">';
								echo '&nbsp';
							echo '</div>';
						echo '</a>';
					}
				echo '</td>';
				
			}
			
			echo '</tr>';
		}


		echo '</table>';
	?>

	</div>	   
</div>
