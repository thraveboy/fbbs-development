<!DOCTYPE html>
<html>
<head>
<style>
body, input {
    font-family: monospace;
    background-color: black;
    color: green;
    font-size: large;
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

  class FDB extends mysqli
  {
    private $fbbs_servername = "localhost";
    private $fbbs_username = "root";
    private $fbbs_password = "";
    private $fbbs_database = "FBBSUSER";

    function __construct()
    {
      parent::__construct($this->fbbs_servername, $this->fbbs_username,
                          $this->fbbs_password, $this->fbbs_database);
      if ($this->connect_errno) {
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
      echo $db->error;
    }
    $cleanusername = $db->real_escape_string($usernamepost);
    $user_info_query = 'SELECT * FROM users WHERE username = "' .
                        $cleanusername . '" ORDER BY timestamp DESC LIMIT 1';
    echo "mysql query: " . $user_info_query . "\n";
    $results_user_info = $db->query($user_info_query);
    $userfound = FALSE;
    if ($results_user_info->num_rows != 0) {
      $user_info_array = $results_user_info->fetch_assoc();
      if ($user_info_array) {
        $retrievedusername = $user_info_array["username"];
        $retrievedpassword = $user_info_array["password"];
        if (password_verify($passwordpost, $retrievedpassword)) {
          echo 'password matched...<br>';
          $auth_token = bin2hex(openssl_random_pseudo_bytes(16));
          echo 'token generated....<br>';
          $auth_encode = password_hash($auth_token, PASSWORD_DEFAULT);
          $auth_insert_query = 'REPLACE INTO auth_tokens ' .
                               '(username, token, expire) ' .
                               'VALUES ("' . $retrievedusername . '", "'.
                               $auth_encode . '")';
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
         $create_query = 'INSERT INTO users (username, password) ' .
                         'VALUES ("'. $cleanusername . '", "' .
                         $passwordhashed . '")';
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
|\______
<br>
--------->>>>
<br>
[o]----[o]---=>>>>>>>
<br>
----Velcome------->>>>>>>>>
<br>
*************to*************
<br>
** Fury's Fortress (fbbs) **
<br>
**------------------------**
<br>
****************************
<br>
<FORM NAME="form1" METHOD="POST" ACTION="fbbs-login-submit.php">
username:
<INPUT TYPE="Text" VALUE="" id="username" NAME="username" SIZE="40" autofocus>
<br>
password:
<INPUT TYPE="Password" VALUE="" id="password" NAME="password" SIZE="40">
<br>
password again (if new user):
<INPUT TYPE="Password" VALUE="" id="password-again" NAME="password-again" SIZE="40">
<div class="userpassDiv" />
<br>
<input type="submit" value="\._enter_./" />
</FORM>
</p>

</body>
</html>
