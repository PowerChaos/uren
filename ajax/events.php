<?php
require(getenv("DOCUMENT_ROOT")."/functions/database.php");
require(getenv("DOCUMENT_ROOT")."/functions/array.php");	
if ($_POST['id'] >='1')
{
	$id = $_POST['id'];
	$start = $_POST['start'];
	$end = $_POST['end'];
	$groep = $_POST['groep'];
	$color = "#D05860"; // Niet volledig //rc(0,125);
	$color1 = "#008000";  // Volledig//rc(125,250);
	$fcolor = "#A0B0E0";
	$out = array();
	try{
		$stmt = $db->prepare("SELECT * FROM groep WHERE id=:groep");
		$stmt->execute(array(':groep' => $groep,));
		$result = $stmt->fetchall(PDO::FETCH_ASSOC);
		$grcount = $stmt->rowCount();
		if ($grcount > "0" )
		{
			foreach($result as $info) {
				$str = arr($info['user']);
				sort($str);
				$tel = count($str);
				if (!empty($str))
				{
					for($i=0;$i < $tel;$i++){
						$value = $str[$i];
						$stmtg = $db->prepare("SELECT * FROM gebruikers where id=:gebruiker");
						$stmtg->execute(array(':gebruiker' => $value,));
						$resultg = $stmtg->fetch(PDO::FETCH_ASSOC);
						$stmt1 = $db->prepare("SELECT * FROM
						aanwezig WHERE (datum BETWEEN :start AND :end) AND uid = :id ORDER BY datum ASC
						");
						$naam = $resultg['naam'];
						$stmt1->execute(array( 
						':id' => $resultg['id'],
						':start' => $start,
						':end' => $end,
						));
						$result2 = $stmt1->fetchall(PDO::FETCH_ASSOC);
						foreach($result2 as $info)
						{
								
							try{	
								$stmt2 = $db->prepare("SELECT * FROM
								details WHERE datum = :datum AND awid = :id ORDER BY datum ASC
								");
								$stmt2->execute(array( 
								':id' => $info['id'],
								':datum' => $info['datum'],
								));
								$result3 = $stmt2->fetchall(PDO::FETCH_ASSOC);
								$detcount = $stmt2->RowCount();
								if ($info['gefactureerd'] == "y")
								{
									$color2 = $fcolor;
									
								}	
								elseif ($detcount >=1)
								{
									$color2 = $color1; 
								}
								else
								{
									$color2 = $color;				
								}
							}//end try
							catch(Exception $e) {
								echo '<h2><font color=red>';
								var_dump($e->getMessage());
								die ('</h2></font> ');
							}
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
							$totcount = "0";
							$factuur = "0";	
							foreach($result3 as $info2) {
								try{
									$totcount += $info2['uren'];
									$factuur += ($info2['factuur'] == "y")?$info2['uren']:"0";
									$stmt3 = $db->prepare("SELECT * FROM
									klanten WHERE id = :klant ORDER BY id ASC
									");
									$stmt3->execute(array( 
									':klant' => $info2['kid'],
									));
									$klant = $stmt3->fetch(PDO::FETCH_ASSOC);
									$stmt4 = $db->prepare("SELECT * FROM
									projecten WHERE id = :project ORDER BY id ASC
									");
									$stmt4->execute(array( 
									':project' => $info2['pid'],
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
							<td class='alert alert-info'>$totcount</td>
							<td>Te Facturen uren:</td>
							<td class='alert alert-danger'>$factuur</td></tr>";
							$table .=  "</tbody></table>";				
							
							$out[] = array(
							'start' => $info['datum']."T".$info['van'],
							'end' => $info['datum']."T".$info['tot'],
							'datum' => "overzicht van ".$info['datum'],
							'title' => $naam." van ".$info['van'] ." tot ". $info['tot'],
							'link' => "../details/$info[id]",
							'description' => $table,
							'color' => $color2,
							);
										
					}	
				}
			}
		}
		} // Geen Groep
		else
		{			
			$stmt = $db->prepare("SELECT * FROM gebruikers where id=:gebruiker");
			$stmt->execute(array(':gebruiker' => $id,));
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$naam = $result['naam'];
			$stmt1 = $db->prepare("SELECT * FROM
			aanwezig WHERE (datum BETWEEN :start AND :end) AND uid = :id ORDER BY datum ASC
			");
			$stmt1->execute(array( 
			':id' => $result['id'],
			':start' => $start,
			':end' => $end,
			));
			$result2 = $stmt1->fetchall(PDO::FETCH_ASSOC);
			foreach($result2 as $info)
			{
				try{	
					$stmt2 = $db->prepare("SELECT * FROM
					details WHERE datum = :datum AND awid = :id ORDER BY datum ASC
					");
					$stmt2->execute(array( 
					':id' => $info['id'],
					':datum' => $info['datum'],
					));
					$result3 = $stmt2->fetchall(PDO::FETCH_ASSOC);
					$detcount = $stmt2->RowCount();
					if ($info['gefactureerd'] == "y")
					{
						$color2 = $fcolor;
						
					}	
					elseif ($detcount >=1)
					{
						$color2 = $color1; 
					}
					else
					{
						$color2 = $color;				
					}
				}//end try
				catch(Exception $e) {
					echo '<h2><font color=red>';
					var_dump($e->getMessage());
					die ('</h2></font> ');
				}
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
				$totcount = "0";
				$factuur = "0";	
				foreach($result3 as $info2) {
					try{
						$totcount += $info2['uren'];
						$factuur += ($info2['factuur'] == "y")?$info2['uren']:"0";
						$stmt3 = $db->prepare("SELECT * FROM
						klanten WHERE id = :klant ORDER BY id ASC
						");
						$stmt3->execute(array( 
						':klant' => $info2['kid'],
						));
						$klant = $stmt3->fetch(PDO::FETCH_ASSOC);
						$stmt4 = $db->prepare("SELECT * FROM
						projecten WHERE id = :project ORDER BY id ASC
						");
						$stmt4->execute(array( 
						':project' => $info2['pid'],
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
				<td class='alert alert-info'>$totcount</td>
				<td>Te Facturen uren:</td>
				<td class='alert alert-danger'>$factuur</td></tr>";
				$table .=  "</tbody></table>";				
				
				$out[] = array(
				'start' => $info['datum']."T".$info['van'],
				'end' => $info['datum']."T".$info['tot'],
				'datum' => "overzicht van ".$info['datum'],
				'title' => $naam." van ".$info['van'] ." tot ". $info['tot'],
				'link' => "../details/$info[id]",
				'description' => $table,
				'color' => $color2,
				);
		}	
	}//end else			
} //end try
	catch(Exception $e) {
		echo '<h2><font color=red>';
		var_dump($e->getMessage());
		die ('</h2></font> ');
	}
	// output to the browser
	echo json_encode($out);			
}
	?>