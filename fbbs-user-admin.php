<!DOCTYPE html>
<html>
<head>
<style>
body, input {
    font-family: monospace;
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
    color: green;
}

input[type=submit] {
    outline-color: green;
    background-color: black;
}

p {
  white-space: nowrap;
}

</style>
</head>

<body>
<?php

  $_LOCAL_API_CALLS = 1;

  require_once 'fbbs-user-auth.php';

  $username = authorize_user();

  $lastauth = last_auth_user();

  require_once 'fbbs-user-admin-api.php';
?>
|\\:::::::::::::::::::::::::::::::::::|\::::<?=$username?>:::::
<span id="back_to_main">
  <FORM NAME="backtomain" METHOD="POST" ACTION="fbbs-main.php" style="display:inline">
    <INPUT TYPE="Submit"  Value="<<--back to main">
  </form>
</span>
<br>
||| <b>f</b>ury <b>b</b>ulletin <b>b</b>oard <b>s</b>ystem (<b>fbbs</b>) ||: board :
<span id="board_name"></span>
<br>
|||...................................|/:::::::::::::::...last online...
<b>[<span id="last_active"><?=$lastauth?></span>]</b>...
<br>
|||||||||||||||||

</body>
</html>
