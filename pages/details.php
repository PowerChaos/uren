<?php
if (u()){
echo "<h1>".$_SESSION[ERROR]."</h1>";
$_SESSION[ERROR] ="";
	if ($_GET['details'])
	{
		// parameters from URL
	$details = $_GET['details'];
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
		$print = ($fac != "y")?"<a href=\"#\" class=\"btn btn-success btn-block btn-sm\" onclick=\"PrintElem('#print')\" ><i class='material-icons' title='print' aria-hidden='true'>print</i><span class=\"sr-only\">print</span></a>":"Al Gefactureerd";
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
			if ($fac != 'y')
			{
			$table .="<td id='remove0'>Delete</td>";
			}
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
				if ($fac != 'y')
				{
				$table .=  "<td id='remove$total'><a href='#' data-toggle='modal' data-target='#modal' id='$info[id]' onclick='post(this.id,\"details\");'>  <i class='fa fa-trash-o' title='Verwijder details' aria-hidden='true'></i><span class='sr-only'>Verwijder details</span></a></td>";
				}
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
	?>
	<div class="text-right">
	<?php if (s()){ 
	if ($fac != "y"){?>
	<a href="#" class="btn btn-warning btn-sm" data-toggle='modal' data-target='#modal' id='<?php echo $result3['id'];?>' onclick='post(this.id,"factuur");'><i class="fi-checkbox"></i>Gefactureerd<span class="sr-only">Gefactureerd</span></a>
	<?php } }
	if ($fac != 'y'){?>
	</div>
<script type="text/javascript">
	function PrintElem(elem)
	{	
		Popup($(elem).html());
	}
	
	function Popup(data) 
	{
		var mywindow = window.open('', 'post', 'height=auto,width=auto');
		mywindow.document.write('<html><head><title><?php echo $result3['datum'] ?> van <?php echo $result3['van'] ?> tot <?php echo $result3['tot'] ?> voor  <?php echo $result2['naam']?> met <?php echo $result3['uren'] ?> uren in totaal</title>');
		mywindow.document.write('</head><body >');
		mywindow.document.write(data);
		mywindow.document.write('</body></html>');
		mywindow.document.close(); // necessary for IE >= 10
		mywindow.focus(); // necessary for IE >= 10
		for(var i = 0; i <= <?php echo $total ?>; i++){
		mywindow.document.getElementById('remove'+i).remove();
		}
		mywindow.print();
		mywindow.close();
		
		return true;
	}
	
	function post(val, dat) {
		$.ajax({
			type: "POST",
			url: "../ajax/details.php",
			data:'groep='+dat+'&waarde='+val,
			success: function(data){
				//alert(data);
				//alert ("del: " +dat+ " en waarde: " +val);
				$("#modal").modal('show');
				$("#modalcode").html(data);
				
			}
		});
	}
	
	$(document).ready(function() {						
		//selectize
		var xhr;
		var select_hc, $select_hc;
		var select_shc, $select_shc;
		
		$select_hc = $('#klant').selectize({
			valueField: 'id',
			labelField: 'name',
			searchField: 'name',
			plugins: ['restore_on_backspace'],
			create: true,
			createOnBlur: true,
			openOnFocus: true,
			preload: true,
			load: function(query, callback) {
				this.settings.load = null;
				$.ajax({
					url: '../ajax/kp.php',
					type: 'POST',
					dataType: 'json',
					data: {
						name: "load",
						klant: "1",
					},
					error: function() {
						callback();
					},
					success: function(res) {
						callback(res);
					}
				});
				(function(response){
					callback(response);
				},
				function(){
					callback();
				});
			},	
			onChange: function(value) {
				if (!value.length) return;
				select_shc.disable();
				select_shc.clearOptions();
				select_shc.load(function(callback) {
					xhr && xhr.abort();
					xhr = $.ajax({
						url: '../ajax/kp.php',
						type: 'POST',
						dataType: 'json',
						data: {
							id: value,
							name: $("#klant option:selected").text(),
							project: '1',
						},
						success: function(results) {
							select_shc.enable();
							callback(results);
						},
						error: function() {
							select_shc.disable();
							callback();
						}
					})
				});
			}
		});
		
		$select_shc = $('#project').selectize({
			valueField: 'id',
			labelField: 'name',
			searchField: 'name',
			plugins: ['restore_on_backspace'],
			create: true,
			createOnBlur: true,
			openOnFocus: true,
		});
		
		select_shc  = $select_shc[0].selectize;
		select_hc = $select_hc[0].selectize;
		select_shc.disable();
		
		//disable Submit Button
		$("#submit").attr('disabled', 'disabled');
		$("#submit").attr('class', 'btn btn-danger btn-block');
		$("form").keyup(function() {
			// To Disable Submit Button
			$("#submit").attr('disabled', 'disabled');
			$("#submit").attr('class', 'btn btn-danger btn-block');
			// Validating Fields
			var hc = $("#klant option:selected").text();
			var shc = $("#project option:selected").text();
			var info = $("#info").val();
			if (!(hc == "" || shc == "" || info == "" )){
				
				// To Enable Submit Button
				$("#submit").removeAttr('disabled');
				$("#submit").removeAttr('class');
				$("#submit").attr('class', 'btn btn-success btn-block');
			}
		});
	
	});
	
</script>
	<div class="alert alert-warning">
		<div class="row">
			<div class="span12">
				<h2>POST DATA</h2>
				<pre>
					<?php print_r($_POST); ?>
				</pre>
			</div>
		</div>
	</div>


<div class="alert alert-success text-center">
	<strong>Nieuwe Detail Registratie</strong>
</div>
<div class='hidden-print'>
	
				<form class="form-horizontal" id="postForm" action="../invoer" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="registratie" value="details">
				<input type="hidden" name="id" value="<?php echo $result3['id']?>">
				<input type="hidden" name="datum" value="<?php echo $result3['datum'] ?>">
					<div class="form-group">
						<label class="control-label col-sm-2" for="info">info:</label>
						<div class="col-sm-10">
						<textarea rows="4" cols="50" class="form-control" name="info" id="info" aria-describedby="infoblock"></textarea>
							<p id="infoblock" class="form-text text-muted">
							beschrijving van de taak
							</p>
						</div>
					</div>	
				<div class="form-group">
				<label class="control-label col-sm-2" for="van">van:</label>
				<div class="col-sm-10">
					<select name="van" class="form-control" id='van'>
					<?php
						$start = "08:00";
						$end = "21:30";
						$tStart = strtotime($start);
						$tEnd = strtotime($end);
						$tNow = $tStart;
						while($tNow <= $tEnd){		
							$time = date("H:i",$tNow);
							$selected = ($time == $result3['van'])?"selected":"";
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
						$start = "08:30";
						$end = "22:00";
						$tStart = strtotime($start);
						$tEnd = strtotime($end);
						$tNow = $tStart;
						while($tNow <= $tEnd){
							$time = date("H:i",$tNow);
							$selected = ($time == $result3['tot'])?"selected":"";							
							echo "<option value='$time' $selected>$time</option>";
							$tNow = strtotime('+15 minutes',$tNow);
						}
					?>
				</select>
				</div>				
			</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="klant">Klant</label>
						<div class="col-sm-10">
							<select id="klant"  placeholder="Klant" name='klant' class="form-control">
								<option value=""></option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="project">Project</label>
						<div class="col-sm-10"> 
							<select id="project"  placeholder="project" name='project' class="form-control">
								<option value=""></option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="factuur">Factureren</label>
						<div class="col-sm-10"> 
							<select id="factuur"  placeholder="factuur" name='factuur' class="form-control">
								<option value="y">Ja</option>
								<option value="n" selected>Neen</option>
							</select>
						</div>
					</div>
		<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
      <button type="submit" id="submit" value="Submit" class="btn btn-success">Registreer Details</button>
	  </div>
	  </div>
</form>
</div>
<?
}
else
{
		echo "<div class='alert alert-block alert-danger text-center'>Factuur is al aangemaakt , geen bewerking meer mogelijk</div>";
}
}//end try
else
{
		echo"aanwezigheid ID Bestaan niet";
}
}
?>	