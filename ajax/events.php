<?php
require(getenv("DOCUMENT_ROOT")."/functions/database.php");
function rc($minVal = 0, $maxVal = 255)
{
	
    // Make sure the parameters will result in valid colours
    $minVal = $minVal < 0 || $minVal > 255 ? 0 : $minVal;
    $maxVal = $maxVal < 0 || $maxVal > 255 ? 255 : $maxVal;
	
    // Generate 3 values
    $r = mt_rand($minVal, $maxVal);
    $g = mt_rand($minVal, $maxVal);
    $b = mt_rand($minVal, $maxVal);
	
    // Return a hex colour ID string
    return sprintf('#%02X%02X%02X', $r, $g, $b);
	
}	
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
	$color = rc(0,125);
	$color1 = rc(125,250);
	$count = "0";
	$factuur = "0";
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
			$count = $stmt2->RowCount();
			if ($count >=1)
			{
				$color2 = $color1;
				$ingevuld = "1"; 
			}
			else
			{
				$color2 = $color;
				$ingevuld = "0";				
			}
		}//end try
		catch(Exception $e) {
			echo '<h2><font color=red>';
			var_dump($e->getMessage());
			die ('</h2></font> ');
		}		
		//end Loop
		$table = "
		<div class=\"alert alert-info\">
		<div class=\"row\">
		<div class=\"col-sm-6\">OVerzicht van ".$info['van']." tot ".$info['tot']."</div>
		<div class=\"col-sm-6 text-right\">".$info['uren']." uren</div>
		</div>
		</div>
		<table border=1 id='hoofd' class=\"table table-striped table-bordered table-hover\">
		<thead>
		<tr>	
		<td>van</td>
		<td>Tot</td>
		<td>Klant</td>
		<td>Project</td>
		<td>uren</td>
		<td>info</td>
		<td>Factuur</td>
		</tr>
		</thead>
		<tbody>";
		foreach($result2 as $info2) {
			try{
				$count += $info2['uren'];
				$factuur += ($info2['factuur'] == "y")?$info2['uren']:"0";
				$stmt3 = $db->prepare("SELECT * FROM
				klanten WHERE id = :klant ORDER BY id ASC
				");
				$stmt3->execute(array( 
				':klant' => $info['kid'],
				));
				$klant = $stmt3->fetch(PDO::FETCH_ASSOC);
				$stmt4 = $db->prepare("SELECT * FROM
				projecten WHERE id = :project ORDER BY id ASC
				");
				$stmt4->execute(array( 
				':project' => $info['pid'],
				));
				$project = $stmt4->fetch(PDO::FETCH_ASSOC);
			}//end try
			catch(Exception $e) {
				echo '<h2><font color=red>';
				var_dump($e->getMessage());
				die ('</h2></font> ');
			}		
				
			$table .= "<tr>";
			$table .=  "<td class=warning >$info2[van]</td>";
			$table .=  "<td class=warning >$info2[tot]</td>";
			$table .=  "<td class=success>$klant[naam]</td>";
			$table .=  "<td class=success>$project[naam]</td>";
			$table .=  "<td class=danger>$info2[uren]</td>";
			$table .=  "<td class=info>$info2[info]</td>";
			$table .=  "<td class=danger >$info2[factuur]</td>";
			$table .=  "</tr>";
		}
		$table .= "<tr><td colspan=\"3\"></td>
		<td>Totaal Aantal Uren:</td>
		<td class='alert alert-info'>$count</td>
		<td>Te Facturen uren:</td>
		<td class='alert alert-danger'>$factuur</td></tr>";
		$table .=  "</tbody></table>";				
					
		$out[] = array(
        'start' => $info['datum']."T".$info['van'],
		'end' => $info['datum']."T".$info['tot'],
		'datum' => "overzicht van ".$info['datum'],
        'title' => $info['info'],
        'link' => "../details/$info[id]",
		'description' => $table,
		'color' => $color2,
		'volledig' => $ingevuld,
		'kleur' => $color2,
		);	
	}; //loop
	
	// output to the browser
	echo json_encode($out);	
}
	?>