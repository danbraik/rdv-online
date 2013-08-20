<?php
	function displayPatientOnLine($patient) {
		if (!isset($patient))
			return;
		echo '<strong>'.$patient['name'].' '.$patient['fname'].'</strong> <em>'.$patient['phone'].'</em>';
	}
	
	function dispPatientOnCell($patient) {
		if (!isset($patient))
			return;
		echo '<strong>'.$patient['name'].' '.$patient['fname'].'</strong>';
	}
