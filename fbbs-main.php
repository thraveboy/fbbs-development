<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="fbbs-style.css">
</head>

<body>
<?php
  $previous_cmd_trim = trim($_POST['command']);
  $_POST['command'] = $previous_cmd_trim;

  $_LOCAL_API_CALLS = 1;

  require_once 'fbbs-api.php';

  $previous_command = explode(" ", $previous_cmd_trim)[0];
  echo '<div id="previous_command" hidden>';
  print($previous_command);
  echo '</div>';

  require_once 'fbbs-user-auth.php';

  $username = authorize_user();

  $lastauth = last_auth_user();
?>
|\\::::::::::::|
<br>
<b>f</b>ury <b>b</b>ulletin <b>b</b>oard <b>s</b>ystem (<b>fbbs</b>)
<br>
Welcome <?=$username?>
<br>
|||............|
<br>
last online:[<?=$lastauth?>]
<br>

<?php

  include "fbbs-available-commands.php";
  echo '<FORM id="command_form" ACTION="" METHOD="POST">';
  echo '</FORM>';
  echo '<br>';
  foreach ($available_commands as $cmd) {
    echo "<button onclick=launchCommand('" . $cmd . "')>" . $cmd .
      '</button>&nbsp';
  }
?>

<script>
  function launchCommand(str) {
//    var str = document.getElementById("command").value;
    var str_trim = str.trim();
    var str = str_trim.split(" ")[0];
    if (str.length == 0) {
      document.getElementById("dash").innerHTML = "Try help... ";
      return false;
    }
    else {
      document.getElementById("command_form").action ="fbbs-" + str + ".php";
      document.getElementById("command_form").submit();
    }
    return false;
  }
</script>

</body>
</html>
