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
	<strong>Kalender OverZicht</strong>
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
			}
		});
	});
</script>
<div id="fullCalModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                <h4 id="modalTitle" class="modal-title"></h4>
            </div>
            <div id="modalBody" class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button class="btn btn-success"><a id="eventUrl" target="_blank">Event Page</a></button>
            </div>
        </div>
    </div>
</div>
<?
}// Einde start sessie
?>

