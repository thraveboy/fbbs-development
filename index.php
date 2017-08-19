<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="fbbs-login-style.css">
</head>

<body>
<?php
  $_LOCAL_API_CALLS = 1;
?>

<p>
**********************************
<br>
fury bulletin board system (fbbs) 
<br>
**********************************
<br>
** run on a raspberry pi **
<br>
**********************************
<br>
<FORM NAME="form1" METHOD="POST" ACTION="fbbs-login-submit.php">
username:<br>
<INPUT TYPE="Text" VALUE="" id="username" NAME="username" SIZE="15" autofocus>
<br>
password:<br>
<INPUT TYPE="Password" VALUE="" id="password" NAME="password" SIZE="15" autofocus>
<br>
password again (if new user):<br>
<INPUT TYPE="Password" VALUE="" id="password-again" NAME="password-again" SIZE="15" autofocus>
<div class="userpassDiv" />
<br>
<input type="submit" value="\.enter./" />
</FORM>
</p>

</body>
</html>
