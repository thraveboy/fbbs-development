<?php
  $user_attr_fields = [];
  $user_attr_fields['CAN_READ'] = "can_read";
  $user_attr_fields['CAN_WRITE'] = "can_write";
  $user_attr_fields['CREATE_TABLE'] = "create_table";
  $user_attr_fields['IS_ADMIN'] = "is_admin";

  echo $user_attr_fields['CAN_READ'] . PHP_EOL;

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

  function get_user_attributes($username="") {
   $user_attributes = [];
   $user_id = 0;
   if ($username != "") {
      $fdbuser = new FDBUSER();
      if (!$fdbuser) {
        echo $fdbuser->error;
      }
      $clean_username = $fdbuser->real_escape_string($username);
      $user_attributes['username'] = $clean_username;
      $userid_query = "SELECT id FROM users where username = '" .
        $clean_username . "'";
      $userid_result = $fdbuser->query($userid_query);
      if ($userid_result->num_rows > 0) {
        $userid_array = $userid_result->fetch_assoc();
        $user_id = $userid_array['id'];
        $user_attributes['user_id'] = $user_id;
        if ($user_id > 0) {
          $userattr_query = "SELECT * from user_attr WHERE userid = " .
            $user_id;
          $userattr_result = $fdbuser->query($userattr_query);
          if ($userattr_result->num_rows > 0) {
            while ($userattr_row = $userattr_result->fetch_assoc()) {
              $attr_id = $userattr_row['id'];
              $attr_name = $userattr_row['attribute'];
              $attr_value = $userattr_row['value'];
              $attr_usermod = $userattr_row['user_mod'];
              $user_attributes[$attr_id] = array(
                  "attribute" => $attr_name,
                  "value" =>  $attr_value,
                  "user_mod" => $attr_usermod
                );
            }
          }
        }
      }
    }
    return $user_attributes;
  }

?>
