<?php
require(getenv("DOCUMENT_ROOT")."/functions/database.php");

/*
Ruimte tussen verschillende post invoer functies
*/

//verwerking Data van pass.php
if ($_POST[info] == 'pass') //pass submit
{
	$hash = NEW PasswordStorage;
	$p2 =$hash->verify_password($_POST['oldpass'],$_SESSION['hash']);
if (($p2)&& $_POST['newpass'] == $_POST['newpass2'])
{
			$changepass = $db->prepare("UPDATE gebruikers SET wachtwoord=:hash WHERE id=:uid");
			$hashpass = $hash->create_hash($_POST["newpass"]);
			$changepass->execute(array(':hash' => $hashpass,':uid' => $_SESSION[id]));
			$_SESSION[relog] = "yes";			
}
else
{
$_SESSION[ERROR] = "Wachtwoord komt niet overeen<br>oud wachtwoord: $_POST[oldpass]<br>nieuw wachtwoord: $_POST[newpass] <br>Wachtwoord Herhaling: $_POST[newpass2]";	
}
if ($_SESSION[relog] =="")
{
echo "<meta http-equiv=\"refresh\" content=\"0;URL=http://{$_SERVER['SERVER_NAME']}//pass\" />";
}
else
{
echo "<meta http-equiv=\"refresh\" content=\"0;URL=http://{$_SERVER['SERVER_NAME']}/\" />";	
}
} // einde verwerking pass.php

/*
Ruimte tussen verschillende post invoer functies
*/

 // Begin verwerking users.php
 
if ($_POST['users'] == 'rechten') //Rechten aanpassen
{
$waarde = $_POST['id'];
$data = $_POST['rechten'];
	if ($waarde > 1)
	{
	try{
$stmt = $db->prepare("UPDATE gebruikers SET rechten =:data WHERE id =:waarde ");
$stmt->execute(
array(
':waarde' => $waarde, 
':data' => $data 
 ));
	}
catch(Exception $e) {
    echo '<h2><font color=red>';
    var_dump($e->getMessage());
	die ('</h2></font> ');
}

switch ($data) {
    case "3":
        $data = "admin";
        break;
	case "2":
		$data = "staff";        
        break;
    case "b":
		$data = "Geblokeerd";
		break;
	default:
		$data = "gebruiker";
	}
$_SESSION[ERROR] = "Rechten zijn aangepast naar $data" ;
}
else
{
$_SESSION[ERROR] = "de rechten van id $waarde kan niet worden veranderd";
}

echo "<meta http-equiv=\"refresh\" content=\"0;URL=http://{$_SERVER['SERVER_NAME']}/a/gebruikers\" />";
}
 

/*
Ruimte tussen verschillende post invoer functies
*/

if ($_POST['users'] == 'hernoem') //Rechten aanpassen
{
$waarde = $_POST['id'];
$data = $_POST['naam'];
	try{
$stmt = $db->prepare("UPDATE gebruikers SET naam =:data WHERE id =:waarde ");
$stmt->execute(
array(
':waarde' => $waarde, 
':data' => $data 
 ));
	}
catch(Exception $e) {
    echo '<h2><font color=red>';
    var_dump($e->getMessage());
	die ('</h2></font> ');
}
$_SESSION[ERROR] = "naam is aangepast naar $data" ;
echo "<meta http-equiv=\"refresh\" content=\"0;URL=http://{$_SERVER['SERVER_NAME']}/a/gebruikers\" />";
}
 

/*
Ruimte tussen verschillende post invoer functies
*/

if ($_POST['users'] == 'toevoegen') //Gebruiker toevoegen
{
$hash = NEW PasswordStorage;
$data = $_POST['wachtwoord'];
$hashpass = $hash->create_hash($data);
$naam = $_POST['naam'];
	try{	
$stmt = $db->prepare("INSERT INTO gebruikers (naam,wachtwoord) VALUES (:naam,:data)");
$stmt->execute(
array(
':naam' => $naam, 
':data' => $hashpass,
 ));
	}
catch(Exception $e) {
    echo '<h2><font color=red>';
    var_dump($e->getMessage());
	die ('</h2></font> ');
}
$_SESSION[ERROR] = "Gebruiker Toegevoegd met wachtwoord: <font color='red'>$data</font>" ;
echo "<meta http-equiv=\"refresh\" content=\"0;URL=http://{$_SERVER['SERVER_NAME']}/a/gebruikers\" />";
}

/*
Ruimte tussen verschillende post invoer functies
*/

if ($_POST['users'] == 'wachtwoord') //Rechten aanpassen
{
$hash = NEW PasswordStorage;
$waarde = $_POST['id'];
$data = $_POST['wachtwoord'];
$hashpass = $hash->create_hash($data);
	try{
$stmt = $db->prepare("UPDATE gebruikers SET wachtwoord =:data WHERE id =:waarde ");
$stmt->execute(
array(
':waarde' => $waarde, 
':data' => $hashpass 
 ));
	}
catch(Exception $e) {
    echo '<h2><font color=red>';
    var_dump($e->getMessage());
	die ('</h2></font> ');
}
$_SESSION[ERROR] = "wachtwoord is aangepast naar $data" ;
echo "<meta http-equiv=\"refresh\" content=\"0;URL=http://{$_SERVER['SERVER_NAME']}/a/gebruikers\" />";
}

/*
Ruimte tussen verschillende post invoer functies
*/

 // einde verwerking users.php 

/*
	Ruimte tussen verschillende post invoer functies
 */

// verwerking nieuw.php 

if ($_POST['nieuw'] == 'nieuw') //Rechten aanpassen
{
//hc nieuw

	$hc = $_POST['hc'];
	$shc = $_POST['shc'];
	$info = htmlspecialchars($_POST['info']);
	$naam = preg_replace("/[^A-Za-z09 ]/", null, $_POST['naam']);
	$naam = ucfirst(strtolower($naam));
	//hc edit
	try{
		$stmt = $db->prepare("select id from hc WHERE id =:hc ");
		$stmt->execute(
		array(
		':hc' => $hc, 
		));
	$count = $stmt->rowCount();
if (!empty($count))
{
	$hc = $hc;				
}
else
{
$hc = preg_replace("/[^A-Za-z0-9 ]/", null, $hc);
while (is_numeric(substr($hc, 0, 1)))
{
		$hc = substr($hc, 1);	
}
$hc = ucfirst(strtolower($hc));


$stmt = $db->prepare("select id from hc WHERE naam =:hc ");
$stmt->execute(
array(
':hc' => $hc, 
));
$count = $stmt->rowCount();
if (!empty($count))
{
$result = $stmt->fetch(PDO::FETCH_ASSOC);		
$hc = $result['id'];				
}
else
{
$stmt = $db->prepare("INSERT INTO hc (naam) VALUES (:hc) ");
$stmt->execute(
array(
':hc' => $hc, 
));
$hc = $db->lastInsertId();
}
}
	}
	catch(Exception $e) {
		echo '<h2><font color=red> lijn 224 error <br>';
		var_dump($e->getMessage());
		die ('</h2></font> ');
	}
//end hc nieuw

//shc nieuw
	try{
		$stmt = $db->prepare("select id from shc WHERE id =:shc ");
		$stmt->execute(
		array(
		':shc' => $shc, 
		));
		$count = $stmt->rowCount();
		if (!empty($count))
		{
			$shc = $shc;				
		}
		else
		{
			$shc = preg_replace("/[^A-Za-z0-9 ]/", null, $shc);
			while (is_numeric(substr($shc, 0, 1)))
			{
				$shc = substr($shc, 1);	
			}
			$shc = ucfirst(strtolower($shc));

			$stmt = $db->prepare("select id from shc WHERE naam =:shc AND hc = :hc ");
			$stmt->execute(
			array(
			':shc' => $shc,
			':hc' => $hc,			
			));
			$count = $stmt->rowCount();
			if (!empty($count))
			{
			$result = $stmt->fetch(PDO::FETCH_ASSOC);		
			$shc = $result['id'];				
			}		
		else
		{		
		$stmt = $db->prepare("INSERT INTO shc (naam,hc) VALUES (:shc,:hc) ");
		$stmt->execute(
		array(
		':hc' => $hc,
		':shc' => $shc,			
		));
		$shc = $db->lastInsertId();	
		}
			}
	}
	catch(Exception $e) {
		echo '<h2><font color=red>';
		var_dump($e->getMessage());
		die ('</h2></font> ');
	}	
	
//end shc nieuw

//post nieuw
	
	try{		
			$stmt = $db->prepare("INSERT INTO posts (shc,naam,info,cu) VALUES (:shc,:naam,:info,:cu) ");
			$stmt->execute(
			array(
			':shc' => $shc,
			':naam' => $naam,
			':info' => $info,
			':cu' => $_SESSION['naam'],
			));
			$post = $db->lastInsertId();	
		}
	catch(Exception $e) {
		echo '<h2><font color=red>';
		var_dump($e->getMessage());
		die ('</h2></font> ');
	}		
	
	$_SESSION[ERROR] = "Nieuw post aangemaakt met id $post" ;
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=http://{$_SERVER['SERVER_NAME']}/post/$post\" />";
}
//einde verwerking nieuw.php

/*
	Ruimte tussen verschillende post invoer functies
 */

 //verwerking ajax/post.php
if ($_POST['post'] == 'verplaats') //Rechten aanpassen
{ 
	$waarde = $_POST['id'];
	$data = $_POST['shc'];
	try{		
		$stmt = $db->prepare("UPDATE posts SET shc =:data,eu=:cu WHERE id =:waarde ");
		$stmt->execute(
		array(
		':waarde' => $waarde, 
		':data' => $data,
		':cu' => $_SESSION['naam'],
		));
		$stmt = $db->prepare("select * from shc WHERE id =:data");
		$stmt->execute(
		array(
		':data' => $data,			
		));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);		
		$shc = $result['naam'];
		$data = $result['hc'];
		$stmt = $db->prepare("select naam from hc WHERE id =:data");
		$stmt->execute(
		array(
		':data' => $data,			
		));
		$result2 = $stmt->fetch(PDO::FETCH_ASSOC);		
		$hc = $result2['naam'];
	}
	catch(Exception $e) {
		echo '<h2><font color=red>';
		var_dump($e->getMessage());
		die ('</h2></font> ');
	}		
	
	$_SESSION[ERROR] = "Post verplaatst naar <font color='red'>$hc - $shc</font>" ;
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=http://{$_SERVER['SERVER_NAME']}/post/$waarde\" />";
	
}

 // einde verwerking ajax/post.php
 
/*
	Ruimte tussen verschillende post invoer functies
 */

// verwerking s/bewerk.php 

if ($_POST['post'] == 'bewerk') //Rechten aanpassen
{
	$id = $_POST['id'];	
	$hc = $_POST['hc'];
	$shc = $_POST['shc'];
	$info = htmlspecialchars($_POST['info']);
	$naam = preg_replace("/[^A-Za-z09 ]/", null, $_POST['naam']);
	$naam = ucfirst(strtolower($naam));
	//hc edit
	try{
		$stmt = $db->prepare("select id from hc WHERE id =:hc ");
		$stmt->execute(
		array(
		':hc' => $hc, 
		));
		$count = $stmt->rowCount();
		if (!empty($count))
		{
			$hc = $hc;				
		}
		else
		{
			$hc = preg_replace("/[^A-Za-z0-9 ]/", null, $hc);
			while (is_numeric(substr($hc, 0, 1)))
			{
				$hc = substr($hc, 1);	
			}
			$hc = ucfirst(strtolower($hc));
			
			
			$stmt = $db->prepare("select id from hc WHERE naam =:hc ");
			$stmt->execute(
			array(
			':hc' => $hc, 
			));
			$count = $stmt->rowCount();
			if (!empty($count))
			{
				$result = $stmt->fetch(PDO::FETCH_ASSOC);		
				$hc = $result['id'];				
			}
			else
			{
				$stmt = $db->prepare("INSERT INTO hc (naam) VALUES (:hc) ");
				$stmt->execute(
				array(
				':hc' => $hc, 
				));
				$hc = $db->lastInsertId();
			}
		}
	}
	catch(Exception $e) {
		echo '<h2><font color=red> lijn 418 error <br>';
		var_dump($e->getMessage());
		die ('</h2></font> ');
	}
	//end hc edit
	
	//shc edit
	try{
		$stmt = $db->prepare("select id from shc WHERE id =:shc ");
		$stmt->execute(
		array(
		':shc' => $shc, 
		));
		$count = $stmt->rowCount();
		if (!empty($count))
		{
			$shc = $shc;				
		}
		else
		{
			$shc = preg_replace("/[^A-Za-z0-9 ]/", null, $shc);
			while (is_numeric(substr($shc, 0, 1)))
			{
				$shc = substr($shc, 1);	
			}
			$shc = ucfirst(strtolower($shc));
			
			$stmt = $db->prepare("select id from shc WHERE naam =:shc AND hc = :hc ");
			$stmt->execute(
			array(
			':shc' => $shc,
			':hc' => $hc,			
			));
			$count = $stmt->rowCount();
			if (!empty($count))
			{
				$result = $stmt->fetch(PDO::FETCH_ASSOC);		
				$shc = $result['id'];				
			}		
			else
			{		
				$stmt = $db->prepare("INSERT INTO shc (naam,hc) VALUES (:shc,:hc) ");
				$stmt->execute(
				array(
				':hc' => $hc,
				':shc' => $shc,			
				));
				$shc = $db->lastInsertId();	
			}
		}
	}
	catch(Exception $e) {
		echo '<h2><font color=red>';
		var_dump($e->getMessage());
		die ('</h2></font> ');
	}	
	
	//end shc edit
	
	//post edit
	
	try{		
		$stmt = $db->prepare("UPDATE posts SET shc=:shc,naam=:naam,info=:info,eu=:cu WHERE id=:id ");
		$stmt->execute(
		array(
		':shc' => $shc,
		':naam' => $naam,
		':info' => $info,
		':id' => $id,
		':cu' => $_SESSION['naam'],		
		));
		$post = $db->lastInsertId();	
	}
	catch(Exception $e) {
		echo '<h2><font color=red>';
		var_dump($e->getMessage());
		die ('</h2></font> ');
	}		
	
	$_SESSION[ERROR] = "Post id $id Aangepast" ;
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=http://{$_SERVER['SERVER_NAME']}/post/$id\" />";
}
//einde verwerking s/bewerk.php

/*
	Ruimte tussen verschillende post invoer functies
 */

	
//Geen Direct Acces
if (empty($_POST)) // Geen direct acces :D
{
echo "<meta http-equiv=\"refresh\" content=\"0;URL=http://{$_SERVER['SERVER_NAME']}/\" />";	
}
?>