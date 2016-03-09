<?php
  
 /*
 $site_nav is the navigation menus
 */
  $process_shownumber="";
  while($data_mysql_menu_all_row=mysql_fetch_array($data_mysql_menu_all)) {
   $data_php_nav_gallery="";
   if($data_mysql_menu_all_row["number"]==$data_url_menu){
    $data_php_nav_menu_class="button-selected";
    $data_name_menu=$data_mysql_menu_all_row["name"];
    while($data_mysql_gallery_menu_row=mysql_fetch_array($data_mysql_gallery_menu)){
     if($data_cookie_admin||$data_mysql_gallery_menu_row["name"]!=$data_mysql_menu_all_row["name"]){ //don't show gallery if the menu has the same name
      if($data_mysql_gallery_menu_row["number"]==$data_url_gallery){
       $data_php_nav_gallery_class="button-selected";
       $data_name_gallery=$data_mysql_gallery_menu_row["name"];
      } else {
       $data_php_nav_gallery_class="button";
      }
      if($data_cookie_admin){
       $process_shownumber="G".$data_mysql_gallery_menu_row["number"].": ";
      }
      $data_php_nav_gallery .= button("<span class='hidethis'>&gt&gt</span>".$process_shownumber.$data_mysql_gallery_menu_row["name"], httplink($data_url_menu,$data_mysql_gallery_menu_row["number"]),$data_mysql_admin["Button Width"]-20,$data_php_nav_gallery_class);
     }
    }
    $process_nav_hidethis="<span class='hidethis'> || </span>\n";
   } else {
    $data_php_nav_menu_class="button";
    $process_nav_hidethis="";
   }
   if($data_cookie_admin){
    $process_shownumber="M".$data_mysql_menu_all_row["number"].": ";
   }
   $site_nav.=$process_nav_hidethis.button($process_shownumber.$data_mysql_menu_all_row["name"],httplink($data_mysql_menu_all_row["number"]),"",$data_php_nav_menu_class);
   $site_nav.=$data_php_nav_gallery.$process_nav_hidethis;
  }
  if($data_cookie_admin){
   $site_nav_admin.=button("Edit Media in Selected Gallery",httplink($data_url_menu,$data_url_gallery,"","","edittable","media","gallery",$data_url_gallery,"00011"));
   $site_nav_admin.=button("Edit Unassigned Media",httplink("","","","","edittable","media","gallery","noGallery","00011"));
   $site_nav_admin.="<hr>\n";
   $site_nav_admin.=button("Edit Menus",httplink("","","","","edittable","menu","","","11100"));
   $site_nav_admin.=button("Edit Galleries",httplink("","","","","edittable","gallery","","","11100"));
   $site_nav_admin.=button("Edit All Media",httplink("","","","","edittable","media","","","00011"));
   $site_nav_admin.="<hr>\n";
   $site_nav_admin.=button("Edit Admin",httplink("","","","","edittable","admin","","","11100"));
   $site_nav_admin.=button("Edit Media Info",httplink("","","","","edittable","mediainfo","","","11100"));
   $site_nav_admin.=button("Edit Messages",httplink("","","","","edittable","messages","","","00000"));
   $site_nav_admin.=button("Edit Long Messages",httplink("","","","","edittable","messagesx","","","00000"));
   $site_nav_admin.=button("Edit Orders",httplink("","","","","edittable","orders","","","10000"));
   $site_nav_admin.=button("Edit Paypal",httplink("","","","","edittable","paypal","","","10000"));
   $site_nav_admin.=button("Edit Structure",httplink("","","","","edittable","structure","","","00000"));
   $site_nav_admin.=button("Edit Table Setup",httplink("","","","","edittable","tablesetup","","","11100"));
   $site_nav_admin.=button("Edit Users",httplink("","","","","edittable","users","","","10000"));
   $site_nav_admin.="<hr>\n";
   $site_nav_admin.=button("Renumber Table","#","","button","renumbertable()");
   $site_nav_admin.=button("Check Uploads",httplink("","","","","checkuploads"));
  }
 /*
 $site_main contains the main information
 */
 switch($action){
  case "login":
  case "logout":
   $site_main.=$loginhtml;
   $action="stopload";
  break;
  case "createrecords";
   if($data_cookie_admin){
    include("includes/admin_functions.php");
   $site_main.="<h2>Load New Records</h2>";
   if($value1){
    $temp_rec=$value1-1;
    $temp_ajax="10::Done";
   } else {
    $temp_rec=$data_mysql_admin["Total Records"];
   }
   $site_main.=createrecords($temp_rec,$data_mysql_admin["MP3 Sample Length"],$data_mysql_admin["MP3 Bitrate"]);
   }
  if($temp_ajax){
   echo($temp_ajax);
   exit();
  }
  $action="stopload";
  break; 
  case "checkstructure";
   if($data_cookie_admin){
    include("includes/admin_functions.php");
    checkstructure();
   }
   $action="stopload";
  break; 
  case "checkuploads";
   if($data_cookie_admin){
    include("includes/admin_functions.php");
    $site_main.=checkuploads($value1,$value2);
   }
   $action="stopload";
  break; 
  case "email":
   $data_email_from=$value1;
   $data_email_subject=$value2;
   $data_email_body=$value3;
   $data_email_emailto=$data_mysql_admin["Web Email"];
   $message="";
   if ($data_email_from&&$data_email_subject&&$data_email_body){
    if (preg_match('/(%0A|%0D|\\n+|\\r+)/i', $data_email_subject)) {
     $message.="<p>".$data_messages[29]."</p>";
    }
    if (!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $data_email_from)) {
     $message.="<p>".$data_messages[30]."</p>";
    }
    if (preg_match('/(%0A|%0D|\\n+|\\r+)(content-type:|to:|cc:|bcc:)/i', $data_email_body)) {
     $message.="<p>".$data_messages[31]."</p>";
    }
    if(!$message){
     $headers = sprintf("From: %s\r\n", $data_email_from);
     if (sendMail($data_email_emailto, $data_email_subject . " - " . $data_email_from, $data_email_body, $data_email_from)){
      $message.="<p>".$data_messages[32]."</p>";
     } else {
      $message.="<p>".$data_messages[33]."</p>";
     }
    }
   } else {
    $message.="<p>".$data_messages[34]."</p>";
   }
   $site_main.="<div class='message'>".$message."</div>";
   $action="stopload";
  break;
  case "tellafriend":
   $data_email_from=$value1;
   $data_email_subject="Take a look at this site...";
   $data_email_body=$value3.",\n\nI've found this cool site Click on the Link: http://www.magnetrecords.co.uk and have a look\n\nPlease forward on.\n\n".$value2;
   $data_email_emailto=$value4;
   $message="";
   if ($data_email_from&&$data_email_subject&&$data_email_body){
    if (preg_match('/(%0A|%0D|\\n+|\\r+)/i', $data_email_subject)) {
     $message.="<p>".$data_messages[29]."</p>";
    }
    if (!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $data_email_from)) {
     $message.="<p>".$data_messages[30]."</p>";
    }
    if (preg_match('/(%0A|%0D|\\n+|\\r+)(content-type:|to:|cc:|bcc:)/i', $data_email_body)) {
     $message.="<p>".$data_messages[31]."</p>";
    }
    if(!$message){
     if (sendMail($data_email_emailto, $data_email_subject, $data_email_body, $data_email_from)){
      $message.="<p>".$data_messages[32]."</p>";
     } else {
      $message.="<p>".$data_messages[33]."</p>";
     }
    }
   } else {
    $message.="<p>".$data_messages[34]."</p>";
   }
   $site_main.="<div class='message'>".$message."</div>";
   $action="stopload";
  break;
  case "downloadfile":
   if($data_cookie_user){
    $data_php_downloadfile=checkpaid($data_cookie_user, $data_url_media);
    if($data_php_downloadfile["location"]&&$data_php_downloadfile["description"]){
     $process_file=substr(strrchr($data_php_downloadfile["location"], "/"), 1);
     $process_filename=$data_php_downloadfile["description"];
     // $update=mysql_query("UPDATE `orders` SET `date` = '".date("Y-m-d G:i:s",time()-(24*60*60)+(60*60))."' WHERE `user` LIKE '".$data_cookie_user."' AND `code` = '".$data_url_media."';");
     $update=mysql_query("UPDATE `orders` SET `date` = '".date("Y-m-d G:i:s",time()-(24*60*60))."' WHERE `user` LIKE '".$data_cookie_user."' AND `code` = '".$data_url_media."';");
     $site_main.=filedownload($process_file,$process_filename);
    } else {
     $site_main.="<div class='message'><p>".$data_messages[35]."</p><p>".$data_messages[36]."</p></div>";
    }
   } else {
    $site_main.="<div class='message'>".$data_messages[37]."</div>";
   }
   $action="stopload";
  break;
  case "viewpurchases":
   if($data_cookie_user){
    $data_mysql_orders=getorders($data_cookie_user);
    $site_main.="<h3>".$data_messages[38]."</h3>";
    $site_main.="<div class='message'>".$data_messagesx[4]."</div>";
    $site_main.="<table>\n";
    $site_main.="<tr>\n";
    $site_main.="<td class='tableheader'>".$data_messages[39]."</td>\n";
    $site_main.="<td class='tableheader'>".$data_messages[40]."</td>\n";
    $site_main.="<td class='tableheader'>".$data_messages[41]."</td>\n";
    $site_main.="<td class='tableheader'>".$data_messages[42]."</td>\n";
    $site_main.="<td class='noborder'>&nbsp</td>\n";
    $site_main.="</tr>\n";
    while($row=mysql_fetch_array($data_mysql_orders)) {
     if($row[5]=="paid"){
	  $row[5]=$data_messages[43];
	 }
     if($row[5]=="basket"){
	  $row[5]=$data_messages[44];
	 }
     $site_main.="<tr>\n";
     $site_main.="<td class='tablerow'>$row[5]</td>\n";
     $site_main.="<td class='tablerow'>$row[3]</td>";
     $site_main.="<td class='tablerow'>$row[1]</td>";
     $site_main.="<td class='tablerow'>&pound;$row[2]</td>\n";
     if($row[6]){
      if($row[5]==$data_messages[43]){
       $temp=button($data_messages[45], httplink("","","",$row[0],"downloadfile"),"auto", "button");
      } else {
       $temp="&nbsp";
      }
     } else {
       $temp=$data_messages[46];
     }
     $site_main.="<td class='noborder'>$temp</td>\n";
     $site_main.="</tr>\n";
    }
    $site_main.="</table>\n";
   } else {
    $site_main.=$data_messages[47];
   }
   $action="stopload";
  break;
  case "register":
   $site_main.="<h1>".$data_messages[8]."</h1>\n";
   $site_main.="<div class='message'>\n";
   $site_main.="<p>".$data_messagesx[1]."</p>";
   $site_main.="</div>\n";
   $site_main.="<div class='message'>\n";
   $site_main.="<h3>".$data_messages[9]."</h3>";
   $site_main.="<p>".$data_messagesx[2]."</p>";
   $site_main.="</div>\n";
   $site_main.="<div class='message'>\n";
   $site_main.="<form method='post' action='index.php' style='margin-top:8px'>\n";
   $site_main.="<table align='center'>\n";
   $site_main.="<tr>\n";
   $site_main.="<td><input name='action' type='hidden' value='newuser'>".$data_messages[10]."</td><td><input name='value1' class='input'></td>\n";
   $site_main.="</tr><tr>\n";
   $site_main.="<td>".$data_messages[11]."</td><td><input name='value2' class='input'></td>\n";
   $site_main.="</tr><tr>\n";
   $site_main.="<td>".$data_messages[12]."</td><td><input name='value3' type='password' class='input'></td>\n";
   $site_main.="</tr><tr>\n";
   $site_main.="<td colspan='2'></td>\n";
   $site_main.="</tr><tr>\n";
   $site_main.="<td colspan='2' align='center'><input type='submit' value='".$data_messages[13]."' class='button'></td>\n";
   $site_main.="</tr>\n";
   $site_main.="</table>\n";	
   $site_main.="</form>\n";
   $site_main.="</div>\n";
   $action="stopload";
  break;
  case "emaildetails":
   $site_main.="<h1>".$data_messages[84]."</h1>\n";
   $site_main.="<div class='message'>\n";
   $site_main.="<form method='post' action='index.php' style='margin-top:8px'>\n";
   $site_main.="<table align='center'>\n";
   $site_main.="<tr>\n";
   $site_main.="<td><input name='action' type='hidden' value='senddetails'>".$data_messages[10]."</td><td><input name='value1' class='input'></td>\n";
   $site_main.="</tr><tr>\n";
   $site_main.="<td colspan='2' align='center'><input type='submit' value='".$data_messages[7]."' class='button'></td>\n";
   $site_main.="</tr>\n";
   $site_main.="</table>\n";	
   $site_main.="</form>\n";
   $site_main.="</div>\n";
   $action="stopload";
  break;
  case "senddetails":
   $select=mysql_query("SELECT `name`, `password` FROM `users` WHERE `email` LIKE '".$value1."';");
   $row=mysql_fetch_array($select);
   if(!$row[0]){
    $site_main.="<div class='message'>".$data_messages[85]."</div>\n";
    $action="stopload";
    break;
   }
   $data_email_from=$data_mysql_admin["Web Email"];
   $data_email_subject="Your Login Details";
   $data_email_emailto=$value1;
   $data_email_body=$data_messages[5].": ".$row[0]."\n".$data_messages[6].": ".$row[1]."\n";
   $headers = sprintf("From: %s\r\n", $data_email_from);
   @sendMail($data_email_emailto, $data_email_subject, $data_email_body, $data_email_from);
   $site_main.="<div class='message'>".$data_messages[86]."</div>\n";
   $action="stopload";
  break;
  case "newuser":
   if($value1&&$value2&&$value3){
    $query=mysql_fetch_array(mysql_query("SELECT `name` FROM `users` WHERE `name` LIKE '".$value2."';"));
    if($query){
     $site_main.="<div class='message'>".$data_messages[48]."</div>\n";
     $site_main.=button($data_messages[49],httplink("","","","",register), "", "button");
     break;
    }
    $query=mysql_fetch_array(mysql_query("SELECT `email` FROM `users` WHERE `email` LIKE '".$value1."';"));
    if($query){
     $site_main.="<div class='message'>".$data_messagesx[3]."</div>\n";
     $site_main.=button($data_messages[49],httplink("","","","",register), "", "button");
     break;
    }
    //$insert = @mysql_query("INSERT INTO `users` VALUES (DEFAULT, '$value2','$value3','$value1','10');");
    $insert = @mysql_query("INSERT INTO `users` VALUES (DEFAULT, '$value2','$value3','$value1','10',DEFAULT);");
    $mail = @sendMail($value1,"Thank you for registering at ".$data_mysql_admin["Title"]."!","Your user name is: $value2\n\nYour password is: $value3");
    $site_main.="<h3>".$data_messages[50]."</h3>\n";
    $site_main.="<div class='message'>".$data_messages[51]."</div>\n";
    $site_main.=button($data_messages[1],httplink("","","","",login),"","button");
   } else {
    $site_main.="<h3>".$data_messages[52]."</h3>\n";
    $site_main.=button($data_messages[49],httplink("","","","",register),"","button");
   }
   $action="stopload";
  break;
  case "basketadd":
  if($data_cookie_user){
   $insert=mysql_query("INSERT INTO `orders` VALUES (DEFAULT,'".$data_cookie_user."','$data_url_media',1,'basket',DEFAULT);");
  } else {
   $site_main.="<div class='message'>".$data_messages[53]."</div>";
   $site_main.=displaylogin();
   $action="stopload";
  }
  break;
  case "basketremove":
  if($data_cookie_user){
   $update=mysql_query("UPDATE `orders` SET `status` = 'removed' WHERE `number` = '$value1' AND `user` = '".$data_cookie_user."';");
  } else {
   $site_main.="<div class='message'>".$data_messages[54]."</div>";
   $site_main.=displaylogin();
   $action="stopload";
  }
  break;
  case "edittable":
   if($data_cookie_admin&&$value1){
    include("includes/admin_functions.php");
    $process_query="";
    if($value2&&$value3){
     $process_query.=" WHERE `".$value2."`='".$value3."'";
    }
     $process_query="SELECT * FROM `".$value1."`".$process_query.";";
    $site_main.="<h3>".$process_query."</h3>";
    $site_main.=mysqltable($process_query,$value4);
    if($value1=="media"||$value1=="orders"||$value1=="paypal"){
     $site_options_admin="";
    }
   }
   $action="stopload";
  break;
  case "ajaxedit":
   if($data_cookie_admin&&$value1&&$value2&&$value3){
    $update=mysql_query("UPDATE `".$value1."` SET `".$value3."`='".$value4."' WHERE `number` = '".$value2."';");
    $select=mysql_query("SELECT `".$value3."` FROM `".$value1."` WHERE `number` = '".$value2."';");
    $row=mysql_fetch_array($select);
    if($value4==$row[0]){
     echo("1");
    } else {
     echo("0");
    }
    echo("::".$value1."::".$value2."::".$value3."::".$value4."::".$row[0]);
   }
   exit();
  break;
  case "edittxt":
   if($data_cookie_admin&&$value1){
    include("includes/admin_functions.php");
    $file="txt/".str_pad($value1, 5, "0", STR_PAD_LEFT).".txt";
    echo("2::".edittxt($file));
   }
   exit();
  break;
  case "savetxt":
   if($data_cookie_admin&&$value1&&$value2){
    $file = fopen($value1, 'w');
    $write = fwrite($file, html_entity_decode(urldecode(html_entity_decode($value2)),ENT_QUOTES));
    fclose($file);
    if($write){
     echo("3::".$write." bytes written to ".$value1);
    } else {
     echo("0::Error writing file.");
    }
   }
   exit();
  break;
  case "newthumb":
   if($data_cookie_admin&&$value1){
    include("includes/admin_functions.php");
    if(newthumb($value1)){
     echo("4::Ok");
    } else {
     echo("0::Failed to create a thumb - check that the file exists in ./originals/");
    }
   }
   exit();
  break;
  case "renumbertable":
   if($data_cookie_admin&&$value1){
    $select = mysql_query("SELECT * FROM `".$value1."` ORDER BY `number`");
    $row = mysql_fetch_array($select);
    if($row[0]<"1"){
     echo("0::Renumber table - Value less than 1, error!!!");
    } else {
     $count="0";
     while($row = mysql_fetch_array($select)){
      $count++;
      $update = "UPDATE `".$value1."` SET `number` = '$count' WHERE `number` = '$row[0]';";
      $update = mysql_query($update);
     }
    }
    echo("5::Renumbered - you need refresh screen");
   }
   exit();
  break;
  case "deleterow":
   if($data_cookie_admin&&$value1&&$value2){
    $delete=mysql_query("DELETE FROM `".$value1."` WHERE `number` = '".$value2."';");
    if($delete){
     echo("6::".$value2."::Deleted row ok!");
    } else {
     echo("0::Failed to delete row");
    }
   }
   exit();
  break;
  case "moveup":
   if($data_cookie_admin&&$value1&&$value2){
    if($value1=="gallery"){
     $childtable="media";
    }
    if($value1=="menu"){
     $childtable="gallery";
    }
    $update=@mysql_query("UPDATE `".$value1."` SET `number` = '99999' WHERE `number` = '".($value2-1)."';");
    $update=@mysql_query("UPDATE `".$value1."` SET `number` = '".($value2-1)."' WHERE `number` = '".($value2)."';");
    $update=@mysql_query("UPDATE `".$value1."` SET `number` = '".($value2)."' WHERE `number` = '99999';");
    $update=@mysql_query("UPDATE `".$childtable."` SET `".$value1."` = '99999' WHERE `".$value1."` = '".($value2-1)."';");
    $update=@mysql_query("UPDATE `".$childtable."` SET `".$value1."` = '".($value2-1)."' WHERE `".$value1."` = '".($value2)."';");
    $update=@mysql_query("UPDATE `".$childtable."` SET `".$value1."` = '".($value2)."' WHERE `".$value1."` = '99999';");
    if($update){
     echo("7::".$value2."::Moved row ok!");
    } else {
     echo("0::Failed to move row");
    }
   }
   exit();
  break;
  case "movedown":
   if($data_cookie_admin&&$value1&&$value2){
    if($value1=="gallery"){
     $childtable="media";
    }
    if($value1=="menu"){
     $childtable="gallery";
    }
    $update=@mysql_query("UPDATE `".$value1."` SET `number` = '99999' WHERE `number` = '".($value2+1)."';");
    $update=@mysql_query("UPDATE `".$value1."` SET `number` = '".($value2+1)."' WHERE `number` = '".($value2)."';");
    $update=@mysql_query("UPDATE `".$value1."` SET `number` = '".($value2)."' WHERE `number` = '99999';");
    $update=@mysql_query("UPDATE `".$childtable."` SET `".$value1."` = '99999' WHERE `".$value1."` = '".($value2+1)."';");
    $update=@mysql_query("UPDATE `".$childtable."` SET `".$value1."` = '".($value2+1)."' WHERE `".$value1."` = '".($value2)."';");
    $update=@mysql_query("UPDATE `".$childtable."` SET `".$value1."` = '".($value2)."' WHERE `".$value1."` = '99999';");
    if($update){
     echo("8::".$value2."::Moved row ok!");
    } else {
     echo("0::Failed to move row");
    }
   }
   exit();
  break;
  case "insert":
   if($data_cookie_admin&&$value1&&$value2){
    $value2=html_entity_decode(urldecode(html_entity_decode($value2)),ENT_QUOTES);
    $value2="'','".str_replace("::","','",$value2)."'";
    $insert=@mysql_query("INSERT INTO `".$value1."` VALUES (".$value2.");");
    if($insert){
     echo("9::".$value2);
    } else {
     echo("0::Failed to insert row");
    }
   }
   exit();
  break;
  default:
  break;
 }
 if($action!="stopload"){
  $data_php_media_selected=mysql_fetch_array($data_mysql_media_selected);
  $data_url_media=$data_php_media_selected["number"];
  $data_php_media_file=str_pad($data_url_media, 5, "0", STR_PAD_LEFT);
  switch($data_php_media_selected["type"]){
   case "jpg":
    if(file_exists("webpics/".$data_php_media_file.".jpg")){
     $site_main.="<div><img src='webpics/".$data_php_media_file.".jpg' id='image'></div>";
//     $site_main.="<div>".getmediainfo($data_php_media_selected)."</div>";
    } else {
     $site_main.=$data_messages[55];
    }
   break;
   case "vinyl":
   case "mp3":
    $site_main.="<h2>".$data_name_menu." - ".$data_name_gallery."</h2>";
    $process_file="m3u/".$data_php_media_file.".m3u";
    if(file_exists($process_file)){
     $site_main.="<div class='message'>";
     $temp_thumb="";
     $temp_thumb_width=0;
     $temp_thumb_a=file_exists("thumbnails/".$data_php_media_file."a.jpg");
     $temp_thumb_b=file_exists("thumbnails/".$data_php_media_file."b.jpg");
     if($temp_thumb_a){
      $temp_thumb.="<td style='width:150px;padding:0px;'><img src='thumbnails/".$data_php_media_file."a.jpg'></td>";  
      $temp_thumb_width+=151;
     }
     if($temp_thumb_a&&$temp_thumb_b){
      $temp_thumb.="<td class='noborder' style='width:9px;'>&nbsp</td>";  
      $temp_thumb_width+=10;
     }
     if($temp_thumb_b){
      $temp_thumb.="<td style='width:150px;padding:0px;'><img src='thumbnails/".$data_php_media_file."b.jpg'></td>";  
      $temp_thumb_width+=151;
     }
     if($temp_thumb_width){
    //$site_main.="<table style='width:".$temp_thumb_width."px;height:152px;'>";
      $site_main.="<table style='width:".$temp_thumb_width."px;'>";
      $site_main.="<tr>";  
      $site_main.=$temp_thumb;
      $site_main.="</tr>";
      $site_main.="</table>";
     }
     $site_main.="<h3>".$data_messages[56]."</h3>";
     $site_main.="<OBJECT ID='MediaPlayer1' CLASSID='CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95' CODEBASE='http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab# Version=5,1,52,701' STANDBY='Loading Microsoft Windows� Media Player components...' TYPE='application/x-oleobject' width='280' height='46'>\n";
     $site_main.="<param name='fileName' value='$process_file'>\n<param name='animationatStart' value='true'>\n<param name='transparentatStart' value='true'>\n";
     if($value2){$temp="true";} else {$temp="false";}
     $site_main.="<param name='autoStart' value='$temp'>\n<param name='showControls' value='true'>\n<param name='Volume' value='-300'>\n";
     if($value2){$temp="1";} else {$temp="0";} 
     $site_main.="<embed type='application/x-mplayer2' pluginspage='http://www.microsoft.com/Windows/MediaPlayer/' src='$process_file' name='MediaPlayer1' width=280 height=46 autostart=$temp showcontrols=1 volume=-300>\n";
     $site_main.="</OBJECT>\n";
     $site_main.="<p class='centered'>".$data_messages[57]."</p>";
     $site_main.="<div style='margin:10px'>".getmediainfo($data_php_media_selected,$data_array_showfields_long,$data_array_showtitles_long)."</div>";
     $site_main.="</div>";
    } else {
     $site_main.="<div class='message'>";
     $site_main.="<h3>".$data_messages[56]."</h3>";
     $site_main.="<p class='centered'>".$data_messages[58]."</p>";
     $site_main.="<div style='margin:10px'>".getmediainfo($data_php_media_selected,$data_array_showfields_long,$data_array_showtitles_long)."</div>";
     $site_main.="</div>";
    }
   break;
   case "txt":
    if(file_exists("txt/".$data_php_media_file.".txt")){
     $file = "txt/".$data_php_media_file.".txt";
     $handle = fopen($file, "r");
     $contents = fread($handle, filesize($file));
     fclose($handle);
     $site_main.="<div>".$contents."</div>";
    } else {
     $site_main.=$data_messages[59];
    }
   break;
   default:
    $site_main.="<div class='message'>".$data_messages[60]."</div>";
   break;
  }
  if($data_php_media_selected["price"]){
   $site_main.="<div class='message'>";   
   if(file_exists($data_php_media_selected["location"])){
    $site_main.="<p class='centered'>".$data_messages[61]." ".$data_php_media_selected["type"]." ".$data_messages[62].$data_php_media_selected["price"]."</p>";
    $site_main.=button($data_messages[63],httplink($data_url_menu,$data_url_gallery,$data_url_page,$data_url_media,"basketadd"),"","button");
   } else {
     if($data_php_media_selected["price"]=="0.00"){
      $site_main.="<p class='centered'>".$data_messages[64]." ".$data_php_media_selected["type"]." ".$data_messages[65]."</p>";
     } else {
      $site_main.="<p class='centered'>".$data_messages[66]." ".$data_php_media_selected["type"]." ".$data_messages[67].$data_php_media_selected["price"]." ".$data_messages[68]."</p>";
      $site_main.=button($data_messages[63],httplink($data_url_menu,$data_url_gallery,$data_url_page,$data_url_media,"basketadd"),"","button");
     }     
   }
   $site_main.="</div>";
  }
 }
 /*
 $site_options contains Login, Search and View Purchases
 */
 if($data_cookie_user){
  $site_options.=button($data_messages[27],httplink("","","","","logout"), "", "button");
  $site_options.=button($data_messages[28],httplink("","","","","viewpurchases"), "", "button");
 }
 if($data_cookie_admin||!$data_cookie_user){
  if($data_mysql_admin["Show Login"]){
   $site_options.=button($data_messages[1],httplink("","","","","login"), "", "button");
   $site_options.=button($data_messages[2],httplink("","","","","register"), "", "button");
  }
 }
 if($action=="search"&&!$value1){
  $site_options.="<form action='index.php'><input name='action' value='search' type='hidden'><div><input id='focushere' name='value1' class='input' value='".$data_messages[14]."' onfocus='this.select()'></div><div><input type='submit' value='".$data_messages[15]."' class='button'></div></form>";   
 } else {
  $site_options.=button($data_messages[3],httplink($data_url_menu,$data_url_gallery,$data_url_page,$data_url_media,"search"), "", "button");
 }
// $site_options.=button("Tell A Friend",httplink("","","",2), "", "button");
 /*
 $site_thumbnails is what creates the selection of files to choose from
 */
 $process_row_number=0;
 $process_col_number=0;
 $process_td_units=0;
 for ($i=0; $i<mysql_num_rows($data_mysql_media_gallery);$i++){
  $process_row=mysql_fetch_array($data_mysql_media_gallery);
  $data_php_media_info[$process_row["number"]]=$process_row;
  switch($process_row["type"]){
   case "jpg":
    $process_media_units=1;
   break;
   case "txt":
    if($process_row["description"]){
     $process_media_units=$data_mysql_admin["Thumbnail Columns"];
    } else {
     $process_media_units=0;
    }
   break;
   default:
    $process_media_units=$data_mysql_admin["Thumbnail Columns"];
   break;
  }
  $data_php_media_lines = array(array());
  if($process_media_units){
   if(($process_td_units+$process_media_units)<=$data_mysql_admin["Thumbnail Columns"]){
    $data_php_media_lines[$process_row_number][$process_col_number]=$process_row[0];
    $process_td_units=$process_td_units+$process_media_units;
    $process_col_number++;
   } else {
    $process_col_number=0;
    $process_td_units=$process_media_units;
    $process_row_number++;
    $data_php_media_lines[$process_row_number][$process_col_number]=$process_row[0];
    $process_col_number++;
   }
  }
 }
 @mysql_data_seek($data_mysql_media_gallery,0);
 if($data_url_page!=9999){
  $data_php_gallery_row_start=($data_url_page-1)*$data_mysql_admin["Thumbnail Rows"];
  $data_php_gallery_row_finish=($data_url_page-1)*$data_mysql_admin["Thumbnail Rows"]+$data_mysql_admin["Thumbnail Rows"]-1;
  $data_php_gallery_pages=ceil(count($data_php_media_lines)/$data_mysql_admin["Thumbnail Rows"]);
 } else {
  $data_php_gallery_row_start=0;
  $data_php_gallery_row_finish=9999;
  $data_php_gallery_pages=0;
 }
 $search_action="";
 $search_value1="";
 if($action=="search"&&$value1){
  $search_action="search";
  $search_value1=$value1;
  $site_thumbnails.="<h3>".$data_messages[69]."</h3>";
 }
 if(count($data_php_media_lines[0])){
  for ($i=$data_php_gallery_row_start;$i<$data_php_gallery_row_finish+1;$i++){
   for ($j=0;$j<count($data_php_media_lines[$i]);$j++){
    $process_media=$data_php_media_info[$data_php_media_lines[$i][$j]];
    if($data_url_media==$process_media["number"]){
     $buttonclass="media-selected";
    } else {
     $buttonclass="media";
    }
    $data_url_mediainfo=getmediainfo($process_media,$data_array_showfields_short,$data_array_showtitles_short);
    switch($process_media["type"]){
     case "jpg":
      $process_filename="thumbnails/".str_pad($process_media["number"],5,"0",STR_PAD_LEFT).".jpg";
      if(file_exists($process_filename)){
       $process_filecheck="<img class='thumbnail' src='".$process_filename."' alt='".$data_url_mediainfo."'>";
      } else {
       $process_filecheck="<div class='thumbnail'>".$data_messages[70]."</div>";
      }
      $site_thumbnails.="<a href='".httplink($data_url_menu,$data_url_gallery,$data_url_page,$process_media["number"],$search_action,$search_value1)."' title='$data_url_mediainfo'>".$process_filecheck."</a>";
     break;
     case "vinyl":
     case "mp3":
      $process_filename="m3u/".str_pad($process_media["number"],5,"0",STR_PAD_LEFT).".m3u";
      $process_original=$process_media["location"];
      if(file_exists($process_filename)&&file_exists($process_original)){            //preview present & original present
       $data_url_mediainfo="<div><b>".$data_messages[71]."</b></div>".$data_url_mediainfo;
       $process_filecheck="audio_11.png";
      } else if(!file_exists($process_filename)&&file_exists($process_original)){    //preview absent & original present
       $data_url_mediainfo="<div><b>".$data_messages[72]."</b></div>".$data_url_mediainfo;
       $process_filecheck="audio_01.png";
      } else if(file_exists($process_filename)&&!file_exists($process_original)){    //preview present & original absent
       $data_url_mediainfo="<div><b>".$data_messages[73]."</b></div>".$data_url_mediainfo;
       $process_filecheck="audio_10.png";
      } else if(!file_exists($process_filename)&&!file_exists($process_original)){   //preview absent & original absent
       $data_url_mediainfo="<div><b>".$data_messages[74]."</b></div>".$data_url_mediainfo;
       $process_filecheck="audio_00.png";
      }
      $site_thumbnails.=button("<img class='mediaimg' width='30' height='30' src='images/".$process_filecheck."' alt=''><span class='mediatxt'>".ucwords($process_media["description"])."</span><span class='mediatxt'>".ucwords($process_media["misc1"])."</span>",httplink($data_url_menu,$data_url_gallery,$data_url_page,$process_media["number"],$search_action,$search_value1,1),"",$buttonclass,"",$data_url_mediainfo);
     break;
     case "txt":
      $process_filename="txt/".str_pad($process_media["number"],5,"0",STR_PAD_LEFT).".txt";
      if(file_exists($process_filename)){
       $process_filecheck="";
      } else {
       $process_filecheck="<div>".$data_messages[75]."</div>";
      }
      $site_thumbnails.=button(ucwords($process_filecheck.$process_media["description"]),httplink($data_url_menu,$data_url_gallery,$data_url_page,$process_media["number"],$search_action,$search_value1),"",$buttonclass);
     break;
     default:
      $site_thumbnails.=$data_messages[76];
     break;
    }
   }
  }
 }
 $site_changepage="<table id='changepage'>";
 if($data_php_gallery_pages>1){
  $site_changepage.="<tr><td class='noborder' colspan='4'>".button("Show All Selected Catagory",httplink($data_url_menu,$data_url_gallery,9999,$data_url_media,$search_action,$search_value1),"","button")."</td></tr>";
 }
 $site_changepage.="<tr><td width='25%' class='noborder'>";
 if($data_url_page>1&&$data_url_page!=9999){
  $site_changepage.="<a href='".httplink($data_url_menu,$data_url_gallery,1,$data_url_media,$search_action,$search_value1)."' title='First Page'><img src='images/firstpage.gif' class='imgbutton' alt='First Page'></a>";
 }
 $site_changepage.="</td><td width='25%' class='noborder'>";
 if($data_url_page>1&&$data_url_page!=9999){
  $site_changepage.="<a href='".httplink($data_url_menu,$data_url_gallery,$data_url_page-1,$data_url_media,$search_action,$search_value1)."' title='".$data_messages[18]."'><img src='images/arrow1.gif' class='imgbutton' alt='".$data_messages[18]."'></a>";
 }
 $site_changepage.="</td><td width='25%' class='noborder'>";
 if(isset($data_php_media_lines[$data_php_gallery_row_finish+1])){
  $site_changepage.="<a href='".httplink($data_url_menu,$data_url_gallery,$data_url_page+1,$data_url_media,$search_action,$search_value1)."' title='".$data_messages[17]."'><img src='images/arrow2.gif' class='imgbutton' alt='".$data_messages[17]."'></a>";
 }
 $site_changepage.="</td><td width='25%' class='noborder'>";
 if(isset($data_php_media_lines[$data_php_gallery_row_finish+1])){
  $site_changepage.="<a href='".httplink($data_url_menu,$data_url_gallery,$data_php_gallery_pages,$data_url_media,$search_action,$search_value1)."' title='Last Page'><img src='images/lastpage.gif' class='imgbutton' alt='Last Page'></a>";
 }
 $site_changepage.="</td></tr><tr><td class='noborder' colspan='4'>";
 if($data_php_gallery_pages>1){
  $site_changepage.=$data_messages[16]." ".$data_url_page." / ".$data_php_gallery_pages;
 }
 $site_changepage.="</td></tr>";
 $site_changepage.="</table>";
 if($data_cookie_user){
  $select=mysql_query("SELECT o.code,o.quantity,p.price,p.description, o.number FROM orders o, media p WHERE p.number = o.code AND o.user='".$data_cookie_user."' AND o.status='basket' ORDER BY o.number");
  $process_basket_row="";
  $process_basket_total=0;
  while($row=@mysql_fetch_array($select)) {
   $process_basket_row.="<tr>\n";
   $process_basket_row.="<td class='tablerow'><a title='Remove' href='".httplink($data_url_menu,$data_url_gallery,$data_url_page,$data_url_media,"basketremove",$row[4])."'>X</a></td>\n";
   $process_basket_row.="<td class='tablerow'><a href='#' title='".$row["code"]." - ".$row["description"]."'>".$row["description"]."</a></td>";
   $process_basket_row.="<td class='tablerow'>".$row["quantity"]."</td>";
   $process_basket_row.="<td class='tablerow'>&pound;".$row["price"]."</td>\n";
   $process_basket_row.="</tr>\n";
   $process_basket_total=$process_basket_total+$row["price"];
  }
  if($process_basket_row){ 
   $site_basket.="<table cellspacing='0' cellpadding='2' id='baskettable'>\n";
   $site_basket.="<tr>\n";
   $site_basket.="<td colspan='4' class='noborder'>\n<h3>".$data_messages[77]."</h3>\n</td>\n";
   $site_basket.="</tr>\n";
   $site_basket.="<tr>\n";
   $site_basket.="<td class='tableheader' colspan='2'>".$data_messages[78]."</td>\n";
   $site_basket.="<td class='tableheader'>".$data_messages[79]."</td>\n";
   $site_basket.="<td class='tableheader'>".$data_messages[80]."</td>\n";
   $site_basket.="</tr>\n";
   $site_basket.=$process_basket_row;
   $site_basket.="<tr>\n";
   $site_basket.="<td class='tablefooter' colspan='4'>".$data_messages[81].$process_basket_total."</td>\n";
   $site_basket.="</tr>\n";
   $site_basket.="<tr>";
   $site_basket.="<td colspan='4' align='center' class='noborder'>";
   $site_basket.="<form action='https://www.paypal.com/cgi-bin/webscr' method='POST' style='margin-top:3px'>";
   $site_basket.="<input type='hidden' name='currency_code' value='GBP'>";
   $site_basket.="<input type='hidden' name='cmd' value='_xclick'>";
   $site_basket.="<input type='hidden' name='business' value='".$data_mysql_admin["Paypal Email"]."'>";
   $site_basket.="<input type='hidden' name='item_name' value='".$data_cookie_user.": mp3 selection'>";
   $site_basket.="<input type='hidden' name='item_number' value='000001'>";
   $site_basket.="<input type='hidden' name='amount' value='".$process_basket_total."'>";
   $site_basket.="<input type='submit' name='submit' value='".$data_messages[82]."' class='button'>";
   $site_basket.="</form>";
   $site_basket.="</td>";
   $site_basket.="</tr>";
   $site_basket.="</table>";
  }
 }
?>