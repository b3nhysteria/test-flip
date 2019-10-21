<?php
include_once('../configuration/env.php');
$mysql_host = getenv('DB_HOST');
$mysql_database = getenv('DB_NAME');
$mysql_user = getenv('DB_USERNAME');
$mysql_password = getenv('DB_PASSWORD');
try {
    $db = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
    $query = file_get_contents("import.sql");
    $stmt = $db->prepare($query);
    if ($stmt->execute())
        echo "Success";
    else
        echo "Fail";
} catch (\Exception $err) {
    throw $err;
}
