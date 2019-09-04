<?php



define('DB_SERVER', "localhost:8889");
define('DB_USERNAME', "root");
define('DB_PASSWORD', "root");
define('DB_DATABASE', 'TypeType');

/*
define('DB_SERVER', "127.0.0.1");
define('DB_USERNAME', "shawrtcn_root");
define('DB_PASSWORD', "19940208");
define('DB_DATABASE', 'shawrtcn_shawnsTypingApp');
*/

// Create connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);


?>