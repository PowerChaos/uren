<?php
if (u()){
	require(getenv("DOCUMENT_ROOT")."/functions/database.php");
	try{
		$groep = $_SESSION['groep'];
		$stmt = $db->prepare("SELECT * FROM groep WHERE id=:groep");
		$stmt->execute(array(':groep' => $groep,));
		$result = $stmt->fetchall(PDO::FETCH_ASSOC);
		$count = $stmt->rowCount();
		if ($count > "0" )
		{
			foreach($result as $info) {
				$str = arr($info['user']);
				sort($str);
				$count = count($str);
				if (!empty($str))
				{
					for($i=0;$i < $count;$i++){
						$value = $str[$i];
						$stmt = $db->prepare("SELECT * FROM gebruikers where id=:gebruiker");
						$stmt->execute(array(':gebruiker' => $value,));
						$result = $stmt->fetch(PDO::FETCH_ASSOC);
						$selected = ($result['id'] == $_SESSION['id'])?"selected":"";
						$groepselect .=  "<option value='$result[id]' $selected > $result[naam]</option>";
					}
				}
				else
				{
						
					$groepselect =  "<option value='$_SESSION[id]' selected>$_SESSION[naam]</option>";
				}
			}
		}
		else
		{
			
			$groepselect =  "<option value='$_SESSION[id]' selected>$_SESSION[naam]</option>";
		}
		}//end try
		catch(Exception $e) {
			echo '<h2><font color=red>';
			var_dump($e->getMessage());
			die ('</h2></font> ');
		}
	?>
<div class="alert alert-success text-center">
	<strong>Nieuwe Aanwezigheid Registratie</strong>
</div>
	
				<form class="form-horizontal" id="postForm" action="../invoer" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="registratie" value="aanwezig">
					<div class="form-group">
						<label class="control-label col-sm-2" for="uid">Gebruiker:</label>
						<div class="col-sm-10">
							<select name="uid" class="form-control" id='uid'>
							<?php echo $groepselect ?>
							</select>
						</div>
					</div>
					<!--
					<div class="form-group">
						<label class="control-label col-sm-2" for="info">Titel:</label>
						<div class="col-sm-10">
						<input type="text" class="form-control" name="info" id="info"maxlength="32" aria-describedby="infoblock">
							<p id="infoblock" class="form-text text-muted">
							korte beschrijving van max 32 characters voor weergave op kalendar
							</p>
						</div>
					</div>
							-->					
			<div class="form-group">
			    <label class="control-label col-sm-2" for="date">Datum:</label>
				<div class="col-sm-10">
				<div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="nl-BE" data-date-today-highlight="true" data-date-today-btn="linked">
					<input type="text" class="form-control" value="<?php echo date("Y-m-d")?>" name="datum" id="datum">
					<div class="input-group-addon">
						<span class="glyphicon glyphicon-th"></span>
					</div>
				</div>
				</div>
				</div>
				<div class="form-group">
				<label class="control-label col-sm-2" for="van">van:</label>
				<div class="col-sm-10">
					<select name="van" class="form-control" id='van'>
					<?php
						$start = "08:00";
						$end = "11:00";
						$tStart = strtotime($start);
						$tEnd = strtotime($end);
						$tNow = $tStart;
						echo "<option value='z'>Ziek</option>";
						while($tNow <= $tEnd){		
							$time = date("H:i",$tNow);
							$selected = ($time == '08:30')?"selected":"";
							echo "<option value='$time' $selected>$time</option>";
							$tNow = strtotime('+15 minutes',$tNow);
						}
						?>
				</select>
				</div>
				</div>
				<div class="form-group">
				<label  class="control-label col-sm-2" for="tot">tot:</label>
					<div class="col-sm-10">
						<select name="tot" class="form-control" id='tot'>
					<?php
						$start = "15:00";
						$end = "22:00";
						$tStart = strtotime($start);
						$tEnd = strtotime($end);
						$tNow = $tStart;
						while($tNow <= $tEnd){
							$time = date("H:i",$tNow);
							$selected = ($time == '16:30')?"selected":"";							
							echo "<option value='$time' $selected>$time</option>";
							$tNow = strtotime('+15 minutes',$tNow);
						}
					?>
				</select>
				</div>				
			</div>
		<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
      <button type="submit" id="submit" value="Submit" class="btn btn-success btn-block">Registreer Aanwezigheid</button>
	  </div>
	  </div>
</form>
<?
}//end try
?>	