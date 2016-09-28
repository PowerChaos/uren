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
      
        <h2 class="form-signin-heading"><?php
    echo $_SESSION[ERROR]?$_SESSION[ERROR]:"Aanwezigheid Registratie"; //show our sesion error above the login form
$_SESSION[ERROR]="";
	?></h2><hr />
        
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
echo "<h1>".$_SESSION[ERROR]."</h1>";
$_SESSION[ERROR] ="";
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
		eventRender: function(event, element, view) {
			if (event.volledig == "1")
			{
				$("#volledig").removeAttr('bgcolor');
				$("#volledig").attr('bgcolor', event.kleur);
			};
			if (event.volledig == "0")
			{
				$("#niet-volledig").removeAttr('bgcolor');
				$("#niet-volledig").attr('bgcolor', event.kleur);
			}
			//alert(event.volledig);
			},
		
		});
	});
</script>
	<style>
		#legend h4 {
		text-shadow: 2px 2px #FF0000;
		}
	</style>
	
<table class="table text-center" id='legend'>
    <thead>
		<tr>
			<th><h3>Legende</h3></th>
		</tr>
    </thead>
    <tbody>
		<tr>
			<td id="volledig" bgcolor="orange"><font color='white'><h4>Aanwezigheid is Volledig Ingevuld</h4></font></td>
		</tr>
		<tr>
			<td id="niet-volledig" bgcolor="black"><font color='white'><h4>Aanwezigheid is Niet Volledig ingevuld</h4></font></td>
		</tr>		
		</tbody>
		</table>
		
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

