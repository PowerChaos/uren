<?php
if (u()){ //begin user
?>
<form action="../invoer" method="post" id='pass' name='pass'>
<input type="hidden" name="info" value="pass" />
  oud Wachtwoord:<br>
  <input class="form-control" type="text" name="oldpass"><br>
  Nieuw Wachtwoord:<br>
  <input class="form-control" type="password" name="newpass"><br>
  Herhaling Nieuw Wachtwoord:<br>
  <input class="form-control" type="password" name="newpass2"><br>
  <input class="btn btn-danger" type="submit" value="Submit">
</form>
<?php
}
else
{
echo "<meta http-equiv=\"refresh\" content=\"0;URL=http://{$_SERVER['SERVER_NAME']}/\" />";	
}
?>