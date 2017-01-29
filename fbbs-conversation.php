<!DOCTYPE html>
<html>
<head>
<style>
body, div, span, input {
    font-family: monospace;
    background-color: black;
    color: green;
}

input {
    border-top-width: 1px;
    border-bottom-width: 1px;
    border-left-width: 0px;
    border-right-width: 0px;
    border-color: blue;
    outline-width: 1px;
    outline-width-left: 0px;
    outline-width-right: 0px;
    outline-color: lime;
    color: green;
}

input[type=submit] {
    outline-color: lime;
    background-color: black;
}

p {
  white-space: nowrap;
}

canvas {
    font-family: monospace;
    outline-color: purple;
    border-color: purple;
    border-width: 2px;
    outline-width: 2px;
    background-color: black;
    color: cyan;
}
</style>
</head>
<body>

<?php
  $previous_cmd_trim = '';

  if (isset($_POST['command'])) {
    $previous_cmd_trim = trim($_POST['command']);
    $_POST['command'] = $previous_cmd_trim;
  }

  if (empty($previous_cmd_trim) && isset($_GET['command'])) {
    $previous_cmd_trim = trim($_GET['command']);
    $_POST['command'] = $previous_cmd_trim;
  }

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

:::::::::::::::::::::::::::;::::::::: Welcome, <?=$username?>.
<br>
: <b>f</b>ury <b>b</b>ulletin <b>b</b>oard <b>s</b>ystem (<b>fbbs</b>) :
<span id="back_to_main">
  <FORM NAME="backtomain" METHOD="POST" ACTION="fbbs-main.php" style="display:inline">
    <INPUT TYPE="Submit"  Value="<<--back to main">
  </form>
</span>
<br>
<u>::::::::::::::::::::::::::::::::::::: (last online:
<b>[<span id="last_active"><?=$lastauth?></span>]</b>)</u>
<br>
<br> <FORM NAME="form1" METHOD="POST"
id="form1">
board name:
<?php
  echo '<INPUT TYPE="text" VALUE="' . $previous_command  . ' " ';
  echo 'id="command" NAME="command" SIZE="20" autofocus>';
?>
    <INPUT TYPE="submit" Value="|/\enter/\|">
</FORM>
<br>
<FORM NAME="postmsg" METHOD="POST" ID="postmsg" ACTION="">
  post message=>
<?php
  echo '<INPUT TYPE="Text" VALUE="" ';
  echo 'id="message" NAME="command" SIZE="40">';
?>
  <INPUT TYPE="Submit" Value="<-enter|" SIZE="7">
</FORM>
<br>
<FORM NAME="viewmsg" METHOD="POST" ID="getmsg" ACTION="">
read message->
<INPUT TYPE="TEXT" VALUE="" id="getmsgid" SIZE="5">
<INPUT TYPE="Submit" Value="<=enter|" SIZE ="7">
<span id="displaymsg"></span>
</form>

<canvas id="dashChart" width="640"
 height="480"></canvas>
<br>
::::::::::::::
<br>
: <u>board info</u> : <span id="board_name"></span>
<br>
::::::::::::::
<br>
<div id="board_info"></div>
<br>
<br>
<div id="dash"></div>

<script src="moment-with-locales.min.js"></script>
<script src="Chart.min.js"></script>
<script src="fbbs-core-data-draw.js"></script>

<script>

String.prototype.hashCode = function(){
	var hash = 0;
	if (this.length == 0) return hash;
	for (i = 0; i < this.length; i++) {
		char = this.charCodeAt(i);
		hash = ((hash<<5)-hash)+char;
		hash = hash & hash; // Convert to 32bit integer
	}
	return hash;
}
var prev_cmd_val = document.getElementById("previous_command").innerText;
prev_cmd_val = prev_cmd_val.split(" ")[0];

function funPrefixes(prefix_length = 5) {
  var char_set = ["_", "_", "_", "_", "_", "_", "_", "_",
                  " ", " ", ".", "O", "0", "o"];

  var return_string = "";
  for (var i=0; i<prefix_length; i++) {
    return_string += char_set[Math.floor(Math.random() * char_set.length)];
  }
  return return_string;
}

function getTimeDiffColor(sec_diff) {
  var return_color = "rgba(100,0,0,0.2)";
  if (sec_diff < 600) {
    return_color = "rgba(0,0,200,0.8)";
  }
  else if (sec_diff < 3600) {
    return_color = "rgba(50,200,0,0.5)";
  }
  return return_color;
}

function getTimeDiffBorder(sec_diff) {
  var return_color = "rgba(100,0,0,0.8)";
  if (sec_diff < 600) {
    return_color = "rgba(0,0,200,0.2)";
  }
  else if (sec_diff < 3600) {
    return_color = "rgba(50,250,0,0.3)";
  }
  return return_color;
}

function getValueColor(str) {
  var currentColor = "rgba(0, 0, 255, 0.8)";
  if (!isNaN(parseFloat(str)) && (isFinite(str))) {
    if (str < 50) {
      currentColor = "rgba(255, 0, 0, 0.2)";
    } else if (str < 75) {
      currentColor = "rgba(255, 255, 0, 0.5)";
    }
  }
  return currentColor;
}

var globalCharInstance = null;
 
function showDash(str_full) {
  var xhttp;
  var str_trim = str_full.trim();
  var str = str_trim.split(" ")[0];
  if (str.length == 0) {
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var ctx = document.getElementById("dashChart");
      var dashHtml = "";
      var label_array = [];
      var label_sym_array = [];
      var data_array = [];
      var label_locations = [];
      var color_array = [];
      var color_label_array = [];
      var min_timestamp = new Date().getTime();
      var max_timestamp = min_timestamp;
      var current_time = min_timestamp/1000;
      var previous_time = current_time;

      var dataTransformDraw = new FBBSDataDraw(ctx, str);
      dataTransformDraw.processDataDraw(this.responseText);
      return;
    }
  }
  xhttp.open("POST", "fbbs-api.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("command="+str);

  var xhttp_dashinfo;
  xhttp_dashinfo = new XMLHttpRequest();
  xhttp_dashinfo.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var current_time = (new Date()).getTime();
      try {
        var jsonresponseparsed = JSON.parse(this.responseText);
      } catch(err) {
        return;
      }
      if (jsonresponseparsed == undefined ||
          jsonresponseparsed.value == undefined) return;
      var jsonresponseobj = jsonresponseparsed.value[0];
      document.getElementById("board_info").innerHTML = "";
      Object.keys(jsonresponseobj).forEach(function(key,index) {
        var array_obj = jsonresponseobj[key];
        var entry_obj = new Object();
        for (var i=0; i < array_obj.length; i++) {
          var keyval_obj = array_obj[i];
          Object.keys(keyval_obj).forEach(function(key,index) {
            Object.keys(keyval_obj).forEach(function(id,idx) {
                entry_obj[id] = keyval_obj[id];
              });
          });
        }
        var entry_output = msgValue(entry_obj);
        document.getElementById("board_info").innerHTML += entry_output + "<br>";
      });
    }
  }

  xhttp_dashinfo.open("POST", "fbbs-api.php", true);
  xhttp_dashinfo.setRequestHeader("Content-type",
                                  "application/x-www-form-urlencoded");
  xhttp_dashinfo.send("command="+str+" @");

  document.getElementById("board_name").textContent = str;
}

if (prev_cmd_val) {
  showDash(prev_cmd_val);
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

  var dashName = document.getElementById("command").value;
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
    var commandString = "command="+dashName+" [<?=$username?>] "+data.value;
    xhttp_dashinfo.send(commandString);
    document.getElementById("message").value = "";
  }

  return false;
}

var formElementMsg = document.getElementById("postmsg");
if (formElementMsg.attachEvent) {
  formElementMsg.attachEvent("submit", capturePostEnter);
}
else {
  formElementMsg.addEventListener("submit", capturePostEnter);
}


function captureGetMsgEnter(e) {
  if (e.preventDefault) e.preventDefault();

  var dashName = document.getElementById("command").value;
  var xhttp_mdashinfo;
  xhttp_mdashinfo = new XMLHttpRequest();
  xhttp_mdashinfo.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("displaymsg").innerHTML = "";
      var current_time = (new Date()).getTime()/1000;
      try {
        var jsonresponseparsed = JSON.parse(this.responseText);
      } catch(err) {
        return;
      }
      if (jsonresponseparsed == undefined ||
          jsonresponseparsed.value == undefined) return;
      var jsonresponseobj = jsonresponseparsed.value;
      Object.keys(jsonresponseobj).forEach(function(key,index) {
        var array_obj = jsonresponseobj[key];
        var entry_obj = new Object();
        for (var i=0; i < array_obj.length; i++) {
          var keyval_obj = array_obj[i];
          Object.keys(keyval_obj).forEach(function(key,index) {
            Object.keys(keyval_obj).forEach(function(id,idx) {
                entry_obj[id] = keyval_obj[id];
              });
          });
        }
        var entry_time = parseInt(msgTimestamp(entry_obj));
        var timestamp_diff = (entry_time - current_time);
        var new_data_entry = Math.round((timestamp_diff/60)*10)/10; // In minutes
        var entry_output = msgValue(entry_obj)+" [minsago("+new_data_entry+"]";

        document.getElementById("displaymsg").innerHTML += entry_output + "<br>";
      });
    }
  }

  var msgId = document.getElementById("getmsgid").value.trim()
  xhttp_mdashinfo.open("POST", "fbbs-api.php", true);
  xhttp_mdashinfo.setRequestHeader("Content-type",
                                  "application/x-www-form-urlencoded");
  xhttp_mdashinfo.send("command="+dashName+" @"+msgId);

  return false;
}

var formGetMsg = document.getElementById("getmsg");
if (formGetMsg.attachEvent) {
  formGetMsg.attachEvent("submit", captureGetMsgEnter);
}
else {
  formGetMsg.addEventListener("submit", captureGetMsgEnter);
}

var dashUpdater = setInterval(updateDash, 5000);

</script>

</body>
</html>



