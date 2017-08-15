<?php
  class FDBUSER extends mysqli
  {
    private $fbbs_servername = "localhost";
    private $fbbs_username = "root";
    private $fbbs_password = "bbs";
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

  function authorize_user() {
    $userauthorized = FALSE;
    $username = $_COOKIE['username'];
    $token = $_COOKIE['authToken'];
    if (($username != "") && ($token != "")) {
      $fdbuser = new FDBUSER();
      if (!$fdbuser) {
        echo $fdbuser->error;
      }
      $username = $fdbuser->real_escape_string($username);
      $auth_query = 'SELECT token FROM auth_tokens where username = "' .
                    $username . '"';
      $auth_result = $fdbuser->query($auth_query);
      if ($auth_result->num_rows > 0) {
        $auth_array = $auth_result->fetch_assoc();
        $auth_encoded = $auth_array['token'];
        if (!empty($auth_encoded)) {
          if (password_verify($token, $auth_encoded)) {
            $userauthorized = TRUE;
          }
        }
      }
    }
    if ($userauthorized) {
      $request_time = time();
      $auth_access_insert = 'INSERT INTO user_auth_log ' .
                            '(username, token, timestamp) VALUES ' .
                            '("' . $username . '", "' . $auth_encoded . '", '
                            . $request_time .')';
      $fdbuser->query($auth_access_insert);
      return $username;
    }
    else {
      header("Location: index.php");
    }
    return FALSE;
  }

  function current_user_id() {
    $return_id = "-1";
    $username = authorize_user();
    if ($username) {
      $fdbuser = new  FDBUSER();
      if (!$fdbuser) {
       echo $fdbuser->error;
      }
      else {
        $user_id_query = 'SELECT id, username FROM "users" where username = "'.
                         $username . '" LIMIT 1';
        $id_result = $fdbuser->query($user_id_query);
        if ($id_result->num_rows > 0) {
          $id_result_array = $id_result->fetch_assoc();
          if ($id_result_array) {
            $return_id = $id_result_array["id"];
          }
        }
      }
    }
    return $return_id;
  }

  function last_auth_user() {
    $return_string = "";
    $fdbuser = new FDBUSER();
    if (!$fdbuser) {
     echo $fdbuser->error;
    }
    else {
      $cleanusername = "";
      if (!empty($_COOKIE['username'])) {
        $cleanusername = $fdbuser->real_escape_string($_COOKIE['username']);
      }
      $last_auth_query = 'SELECT username, timestamp FROM user_auth_log ' .
                         'WHERE username != "'. $cleanusername .
                         '" ORDER BY timestamp DESC LIMIT 1';
      $last_auth_result = $fdbuser->query($last_auth_query);
      if ($last_auth_result->num_rows > 0) {
        $result_array = $last_auth_result->fetch_assoc();
        $return_string = $result_array['username'];
      }
    }
    return $return_string;
  }

?>
