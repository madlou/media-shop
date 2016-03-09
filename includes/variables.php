<?php
 //get url data
 $data_url_menu = htmlspecialchars($_GET["menu"], ENT_QUOTES);
 $data_url_gallery = htmlspecialchars($_GET["gallery"], ENT_QUOTES);
 $data_url_page = htmlspecialchars($_GET["page"], ENT_QUOTES);
 $data_url_media = htmlspecialchars($_GET["media"], ENT_QUOTES);
 $action = $_GET["action"];
 $value1 = htmlspecialchars(stripslashes($_GET["value1"]), ENT_QUOTES);
 $value2 = htmlspecialchars(stripslashes($_GET["value2"]), ENT_QUOTES); //used for autoplay
 $value3 = htmlspecialchars(stripslashes($_GET["value3"]), ENT_QUOTES);
 $value4 = stripslashes($_GET["value4"]);
 $value5 = htmlspecialchars(stripslashes($_GET["value5"]), ENT_QUOTES);
 $value6 = htmlspecialchars(stripslashes($_GET["value6"]), ENT_QUOTES);
 if(!($action.$value1.$value2.$value3.$value4.$value5)){
  $action = $_POST["action"];
  $value1 = htmlspecialchars(stripslashes($_POST["value1"]), ENT_QUOTES);
  $value2 = htmlspecialchars(stripslashes($_POST["value2"]), ENT_QUOTES);
  $value3 = htmlspecialchars(stripslashes($_POST["value3"]), ENT_QUOTES);
  $value4 = htmlspecialchars(stripslashes($_POST["value4"]), ENT_QUOTES);
 }

 //create site variables
 $site_nav="";
 $site_main="";
 $site_options="";
 $site_thumbnails="";
 $site_nav_admin="";
 $site_options_admin="";

 //get 'admin' table variables
 $select=mysql_query("SELECT * FROM `admin`;");
 while($row=mysql_fetch_array($select)) {
  $data_mysql_admin[$row[2]]=$row[1];
 }

 //get 'messages' table variables
 $select=mysql_query("SELECT * FROM `messages`;");
 while($row=mysql_fetch_array($select)){
  $data_messages[$row["number"]]=$row["description"];
 }

 //get 'messagesx' table variables
 $select=mysql_query("SELECT * FROM `messagesx`;");
 while($row=mysql_fetch_array($select)){
  $data_messagesx[$row["number"]]=$row["description"];
 }

 //set defaults
 if(!$data_url_page){
  $data_url_page=1;
 }
 if($data_url_menu==""){
  $data_url_menu = 1;
  $data_url_gallery = 1;
 }
 if($data_url_gallery==""&&$data_url_menu!=""){
  $result=mysql_query("SELECT * FROM `gallery` WHERE `menu` = '$data_url_menu'");
  $row=mysql_fetch_array($result);
  $data_url_gallery=$row[0];
 }
 if($data_mysql_admin["Auto Color Setting"]){
  $colour_r=hexdec(substr($data_mysql_admin["Auto Color Base"],0,2));
  $colour_g=hexdec(substr($data_mysql_admin["Auto Color Base"],2,2));
  $colour_b=hexdec(substr($data_mysql_admin["Auto Color Base"],4,2));
  $colour_r1="0.95";
  $colour_r2="1";
  $colour_r3="0.8";
  $colour_r4="0.6";
  $data_mysql_admin["Background Color"]=dechex(round($colour_r*$colour_r1)).dechex(round($colour_g*$colour_r1)).dechex(round($colour_b*$colour_r1));
  $data_mysql_admin["Selected Button Color"]=dechex(round($colour_r*$colour_r2)).dechex(round($colour_g*$colour_r2)).dechex(round($colour_b*$colour_r2));
  $data_mysql_admin["Button Color"]=dechex(round($colour_r*$colour_r3)).dechex(round($colour_g*$colour_r3)).dechex(round($colour_b*$colour_r3));
  $data_mysql_admin["Mouseover Button Color"]=dechex(round($colour_r*$colour_r4)).dechex(round($colour_g*$colour_r4)).dechex(round($colour_b*$colour_r4));
 }


?>