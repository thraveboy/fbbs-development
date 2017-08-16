<?php

require_once "fbbs-user-admin-api.php";

$test_user_attributes = get_user_attributes("happy");

print_r($test_user_attributes);

$user_id = $test_user_attributes["user_id"];

echo "User id: " . $user_id . "\n";

$attr_id = add_user_attribute($user_id, "can_read", "board");

echo "Added attribute\n";

$test_user_attributes = get_user_attributes("happy");

print_r($test_user_attributes);

$remove_id = remove_user_attribute($attr_id);

echo "Removed attribute\n";

$test_user_attributes = get_user_attributes("happy");

print_r($test_user_attributes);

?>
