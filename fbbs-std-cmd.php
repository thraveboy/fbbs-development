<canvas id="dashChart"></canvas>

<FORM NAME="form1" METHOD="POST" id="form1" hidden>
<?php
  echo '<INPUT TYPE="text" VALUE="' . $previous_command  . ' " ';
  echo 'id="command" NAME="command" SIZE="20" autofocus>';
?>
</FORM>
<br>
<span id="board_name"></span>: Welcome, <?=$username?>.
<button TYPE="Submit"  FORM="backtomain" Value="Submit">Back to Main</button>
<br>
<span id="back_to_main">
  <FORM ID="backtomain" METHOD="POST" ACTION="fbbs-main.php" style="display:inline">
  </form>
</span>
<br>
<?php
  $page = 0;
  if (!empty($previous_cmd_trim)) {
    $cmd_exploded = explode($previous_cmd_trim, " ");
    if (count($cmd_exploded) == 2) {
      if (($cmd_exploded[1][0] == '@') && (strlen($cmd_exploded[1]) > 1)) {
        $page_substr = substr($cmd_exploded[1], 1);
      }
    }
  }

  echo '<span id="pagenum" hidden>';
  echo strval($page) . '</span>';
  echo '<span id="previous_page"><button onclick=previousPage()>Prev';
  echo '</button></span>';
  echo '&nbsp;';
  echo '<span id="next_page" ';
  if ($page == 0) echo 'style="visibility: hidden"';
  echo '><button onclick=nextPage()>Next</button></span>';

?>

<br>

<?php
  $user_permissions = get_user_permissions();
  echo '<br>';
  echo $TABLE_PREFIX . "s:<br><br>";
  if ($TABLE_PREFIX) {
    $read_tables = available_tables($TABLE_PREFIX, $user_permissions);
    foreach ($read_tables as $tablename) {
      echo "<button onclick=retrieveBoard('" . $tablename . "')>" .
        $tablename . '</button>&nbsp;';
    }
  }

?>

<br>
<br>

<?php
  if ($can_post_board) {
    echo '<FORM NAME="postmsg" METHOD="POST" ID="postmsg" ACTION="">
post message-><br>';
    echo '<INPUT TYPE="Text" VALUE="" ';
    echo 'id="message" NAME="command" SIZE="45">';
    echo '</FORM>';
  }
?>

<div id="board_info" hidden></div>
<br>
<u>Messages</u> :
<br>
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
  document.getElementById("pagenum").value = 0;
  updateDash(true);
}

function previousPage() {
  var commandVal = document.getElementById("command").value;
  if (commandVal) {
    var commandSplit = commandVal.split(" ");
    var boardName = commandSplit[0]
    var pageNum = 0;
    var pageVal = document.getElementById("pagenum").value;
    if (pageVal) {
      pageNum = parseInt(pageVal);
    }
    pageNum += 1;
    document.getElementById("pagenum").value = pageNum;
    document.getElementById("command").value = boardName + " @" + pageNum;
    nextPageElement = document.getElementById("next_page");
    nextPageElement.style.visibility = "visible";
    updateDash();
  }
}

function nextPage() {
  var commandVal = document.getElementById("command").value;
  if (commandVal) {
    var commandSplit = commandVal.split(" ");
    var boardName = commandSplit[0]
    var pageNum = 0;
    var pageVal = document.getElementById("pagenum").value;
    if (pageVal) {
      pageNum = parseInt(pageVal);
      pageNum -= 1;
    }
    document.getElementById("pagenum").value = pageNum;
    document.getElementById("command").value = boardName + " @" + pageNum;
    if (pageNum == 0) {
      nextPageElement = document.getElementById("next_page");
      nextPageElement.style.visibility = "hidden";
      document.getElementById("command").value = boardName;
    }
    updateDash();
  }

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

function capturePostEnter(e) {
  if (e.preventDefault) e.preventDefault();

  var dashValue = document.getElementById("command").value;
  var dashName = dashValue.split(" ")[0];
  var xhttp_dashinfo;
  xhttp_dashinfo = new XMLHttpRequest();

  xhttp_dashinfo.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      updateDash();
    }
  }

  xhttp_dashinfo.open("POST", "fbbs-api.php", true);
  xhttp_dashinfo.setRequestHeader("Content-type",
                                  "application/x-www-form-urlencoded");
  var data = document.getElementById("message");
  if (data && data.value) {
    var curr_username = '[' + "<?php echo $username ?>" + ']';
    var commandString = "command="+dashName+" "+data.value+" "+curr_username;
    xhttp_dashinfo.send(commandString);
    document.getElementById("message").value = "";
  }

  return false;
}

var formElementMsg = document.getElementById("postmsg");
if (formElementMsg) {
  if (formElementMsg.attachEvent) {
    formElementMsg.attachEvent("submit", capturePostEnter);
  }
  else {
    formElementMsg.addEventListener("submit", capturePostEnter);
  }
}

var dashUpdater = setInterval(updateDash, 5000);

</script>

