
<style type="text/css">

	body {
		font-family: verdana, arial, sans-serif ;
		font-size: 12px ;
		text-align:center;
		background-color:white;
	}
 
	table {
		margin:auto;
		width:70%;
	}
 
	th, td {
		padding: 4px 4px 4px 4px ;
		text-align: center ;
	}
 
	th {
		border-bottom: 1px dotted #999999 ;
		font-family: verdana, arial, sans-serif ;
		font-size: 12px ;
		font-weight:normal;
	}
	 
	td {
		//border-bottom: 1px dotted #999999 ;
	}

</style>

<p>
	<?php 
		if (count($slots) > 0)
			echo $day->format('l j F Y');
	?>
</p>

<p>

	<?php
		if ($no_patient)
			echo 'Pas de patients.';
		
	?>
</p>

<table>
	<thead>
		<tr>
			<th>Heure</th>
			<th>Prénom</th>
			<th>Nom</th>
			<th>Téléphone</th>
		</tr>
	</thead>
	<tbody>
<?php

	
	
	foreach($slots as $s) {
		echo '<tr>';
			echo '<td>';
				echo $s['date']->format('H:i');
			echo '</td>';
			echo '<td>';
				echo $s['p.fname'];
			echo '</td>';
			echo '<td>';
				echo $s['p.name'];
			echo '</td>';
			echo '<td>';
				echo $s['p.phone'];
			echo '</td>';

		
		echo '</tr>';
	}
	
	
?>
	</tbody>
</table>


