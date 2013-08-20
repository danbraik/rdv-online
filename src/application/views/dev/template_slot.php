<?php
	function dispSlotCell($slot) {
	?>
	
<div class='slot-cell'>
	<span> 
		<? echo substr(substr($slot['slot']['datetm_start'],11),0,5); ?>
	</span> 
		<br/>
	<span>
		<? dispPatientOnCell($slot['patient']) ?>
	</span>
</div>
		
	
		
<?php
	}
