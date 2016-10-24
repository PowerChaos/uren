<?php
if (u())
{
?>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
      <a class="navbar-brand" href="../home">Aanwezigheid</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav"> 
		<li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Registratie
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
        <li><a href="../aanwezig">Nieuwe Aanwezigheid</a></li>	
        </ul>
      </li>
	  </ul>
	 <ul class="nav navbar-nav navbar-right"> 
	  	  <?php
	  if (a())
	  {
		  
?>		
<li><a href="../a/overzicht">OverZicht</a></li>			
		<li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Admin Menu
        <span class="caret"></span></a>
        <ul class="dropdown-menu">					
		<li><a href="../a/gebruikers">Gebruikers</a></li>
		<li><a href="../a/groepen">Groepen</a></li>
		<li class="divider"></li>
		<li><a href="../a/versie">Versie Controle</a></li>
        </ul>
      </li>
<?php		
	  }
	  ?>
	  <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $_SESSION['naam'] ?>
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="../pass">wachtwoord</a></li>
          <li><a href="../logout">Log Uit</a></li> 
        </ul>
      </li>
      </ul>
    </div>
  </div>
</nav>
<?php
}
?>
        <!-- Page Content -->
<div class="col-sm-12 col-lg-12">
	<?php
		if ($_SESSION[ERROR] != "")
		{	
			echo "<div class='alert alert-success fade in text-center' data-dismiss='alert' role='alert'>
			$_SESSION[ERROR]
			</div>";
			$_SESSION[ERROR] ="";
		}
	?>