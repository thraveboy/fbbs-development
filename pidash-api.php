<?php
  $_LOCAL_API_CALLS = 1;
  $_COOKIE['username'] = '';

  $usernamepost = $_POST["username"];
  $passwordpost = $_POST["password"];
  $authtokenpost = $_POST["auth_token"];

  echo 'post username' . $usernamepost . "\n";
  echo 'post password' . $passwordpost . "\n";
  echo 'post token' . $authtokenpost . "\n";

  // Open user FDB is the object for checking user database credentials.
  class FDB extends SQLite3
  {
    function __construct()
    {
      $this->open('fbbs-user.db');
    }
  }

  // JSON encoder for returning data
  class jsonEncoder
  {
    private $_output_string = "";

    public function append($output) {
      $this->_output_string .= $output;
    }
    public function retrieve() {
      return '{' . $this->_output_string . '}';
    }
    public function send() {
      echo $this->retrieve();
    }
  }

  $outputObject = new jsonEncoder();

  // Open user database if error, then post error and exit
  $db = new FDB();
  if (!$db) {
    $outputObject->append("error: " . $db->lastErrorMsg());
    $outputObject->send();
    exit;
  }

  // Clean username field
  $cleanusername = $db->escapeString($usernamepost);

  // Check to see if username already exists
  $user_info_query = 'SELECT * FROM "users" WHERE username = "' .
                      $cleanusername . '" ORDER BY timestamp DESC LIMIT 1';
  $results_user_info = $db->query($user_info_query);
  $userfound = FALSE;

  if (!empty($results_user_info)) {
    $user_info_array = $results_user_info->fetchArray(SQLITE3_ASSOC);
    if ($user_info_array) {
      $retrievedusername = $user_info_array["username"];
      $retrievedpassword = $user_info_array["password"];
      $retrievedtoken = $user_info_array["token"];
      $retreivedtokenexpire = $user_info_array["expire"];
      echo 'username:' . $retrievedusername;
      echo "\n";
      echo 'password:' . $retrievedpassword;
      echo "\n";
      echo 'token:' . $retrievedtoken;
      echo "\n";
      echo 'token expire:' . $retrievedtokenexpire;
      echo "\n";
    }
  }
  // Check to see if username and auth_token passed, and if correct.

  if (empty($usernamepost)) {
    $outputObject->append("error: " . "no username specified");
    $outputObject->send();
    exit;
  }

  if (empty($authtokenpost) && empty($passwordpost)) {
    $outputObject->append("error: ". "no password or token specified");
    $outputObject->send();
    exit;
  }

  if (!empty($usernamepost) && !empty($authtokenpost) &&
      !empty($retrievedtoken)) {
    if (!password_verify($authtokenpost, $retrievedtoken)) {
      $outputObject->append("error: ". "auth token does not match");
      $outputObject->send();
      exit;
    }
  }

  if (!empty($usernamepost) && !empty($passwordpost) &&
      !empty($retrievedpassword)) {
    $passwordposthashed = password_hash($passwordpost, PASSWORD_DEFAULT);
    if (!password_verify($passwordpost, $retrievedpassword)) {
      
    }
  }

  echo 'api' . "\n";
?>
