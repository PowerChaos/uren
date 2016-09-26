<?php
require(getenv("DOCUMENT_ROOT")."/functions/database.php");	
if ($_POST['id'] >='1')
{
	$id = $_POST['id'];
	$start = $_POST['start'];
	$end = $_POST['end'];
	// parameters from URL
	try{	
		$stmt = $db->prepare("SELECT * FROM
		aanwezig WHERE (datum BETWEEN :start AND :end) AND uid = :id ORDER BY datum ASC
		");
		$stmt->execute(array( 
		':id' => $id,
		':start' => $start,
		':end' => $end,
		));
		$result = $stmt->fetchall(PDO::FETCH_ASSOC);
	}//end try
	catch(Exception $e) {
		echo '<h2><font color=red>';
		var_dump($e->getMessage());
		die ('</h2></font> ');
	}
	$out = array();

	foreach($result as $info)
	{
		try{	
			$stmt2 = $db->prepare("SELECT * FROM
			details WHERE datum = :datum AND awid = :id ORDER BY datum ASC
			");
			$stmt2->execute(array( 
			':id' => $info['id'],
			':datum' => $info['datum'],
			));
			$result2 = $stmt2->fetchall(PDO::FETCH_ASSOC);
		}//end try
		catch(Exception $e) {
			echo '<h2><font color=red>';
			var_dump($e->getMessage());
			die ('</h2></font> ');
		}	
		//end Loop
		$table = "
		<div class=\"alert alert-info\">
		Uren OverZicht
		</div>
		<table border=1 id='hoofd' class=\"table table-striped table-bordered table-hover\">
		<thead>
		<tr>	
		<td>van</td>
		<td>Tot</td>
		<td>KID</td>
		<td>PID</td>
		<td>uren</td>
		<td>info</td>
		<td>Factuur</td>
		</tr>
		</thead>
		<tbody>";
		foreach($result2 as $info2) {
			$table .= "<tr>";
			$table .=  "<td class=warning >$info2[van]</td>";
			$table .=  "<td class=warning >$info2[tot]</td>";
			$table .=  "<td class=success>$info2[kid]</td>";
			$table .=  "<td class=success>$info2[pid]</td>";
			$table .=  "<td class=danger>$info2[uren]</td>";
			$table .=  "<td class=info>$info2[info]</td>";
			$table .=  "<td class=danger >$info2[factuur]</td>";
			$table .=  "</tr>";
		}
		$table .=  "</tbody></table>";				
			
			
		$out[] = array(
        'start' => $info['datum'],
		'datum' => $info['datum'],
        'title' => $info['info'],
        'link' => "../ajax/details.php?id=$info[id]",
		'description' => $table,
		//loop
		);	
	};
	
	// output to the browser
	echo json_encode($out);	
}
	?>