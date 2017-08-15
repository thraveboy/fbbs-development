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

  function get_user_attributes($user_id=0) {
   $user_attributes = [];
   if ($user_id > 0) {
      $fdbuser = new FDBUSER();
      if (!$fdbuser) {
        echo $fdbuser->error;
      }
      $username_query = "SELECT username FROM users where id = " . $user_id;
      $username_result = $fdbuser->query($username_query);
      if ($username_result->num_rows > 0) {
        $username_array = $username_result->fetch_assoc();
        $username = $username_array['username'];
        $user_attributes['username'] = $username;
      }
    }
    return $user_attributes;
  }

?>
