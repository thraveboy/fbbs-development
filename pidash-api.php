<?php
  require_once 'mysql-login.php';

  $_LOCAL_API_CALLS = 0;
  $_COOKIE['username'] = '';

  $usernamepost = $_POST["username"];
  $passwordpost = $_POST["password"];
  $authtokenpost = $_POST["token"];

  // Open user FDB is the object for checking user database credentials.
  class apiFDB extends mysqli
  {
    private $fbbs_servername = "localhost";
    private $fbbs_username = "";
    private $fbbs_password = "";
    private $fbbs_database = "FBBSUSER";

    function __construct()
    {
      $this->mysql_login = new MySqlLogin();
      $this->fbbs_username = $this->mysql_login->mysql_user;
      $this->fbbs_password = $this->mysql_login->mysql_password;
      error_log("username: " . $this->fbbs_username);
      error_log("password: " . $this->fbbs_password);
      parent::__construct($this->fbbs_servername, $this->fbbs_username,
                          $this->fbbs_password, $this->fbbs_database);
      if ($this->connect_error) {
          error_log("Failed to connect to FBBSUSER: " . $this->connect_error);
      }
    }
  }

  // JSON encoder for returning data
  class apiJsonEncoder
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

  $outputObject = new apiJsonEncoder();

  // Open user database if error, then post error and exit
  $db = new apiFDB();
  if (!$db) {
    $outputObject->append('"error": "' . $db->lastErrorMsg()) . '"';
    $outputObject->send();
    exit;
  }

  // Clean username field
  $cleanusername = $db->real_escape_string($usernamepost);

  // Check to see if username already exists
  $userfound = FALSE;
  $user_info_query = 'SELECT * FROM users WHERE username = "' .
                      $cleanusername . '" ORDER BY timestamp DESC LIMIT 1';
  $results_user_info = $db->query($user_info_query);

  if (!empty($results_user_info) && ($results_user_info->num_rows > 0)) {
    $user_info_array = $results_user_info->fetch_assoc();
    if ($user_info_array) {
      $retrievedusername = $user_info_array["username"];
      $retrievedpassword = $user_info_array["password"];
    }
    $auth_query = 'SELECT token FROM auth_tokens where username = "' .
                  $cleanusername . '"';
    $auth_result = $db->query($auth_query);
    if (!empty($auth_result) && ($auth_result->num_rows > 0)) {
      $auth_array = $auth_result->fetch_assoc();
      $retrievedtoken = $auth_array['token'];
    }
  }
  // Check to see if username and auth_token passed, and if correct.
  if (empty($usernamepost)) {
    $outputObject->append('"error": "' . "no username specified" . '"');
    $outputObject->send();
    exit;
  }

  if (empty($authtokenpost) && empty($passwordpost)) {
    $outputObject->append('"error": "' . "no password or token specified".'"');
    $outputObject->send();
    exit;
  }

  if (!empty($usernamepost) && !empty($authtokenpost) &&
      !empty($retrievedtoken)) {
    if (!password_verify($authtokenpost, $retrievedtoken)) {
      $outputObject->append('"error": "' . "auth token does not match".'"');
      $outputObject->send();
      exit;
    }
  }

  if (!empty($usernamepost) && !empty($passwordpost) &&
      !empty($retrievedpassword)) {
    $passwordposthashed = password_hash($passwordpost, PASSWORD_DEFAULT);
    if (!password_verify($passwordpost, $retrievedpassword)) {
      $outputObject->append('"error": "' . "password does not match".'"');
      $outputObject->send();
      exit;
    }
  }

  if (!empty($usernamepost) && !empty($passwordpost) &&
      empty($retrievedpassword) && empty($retrievedusername)) {
    $passwordhashed = password_hash($passwordpost, PASSWORD_DEFAULT);
    $request_time = time();
    $create_query = 'INSERT INTO users (username, password, timestamp) ' .
                    'VALUES ("'. $cleanusername . '", "' .
                    $passwordhashed . '", '. $request_time . ')';
    $db->query($create_query);
    $outputObject->append('"user_created": "' . $usernamepost . '"');
    $outputObject->send();
    exit;
  }

   if (!empty($usernamepost) && !empty($passwordpost) &&
      !empty($retrievedpassword) && !empty($retrievedusername)) {

    $auth_token = bin2hex(openssl_random_pseudo_bytes(16));
    $auth_encode = password_hash($auth_token, PASSWORD_DEFAULT);
    $request_time = time();
    $auth_insert_query = 'REPLACE INTO auth_tokens ' .
                         '(username, token, expire, timestamp) ' .
                         'VALUES ("' . $retrievedusername . '", "'.
                         $auth_encode . '", 0, ' . $request_time .
                           ')';
    $db->query($auth_insert_query);
    $outputObject->append('"token": "' . $auth_token . '"');
    $outputObject->send();
    exit;
  }

  if (!empty($usernamepost) && !empty($authtokenpost)) {
    if (!password_verify($authtokenpost, $retrievedtoken)) {
      $outputObject->append('"error": "' . "auth token incorrect" . '"');
      $outputObject->send();
      exit;
    }
    $_COOKIE['username'] = $cleanusername;
    $db->close();
    include 'fbbs-api.php';
  }
?>
