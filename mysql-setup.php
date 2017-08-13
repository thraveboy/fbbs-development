<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FBBS";
$dbname_user = "FBBSUSER";
$dbname_private = "FBBSPRIVATE";

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysql_connect_error());
}

echo "Connected to FBBS successfully\n";

// Create DB
$sql = "CREATE DATABASE FBBS";
if ($conn->query($sql) == TRUE) {
    echo "Database FBBS created successfully\n";
} else {
    echo "Error creating database FBBS: " . $conn->error . "\n";
}

$sql = "CREATE DATABASE FBBSUSER";
if ($conn->query($sql) == TRUE) {
    echo "Database FBBSUSER created successfully\n";
} else {
    echo "Error creating database FBBSUSER: " . $conn->error . "\n";
}

$sql = "CREATE DATABASE FBBSPRIVATE";
if ($conn->query($sql) == TRUE) {
    echo "Database FBBSPRIVATE created successfully\n";
} else {
    echo "Error creating database FBBSPRIVATE: " . $conn->error . "\n";
}

mysqli_close($conn);

$conn_db = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn_db) {
    die("Connection failed: " . mysql_connect_error());
}

echo "Connected successfully\n";

// Create test table
$sql = "CREATE TABLE test (
id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
ip VARCHAR(32) NOT NULL,
value BLOB,
timetamp BIGINT UNSIGNED
)";

if ($conn_db->query($sql) == TRUE) {
    echo "Table test created successfully" . "\n";
} else {
    echo "Error creating table test: " . $conn_db->error . "\n";
}

mysqli_close($conn_db);

$conn_private = mysqli_connect($servername, $username, $password,
                               $dbname_private);

// Check connection
if (!$conn_private) {
    die("Connection failed: " . mysql_connect_error());
}

echo "Connected successfully\n";

// Create test table
$sql = "CREATE TABLE _help (
id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
ip VARCHAR(30) NOT NULL,
value BLOB,
timetamp BIGINT UNSIGNED
)";

if ($conn_private->query($sql) == TRUE) {
    echo "Table _help created successfully" . "\n";
} else {
    echo "Error creating table test: " . $conn_private->error . "\n";
}

mysqli_close($conn_private);


$conn_user = mysqli_connect($servername, $username, $password, $dbname_user);

if (!$conn_user) {
    die("Error connecting to FBBSUER: " . mysql_connect_error());
}

echo "Connected to FBBSUSER successfully\n";

$sql_user = "CREATE TABLE users (
id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(32) UNIQUE NOT NULL,
password VARCHAR(64) NOT NULL,
timestamp BIGINT UNSIGNED
)";

if ($conn_user->query($sql_user) == TRUE) {
    echo "Table users created successfully\n";
} else {
    echo "Error creating table users: " . $conn_user->error . "\n";
}

$sql_user = "CREATE TABLE auth_tokens (
username VARCHAR(32) NOT NULL PRIMARY KEY,
token VARCHAR(64) NOT NULL,
expire BIGINT UNSIGNED,
timestamp BIGINT UNSIGNED
)";

if ($conn_user->query($sql_user) == TRUE) {
    echo "Table auth_tokens created successfully\n";
} else {
    echo "Error creating table auth_tokens: " . $conn_user->error . "\n";
}

$sql_user = "CREATE TABLE user_auth_log (
id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(32) UNIQUE NOT NULL,
token VARCHAR(32) NOT NULL,
timestamp BIGINT UNSIGNED
)";

if ($conn_user->query($sql_user) == TRUE) {
    echo "Table user_auth_log created successfully\n";
} else {
    echo "Error creating table user_auth_log: " . $conn_user->error . "\n";
}


mysqli_close($conn_user);


?>
