<!DOCTYPE html>
<html>
<head>
<style>
body, input {
    font-family: monospace;
    font-size: xx-large;
    background-color: black;
    color: green;
}

input {
    border-top-width: 1px;
    border-bottom-width: 1px;
    border-left-width: 0px;
    border-right-width: 0px;
    border-color: green;
    outline-width: 1px;
    outline-width-left: 0px;
    outline-width-right: 0px;
    outline-color: green;
}

</style>
</head>

<body>
<?php
  $_LOCAL_API_CALLS = 1;
?>

<p>
****************************************
<br>
** fury bulletin board system (fbbs) ***
<br>
****************************************
<br>
** https://github.com/thraveboy/fbbs ***
<br>
****************************************
<br>
******** run on a raspberry pi *********
<br>
****************************************
<br>
<FORM NAME="form1" METHOD="POST" ACTION="fbbs-login-submit.php">
username:
<INPUT TYPE="Text" VALUE="" id="username" NAME="username" SIZE="40" autofocus>
<br>
password:
<INPUT TYPE="Password" VALUE="" id="password" NAME="password" SIZE="40" autofocus>
<br>
password again (if new user):
<INPUT TYPE="Password" VALUE="" id="password-again" NAME="password-again" SIZE="40" autofocus>
<div class="userpassDiv" />
<br>
<input type="submit" value="\.enter./" />
</FORM>
</p>

</body>
</html>
