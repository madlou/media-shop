<?php
 //user defined variables
 $loginName="";
 $password="";
 $server="";
 $databaseName="";
 //if you have multiple database servers, you can fill in the next section
 $loginName_2="";
 $password_2="";
 $server_2="";
 $databaseName_2="";
 // user defined variables end
 if (!$dbh=@mysql_connect($server, $loginName, $password)){
  $loginName=$loginName_2;
  $password=$password_2;
  $server=$server_2;
  $databaseName=$databaseName_2;
  if (!$dbh=@mysql_connect($server, $loginName, $password)){
   echo("Can't connect to the server!");
   exit();
  }
 }
 if (!mysql_select_db($databaseName)){
  echo("Can't access the database!");
  exit();
 }
?>