<?php

  class DefaultPermissions
  {
    private $default_permissions = [
      ["attribute" =>"can_read","value" =>"Board, Ticker, TradeVolume","user_mod" => False],
      ["attribute" => "can_write", "value" => "Board", "user_mod" => False]
    ];
    function get_permissions() {
      return $this->default_permissions;
    }
  }

?>

