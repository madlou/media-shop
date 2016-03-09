<?php

 //show message numbers if an administrator is logged on
 //N.B. You can't do this in site_setglobals.php because you don't know who the user is by that time.
 if($data_cookie_admin){
  foreach ($data_messages as $key => $value) {
   $data_messages[$key] = "[".$key."] ".$value;
  }
  foreach ($data_messagesx as $key => $value) {
   $data_messagesx[$key] = "[x".$key."] ".$value;
  }
  $data_mysql_admin["Font Size"]=10;
  $data_mysql_admin["Button Width"]=140;
  $data_mysql_admin["Page Width"]=$data_mysql_admin["Admin: Page Width"];
  $data_mysql_admin["Center Width"]=$data_mysql_admin["Page Width"]-400;
  $data_mysql_admin["Center Image"]="";
 }

 //Get data on how the media information is displayed
 $data_mysql_mediainfo_long=@mysql_query("SELECT `field`, `display` FROM `mediainfo` WHERE `version` = 'long' ORDER BY `number` ASC");
 $i=0;
 while($row=mysql_fetch_array($data_mysql_mediainfo_long)){
  $data_array_showfields_long[$i]=$row["field"];
  $data_array_showtitles_long[$i]=$row["display"];
  $i++;
 }
 $data_mysql_mediainfo_short=@mysql_query("SELECT `field`, `display` FROM `mediainfo` WHERE `version` = 'short' ORDER BY `number` ASC");
 $i=0;
 while($row=mysql_fetch_array($data_mysql_mediainfo_short)){
  $data_array_showfields_short[$i]=$row["field"];
  $data_array_showtitles_short[$i]=$row["display"];
  $i++;
 }

 //get mysql data for 'menu' (all), 'gallery' (all in selected menu), 'media' (all in selected gallery) and 'media' (for the selected)
 $data_mysql_menu_all=@mysql_query("SELECT * FROM `menu` ORDER BY `number` ASC");
 $data_mysql_gallery_menu=@mysql_query("SELECT * FROM `gallery` WHERE `menu` = '".$data_url_menu."' ORDER BY `number` ASC");
 if($action=="search"&&$value1){
  if($data_url_media){
   $data_mysql_media_selected=@mysql_query("SELECT * FROM `media` WHERE `number` = '".$data_url_media."' AND `priority` < '".$data_mysql_admin["Show Priority Less Than"]."'");
  } else {
   $data_mysql_media_selected=@mysql_query("SELECT * FROM `media` WHERE (`description` LIKE '%".$value1."%' OR `gallery` LIKE '%".$value1."%' OR `misc1` LIKE '%".$value1."%' OR `misc2` LIKE '%".$value1."%' OR `misc3` LIKE '%".$value1."%') AND `priority` < '".$data_mysql_admin["Show Priority Less Than"]."' ORDER BY ".$data_mysql_admin["Gallery Order"]." LIMIT 1");
  }
  $data_mysql_media_gallery=@mysql_query("SELECT * FROM `media` WHERE (`description` LIKE '%".$value1."%' OR `gallery` LIKE '%".$value1."%' OR `misc1` LIKE '%".$value1."%' OR `misc2` LIKE '%".$value1."%' OR `misc3` LIKE '%".$value1."%') AND `priority` < '".$data_mysql_admin["Show Priority Less Than"]."' AND `type` != 'txt' ORDER BY ".$data_mysql_admin["Gallery Order"]);
 } else {
  if($data_url_media){
   $data_mysql_media_selected=@mysql_query("SELECT * FROM `media` WHERE `number` = '".$data_url_media."' AND `priority` < '".$data_mysql_admin["Show Priority Less Than"]."'");
  } else {
   $data_mysql_media_selected=@mysql_query("SELECT * FROM `media` WHERE `gallery` = '".$data_url_gallery."' AND `priority` < '".$data_mysql_admin["Show Priority Less Than"]."' ORDER BY ".$data_mysql_admin["Gallery Order"]." LIMIT 1");
  }
  $data_mysql_media_gallery=@mysql_query("SELECT * FROM `media` WHERE `gallery` = '".$data_url_gallery."' AND `priority` < '".$data_mysql_admin["Show Priority Less Than"]."' ORDER BY ".$data_mysql_admin["Gallery Order"]);
 }
 function getorders($data_cookie_user){
  $data_mysql_orders=@mysql_query("SELECT o.code,o.quantity,p.price,p.description, o.number, o.status, p.location FROM orders o, media p WHERE p.number = o.code AND o.user='".$data_cookie_user."' AND (o.status='basket' or o.status='paid') AND o.date > '".date("Y-m-d G:i:s",time()-(24*60*60))."' ORDER BY o.number"); //UNIX_TIMESTAMP('".strtotime("-11 days")."')
  return $data_mysql_orders;
 }
 function checkpaid($data_cookie_user,$data_url_media){
  $data_mysql_checkpaid=@mysql_query("SELECT p.location, p.description FROM orders o, media p WHERE p.number = o.code AND o.user='".$data_cookie_user."' AND o.status='paid' AND p.number = '".$data_url_media."' AND o.date > '".date("Y-m-d G:i:s",time()-(24*60*60))."' ORDER BY o.number");
  $process_row=@mysql_fetch_array($data_mysql_checkpaid);
  return $process_row;
 }
?>