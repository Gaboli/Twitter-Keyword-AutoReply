<?php
//include configuration

     include 'config.php';

     $conn = mysql_connect(DB_HOST, DB_USERNAME, DB_PASS) or die ('Error connecting to mysql');
     mysql_select_db(DB_NAME) or die ('Unable to select database!');
?>
