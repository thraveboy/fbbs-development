<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="fbbs-login-style.css">
</head>

<body>
<?php
  $_LOCAL_API_CALLS = 1;

  class FDB extends mysqli
  {
    private $fbbs_servername = "localhost";
    private $fbbs_username = "root";
    private $fbbs_password = "bbs";
    private $fbbs_database = "FBBSUSER";

    function __construct()
    {
      parent::__construct($this->fbbs_servername, $this->fbbs_username,
                          $this->fbbs_password, $this->fbbs_database);
      if ($this->connect_error) {
          error_log("Failed to connect to FBBSUSER: " . $this->connect_error);
      }
    }
  }

  $usernamepost = $_POST["username"];
  $passwordpost = $_POST["password"];
  $passwordagainpost = $_POST["password-again"];

  $username_emptyq = empty($usernamepost);
  $password_emptyq = empty($passwordpost);
  $passwordagain_emptyq = empty($passwordagainpost);

  if ($username_emptyq || $password_emptyq) {
    if ($username_emptyq) {
      echo '.....no username<br>';
    }
    if ($password_emptyq) {
      echo '...no password<br>';
    }
  }
  else {

    $db = new FDB();
    if(!$db){
      echo "Can not connect to DB\n";
    }
    $cleanusername = $db->real_escape_string($usernamepost);
    $user_info_query = 'SELECT * FROM users WHERE username = "' .
                        $cleanusername . '" ORDER BY timestamp DESC LIMIT 1';
    error_log("mysql query: " . $user_info_query . "\n");
    $results_user_info = $db->query($user_info_query);
    $userfound = FALSE;
    if ((!empty($results_user_info)) && ($results_user_info->num_rows > 0)) {
      $user_info_array = $results_user_info->fetch_assoc();
      if ($user_info_array) {
        $retrievedusername = $user_info_array["username"];
        $retrievedpassword = $user_info_array["password"];
        if (password_verify($passwordpost, $retrievedpassword)) {
          echo 'password matched...<br>';
          $auth_token = bin2hex(openssl_random_pseudo_bytes(16));
          echo 'token generated....<br>';
          $request_time = time();
          $auth_encode = password_hash($auth_token, PASSWORD_DEFAULT);
          $auth_insert_query = 'REPLACE INTO auth_tokens ' .
                               '(username, token, expire, timestamp) ' .
                               'VALUES ("' . $retrievedusername . '", "'.
                               $auth_encode . '", 0, ' . $request_time . ')';
          $db->query($auth_insert_query);
          echo '<div id="username" style="visibility: hidden">';
          echo $cleanusername;
          echo '</div>';
          echo '<div id="authToken" style="visibility: hidden">';
          echo $auth_token;
          echo '</div>';
        }
        else {
          echo ".........password didn't match";
        }

        $userfound = TRUE;
      }
    }
    if (!$userfound) {
      echo '.....username not found<br>';
    }
    if (!$userfound && !$passwordagain_emptyq) {
      echo 'attempting to create new account for ' . $usernamepost . '..<br>';
      $passwordhashed = password_hash($passwordpost, PASSWORD_DEFAULT);
      if (password_verify($passwordagainpost, $passwordhashed)) {
         $request_time = time();
         $create_query = 'INSERT INTO users (username, password, timestamp) ' .
                         'VALUES ("'. $cleanusername . '", "' .
                         $passwordhashed . '", ' . $request_time . ')';
         $db->query($create_query);
         echo 'created user account for ' . $cleanusername . '.....<br>';
      }
      else {
         echo '....password and password again did not match';
      }
    }
  }
?>
<script>
  var username = document.getElementById("username").innerHTML;
  var token = document.getElementById("authToken").innerHTML;

  if (username && token) {
    document.cookie = "username = " + username;
    document.cookie = "authToken = " + token;
    window.location = "fbbs-main.php";
  }
  document.write(username);
  document.write(token);
</script>
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
<FORM NAME="form1" METHOD="POST" ACTION="fbbs-login-submit.php">
username:
<br>
<INPUT TYPE="Text" VALUE="" id="username" NAME="username" SIZE="15" autofocus>
<br>
password:
<br>
<INPUT TYPE="Password" VALUE="" id="password" NAME="password" SIZE="15">
<br>
password again (if new user):
<br>
<INPUT TYPE="Password" VALUE="" id="password-again" NAME="password-again" SIZE="15">
<div class="userpassDiv" />
<br>
<input type="submit" value="\._enter_./" />
</FORM>
</p>

</body>
</html>
