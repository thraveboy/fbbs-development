<canvas id="dashChart"></canvas>

<?php
  echo '<INPUT TYPE="text" VALUE="' . $previous_command  . ' " ';
  echo 'id="command" NAME="command" SIZE="20" hidden>';
?>

<span id="board_name"></span>
<span id="back_to_main">
  <FORM ID="backtomain" METHOD="POST" ACTION="fbbs-main.php" style="display:inline">
  </form>
    <button TYPE="Submit"  FORM="backtomain" VALUE="Submit">Back to Main</button>
</span>
<br> <FORM NAME="form1" METHOD="POST"
id="form1" hidden></FORM>

<br>
<?php
  $user_permissions = get_user_permissions();

  echo $TABLE_PREFIX . "s:<br><br>";
  if ($TABLE_PREFIX) {
    $read_tables = available_tables($TABLE_PREFIX, $user_permissions);
    foreach ($read_tables as $tablename) {
      $tablename_wo_prefix = substr($tablename, strlen($TABLE_PREFIX));
      echo "<button onclick=retrieveBoard('" . $tablename . "')>" .
        $tablename_wo_prefix . '</button>&nbsp;';
    }
  }

?>
<br>
<br>
Data values:
<br>
<div id="dash"></div>

<script src="moment-with-locales.min.js"></script>
<script src="Chart.min.js"></script>
<script src="fbbs-core-data-draw.js"></script>

<script>

var prev_cmd_val = document.getElementById("previous_command").innerText;
prev_cmd_val = prev_cmd_val.split(" ")[0];

if (prev_cmd_val) {
  document.getElementById("command").value = prev_cmd_val;
}

function getURLWithoutParams() {
  return location.pathname;
}

function updateDash(addCommandToUrl = false ) {
  var dashName = document.getElementById("command").value;
  if (dashName) {
    showDash(dashName);
    if (addCommandToUrl) {
      history.pushState({}, '',
                        getURLWithoutParams() + '?command=' + dashName);
    }
  }
}

function retrieveBoard(boardName) {
  document.getElementById("command").value = boardName;
  updateDash(true);
}

function captureFormEnter(e) {
  if (e.preventDefault) e.preventDefault();

  updateDash(true);

  return false;
}

var formElement = document.getElementById('form1');
if (formElement.attachEvent) {
  formElement.attachEvent("submit", captureFormEnter);
}
else {
  formElement.addEventListener("submit", captureFormEnter);
}

var dashUpdater = setInterval(updateDash, 5000);

</script>

