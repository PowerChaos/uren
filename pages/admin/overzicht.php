<?php
if (a()){
require(getenv("DOCUMENT_ROOT")."/functions/database.php");
//details
	try{	
		$stmt = $db->prepare("SELECT * FROM details ORDER BY datum DESC");
		$stmt->execute();
		$result = $stmt->fetchall(PDO::FETCH_ASSOC);
		foreach($result as $info)
		{
				try{
					$stmt2 = $db->prepare("SELECT * FROM
					klanten WHERE id = :klant ORDER BY id ASC
					");
					$stmt2->execute(array( 
					':klant' => $info['kid'],
					));
					$klant = $stmt2->fetch(PDO::FETCH_ASSOC);
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
				
				$fac = ($info[factuur] == 'y')?"<td class=success >$info[factuur]</td>":"<td class=danger >$info[factuur]</td>";
				$table .= "<tr data-toggle='modal' data-target='#modal' id='$info[awid]' onclick='detail(this.id);'>";
				$table .=  "<td class=warning >$info[datum]</td>";
				$table .=  "<td class=warning >$info[van]</td>";
				$table .=  "<td class=warning >$info[tot]</td>";
				$table .=  "<td class=success>$klant[naam]</td>";
				$table .=  "<td class=success>$project[naam]</td>";
				$table .=  "<td class=danger>$info[uren]</td>";
				$table .=  "<td class=info>$info[info]</td>";
				$table .=  $fac;
				$table .=  "</tr>";
			}
			$details = $table;			
			}	
	catch(Exception $e) {
		echo '<h2><font color=red>';
		var_dump($e->getMessage());
		die ('</h2></font> ');
	}
//einde details

//aanwezig
	try{	
		$stmt6 = $db->prepare("SELECT * FROM aanwezig ORDER BY datum DESC");
		$stmt6->execute();
		$result5 = $stmt6->fetchall(PDO::FETCH_ASSOC);
		foreach($result5 as $info2)
		{
			try{
				$stmt5 = $db->prepare("SELECT * FROM
				gebruikers WHERE id = :klant ORDER BY id ASC
				");
				$stmt5->execute(array( 
				':klant' => $info2['uid'],
				));
				$user = $stmt5->fetch(PDO::FETCH_ASSOC);
			}//end try
			catch(Exception $e) {
				echo '<h2><font color=red>';
				var_dump($e->getMessage());
				die ('</h2></font> ');
			}		
			$fac2 = ($info2[gefactureerd] == 'y')?"<td class=success >$info2[gefactureerd]</td>":"<td class=danger >$info2[gefactureerd]</td>";
			$table2 .= "<tr data-toggle='modal' data-target='#modal' id='$info2[id]' onclick='detail(this.id);'>";
			$table2 .=  "<td class=success>$user[naam]</td>";
			$table2 .=  "<td class=warning >$info2[datum]</td>";
			$table2 .=  "<td class=warning >$info2[van]</td>";
			$table2 .=  "<td class=warning >$info2[tot]</td>";
			$table2 .=  "<td class=danger>$info2[uren]</td>";
			$table2 .=  $fac2;
			$table2 .=  "</tr>";
		}
		$aanwezig = $table2;			
	}	
	catch(Exception $e) {
		echo '<h2><font color=red>';
		var_dump($e->getMessage());
		die ('</h2></font> ');
	}
?>
<script>
	function detail(val, dat) {
		$.ajax({
			type: "POST",
			url: "../ajax/overzicht.php",
			data:'details='+val,
			success: function(data){
				//alert(data);
				//alert ("del: " +dat+ " en waarde: " +val);
				$("#modal").modal('show');
				$("#modalcode").html(data);
				
			}
		});
	}
	$(document).ready(function() {
		$('table.table').DataTable( {
			scrollY:        '15vh',
			scrollCollapse: true,
			paging:         false,
			ordering: true,
			initComplete: function (settings, json) {
    // Get initial order
    var orderInit = this.api().order();

    this.api().columns().every( function (index) {
        var column = this;
        var select = $('<select><option value=""></option></select>')
            .appendTo( $(column.footer()).empty() )
            .on( 'change', function () {
                var val = $.fn.dataTable.util.escapeRegex(
                    $(this).val()
                );

                column
				.search( val ? '^'+val+'$' : '', true, false )
				.draw();
            } );
		
        // NOTE: Temporarily sort the column data before retrieving it
        // with data() function.
        column.order('desc').draw(false).data().unique().each( function ( d, j ) {
				
			if(column.search() === '^'+d+'$'){
				select.append( '<option value="'+d+'" selected="selected">'+d+'</option>' )
			} else {
				select.append( '<option value="'+d+'">'+d+'</option>' )
			}	

        } );
    } );
				
				// Restore initial order
				this.api().order(orderInit).draw(false);
			} 
		} );		
	} );
</script>
<div class='alert alert-block alert-success text-center'> Overzicht Aanwezigheden </div>
<table border=1 id='aanwezig' class="table table-striped table-bordered table-hover">
  <thead>
	  <tr>	
		  <th>Gebruiker</th>
		  <th>datum</th>
		  <th>van</th>
		  <th>Tot</th>
		  <th>uren</th>
		  <th>Gefactureerd</th>
	  </tr>
  </thead>
	<tfoot>
		<tr>
			<th>Gebruiker</th>
			<th>datum</th>
			<th>van</th>
			<th>Tot</th>
			<th>uren</th>
			<th>Gefactureerd</th>
		</tr>
	</tfoot>
	<tbody>
<?php echo $aanwezig; ?>
</tbody></table>
<div class='alert alert-block alert-danger text-center'> Overzicht Details </div>
<table border=1 id='details' class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>datum</th>
					<th>van</th>
					<th>Tot</th>
					<th>Klant</th>
					<th>Project</th>
					<th>uren</th>
					<th>info</th>
					<th>Factuur</th>
				</tr>
			</thead>
	<tfoot>
		<tr>
		    <th>datum</th>
			<th>van</th>
			<th>Tot</th>
			<th>Klant</th>
			<th>Project</th>
			<th>uren</th>
			<th>info</th>
			<th>Factuur</th>
		</tr>
	</tfoot>
		<tbody>
<?php echo $details ?>
</tbody></table>		
<?php } ?>