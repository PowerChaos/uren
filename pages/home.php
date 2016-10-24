<?
if (!u()){
?>

<link rel="stylesheet" href="//<?php echo $_SERVER['SERVER_NAME']?>/template/boot/css/login.css">
<!-- Login -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
<script src="//<?php echo $_SERVER['SERVER_NAME']?>/template/boot/js/login.js"></script>
<!-- login -->
	<div class="signin-form">

 <div class="container">
     
        
       <form class="form-signin" method="post" id="login-form">
      
        <h2 class="form-signin-heading">
			Aanwezigheid Registratie
			</h2><hr />
        
        <div id="error">
        <!-- error will be shown here ! -->
        </div>
        
        <div class="form-group">
        <input type="username" class="form-control" placeholder="Gebruiker" name="username" id="username" />
        <span id="check-username"></span>
        </div>
        
        <div class="form-group">
        <input type="password" class="form-control" placeholder="Password" name="password" id="password" />
        </div>
       
      <hr />
        
        <div class="form-group">
            <button type="submit" class="btn btn-default" name="btn-login" id="btn-login">
      <span class="glyphicon glyphicon-log-in"></span> &nbsp; Inloggen
   </button> 
        </div>  
      
      </form>

    </div>
    
</div>
<?php
}
if (u())
{
?>
<div class="alert alert-success text-center">
	<strong>Aanwezigheid OverZicht</strong>
</div>
<div id="calendar"></div>
<!-- use fixed data -->
<script type="application/javascript">
	$(document).ready(function() { 
		$('#calendar').fullCalendar({	
			 events:{
				url: '../ajax/events.php',
				type: 'POST',
				 data: {
					 id: '<?php echo $_SESSION[id]?>',
					 groep: '<?php echo $_SESSION[groep]?>',
				 },
				error: function() {
					alert('there was an error while fetching events!');
				},
			},
			height: 500,
			header: {
				left: 'today',
				center: 'prev title next',
				right: 'month agendaWeek listMonth'
			},
			eventClick:  function(event, jsEvent, view) {
				$('#modalTitle').html(event.datum);
				$('#modalBody').html(event.description);
				$('#eventUrl').attr('href',event.link);
				$('#fullCalModal').modal();
			},		
		});
	});
</script>
	<style>
		#legend h5 {
		text-shadow: 2px 2px #000000;
		}
	</style>
<div class="row">
<div class="col-sm-5"></div>
<div class="col-sm-2">
	<table class="table text-center" id='legend'>
		<thead>
			<tr>
				<th><h3>Legende</h3></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td id="volledig" bgcolor="#008000" name="volledig"><font color='white'><h5>Volledig Ingevuld</h5></font></td>
			</tr>
			<tr>
				<td id="niet-volledig" bgcolor="#D05860" name="niet-volledig"><font color='white'><h5>Niet Volledig ingevuld</h5></font></td>
			</tr>
			<tr>
				<td id="factuur" bgcolor="#A0B0E0" name="factuur"><font color='white'><h5>Factuur al Aangemaakt</h5></font></td>
			</tr>		
		</tbody>
	</table>
</div>
<div class="col-sm-5"></div>
</div>
<div id="fullCalModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                <h4 id="modalTitle" class="modal-title"></h4>
            </div>
            <div id="modalBody" class="modal-body"></div>
            <div class="modal-footer">
                <a id="eventUrl" class="btn btn-success btn-block btn-lg"><i class="material-icons">playlist_add</i></a>
            </div>
        </div>
    </div>
</div>
<?
}// Einde start sessie
?>

