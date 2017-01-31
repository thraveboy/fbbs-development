:::::::::::::::::::::::::::;::::::::: Welcome, <?=$username?>.
<br>
: <b>f</b>ury <b>b</b>ulletin <b>b</b>oard <b>s</b>ystem (<b>fbbs</b>) :
<span id="back_to_main">
  <FORM NAME="backtomain" METHOD="POST" ACTION="fbbs-main.php" style="display:inline">
    <INPUT TYPE="Submit"  Value="<-back to main->">
  </form>
</span>
<br>
::::::::::::::::::::::::::::::::::::: (last online:
<b>[<span id="last_active"><?=$lastauth?></span>]</b>)</u>
<br>
<FORM id="launch_command_form" ACTION="" METHOD="POST">
<?php
  echo ': command: <INPUT TYPE="Text" VALUE="" ';
  echo 'id="launch_command" NAME="launch_command" SIZE="12" autofocus>';
?>
    <INPUT ID="submission" TYPE="Submit" Value="|/\enter/\|"
     onclick="launchCommand()">
</FORM>
:::::::::::::::::::::::::::::::::::::
<br> <FORM NAME="form1" METHOD="POST"
id="form1">
board name::0>
<?php
  echo '<INPUT TYPE="text" VALUE="' . $previous_command  . ' " ';
  echo 'id="command" NAME="command" SIZE="20" autofocus>';
?>
    <INPUT TYPE="submit" Value="|/\enter/\|">
</FORM>
<FORM NAME="postmsg" METHOD="POST" ID="postmsg" ACTION="">
  post message|>
<?php
  echo '<INPUT TYPE="Text" VALUE="" ';
  echo 'id="message" NAME="command" SIZE="40">';
?>
  <INPUT TYPE="Submit" Value="<-enter|" SIZE="7">
</FORM>
<FORM NAME="viewmsg" METHOD="POST" ID="getmsg" ACTION="">
read message@>
<INPUT TYPE="TEXT" VALUE="" id="getmsgid" SIZE="5">
<INPUT TYPE="Submit" Value="<=enter|" SIZE ="7">
<span id="displaymsg"></span>
</form>

<canvas id="dashChart" width="640"
 height="480"></canvas>
<br>
: <u>board info</u> : <span id="board_name"></span>
<br>
<br>
<div id="board_info"></div>
<br>
<br>
: <u>data</u> :
<br>
<br>
<div id="dash"></div>



