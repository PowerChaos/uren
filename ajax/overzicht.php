<?php
echo "<h1>".$_SESSION[ERROR]."</h1>";
$_SESSION[ERROR] ="";
	if ($_POST['details'])
	{
		// parameters from URL
	$details = $_POST['details'];
	require(getenv("DOCUMENT_ROOT")."/functions/database.php");
	try{
		$stmt = $db->prepare("SELECT * FROM details WHERE awid=:details");
		$stmt->execute(array(':details' => $details,));
		$result = $stmt->fetchall(PDO::FETCH_ASSOC);
		$stmt3 = $db->prepare("SELECT * FROM aanwezig Where id = :naam");
		$stmt3->execute(array(':naam' => $details,));
		$result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
		$stmt2 = $db->prepare("SELECT * FROM gebruikers Where id = :naam");
		$stmt2->execute(array(':naam' => $result3['uid'],));
		$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
		$fac = $result3['gefactureerd'];
		$print = ($fac != "y")?"<a href=\"../details/$details\" class=\"btn btn-success btn-block btn-lg\"><i class='material-icons'>border_color</i></a>":"Al Gefactureerd";
		$count = "0";
		$factuur = "0";
		$total = "0";
		}//end try
		catch(Exception $e) {
			echo '<h2><font color=red>';
			var_dump($e->getMessage());
			die ('</h2></font> ');
		}
			$table = "
			<div class=\"alert alert-info\">
			<div class=\"row\">
			<div class=\"col-sm-4\">OVerzicht van ".$result3['van']." tot ".$result3['tot']." op ".$result3['datum']."</div>
			<div class=\"col-sm-4 text-center\">$print</div>
			<div class=\"col-sm-4 text-right\">".$result3['uren']." uren</div>
			</div>
			</div>
			<div id='print'>
			<table border=1 id='hoofd' name='hoofd' class=\"table table-striped table-bordered table-hover\">
			<thead>
			<tr>	
			<td>van</td>
			<td>Tot</td>
			<td>Klant</td>
			<td>Project</td>
			<td>uren</td>
			<td>info</td>
			<td>Factuur</td>
			";
			$table .= "</tr>
			</thead>
			<tbody>";
			foreach($result as $info) {
				try{
					$total++;	
					$count += $info['uren'];
					$factuur += ($info['factuur'] == "y")?$info['uren']:"0";
	
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
				$table .=  "<td class=warning >$info[van]</td>";
				$table .=  "<td class=warning >$info[tot]</td>";
				$table .=  "<td class=success>$klant[naam]</td>";
				$table .=  "<td class=success>$project[naam]</td>";
				$table .=  "<td class=danger>$info[uren]</td>";
				$table .=  "<td class=info>$info[info]</td>";
				$table .=  "<td class=danger >$info[factuur]</td>";
				$table .=  "</tr>";
			}
		$table .= "<tr><td colspan=\"3\"></td>
		<td>Totaal Aantal Uren:</td>
		<td class='alert alert-info'>$count</td>
		<td>Te Facturen uren:</td>
		<td class='alert alert-danger' colspan=\"2\">$factuur</td>
			<tr>";
			$table .=  "</tbody></table></div>";
			echo $table;
if ($fac == 'y'){
		echo "<div class='alert alert-block alert-danger text-center'>Factuur is al aangemaakt , geen bewerking meer mogelijk</div>";
}
}//end try
else
{
		echo"aanwezigheid ID Bestaan niet";
}
?>	