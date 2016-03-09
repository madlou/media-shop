<?php
 function checklogin(){
  global $action,$value1,$value2,$data_cookie_user, $data_cookie_admin, $data_html_bodytag, $data_messages, $data_messagesx;
  if($action=="logout"){
   $message.="<h3>".ucwords($_COOKIE["user"])." ".$data_messages[19]."</h3>";
   setcookie("user","",time()-60*60);
  } else if($action=="login"&&$value1&&$value2){
   $select=mysql_query("SELECT * FROM `users` WHERE `name`='".$value1."'");
   $row=mysql_fetch_array($select);
   if($row[1]==$value1){
    if($row[2]===$value2){
     setcookie("user",$value1,time()+60*60000);
     $data_cookie_user=$value1;
     if($row[4]<5){
      $data_cookie_admin=1;
     }
     $message.="<h3>".$data_messages[20]." ".ucwords($data_cookie_user)."!</h3>";
    } else {
     $message.="<h3>".$data_messages[4]."</h3>";
     $message.="<div class='message'>".$data_messages[21]."</div>";
     $message.=displaylogin();
//     $data_html_bodytag=" onload='focusform()'";
    }
   } else {
    $message.="<h3>".$data_messages[4]."</h3>";
    $message.="<div class='message'>".$data_messages[22]."</div>";
    $message.=displaylogin();
//    $data_html_bodytag=" onload='focusform()'";
   }
  } else {
   if($_COOKIE["user"]){
    $data_cookie_user=$_COOKIE["user"];
    setcookie("user",$data_cookie_user,time()+60*60);
    $message.="<div class='message'>".$data_messages[23]." ".ucwords($data_cookie_user)."</div>";
    $select=mysql_query("SELECT * FROM `users` WHERE `name`='$data_cookie_user'");
    $row=mysql_fetch_array($select);
    if($row[4]<5){
     $data_cookie_admin=1;
    }
   } else if($action=="login"&&($value1||$value2)){
    $message.="<h3>".$data_messages[4]."</h3>";
    $message.="<div class='message'>".$data_messages[24]."</div>";
    $message.=displaylogin();
//    $data_html_bodytag=" onload='focusform()'";
   } else {
    $message.="<h3>".$data_messages[4]."</h3>";
    $message.=displaylogin();
//    $data_html_bodytag=" onload='focusform()'";
   }
  }
  return $message;
 }
 function displaylogin(){
  global $data_messages,$data_messagesx;
  $data_form_login="<div class='message'>";
  $data_form_login.="<form method='post' action='index.php'>";
  $data_form_login.="<input name='action' value='login' type='hidden'>";
  $data_form_login.="<table align='center'>";
  $data_form_login.="<tr>";
  $data_form_login.="<td style='padding:2px'>".$data_messages[5]."</td>";
  $data_form_login.="<td><input name='value1' id='focushere' class='input'></td>";
  $data_form_login.="</tr>";
  $data_form_login.="<tr>";
  $data_form_login.="<td style='padding:2px'>".$data_messages[6]."</td>";
  $data_form_login.="<td><input name='value2' class='input' type='password'></td>";
  $data_form_login.="</tr>";
  $data_form_login.="<tr>";
  $data_form_login.="<td colspan='2' align='center'><input type='submit' value='".$data_messages[7]."' class='button'></td>";
  $data_form_login.="</tr>";
  $data_form_login.="</table>";
  $data_form_login.="</form>";
  $data_form_login.="<div style='margin-top:20px;'>".$data_messagesx[5]."</div>";
  $data_form_login.=button($data_messages[83],httplink("","","","","emaildetails"));
  $data_form_login.="</div>";
  return $data_form_login;
 }
 function httplink($menu="",$gallery="",$page="",$data_url_media="",$action="",$value1="",$value2="",$value3="",$value4="",$value5="",$value6=""){
  $httparray = array();
  $httparray[0]=array("menu",$menu);
  $httparray[1]=array("gallery",$gallery);
  $httparray[2]=array("page",$page);
  $httparray[3]=array("media",$data_url_media);
  $httparray[4]=array("action",$action);
  $httparray[5]=array("value1",$value1);
  $httparray[6]=array("value2",$value2);
  $httparray[7]=array("value3",$value3);
  $httparray[8]=array("value4",$value4);
  $httparray[9]=array("value5",$value5);
  $httparray[10]=array("value6",$value6);
  $data_php_url="";
  for($i=0;$i<count($httparray); $i++){
   if($httparray[$i][1]){
    $data_php_url.="&amp;".$httparray[$i][0]."=".str_replace(" ","_",$httparray[$i][1]);
   }
  }
  if($data_php_url){
   $data_php_url="index.php?".substr($data_php_url,5);
  }
  return $data_php_url;
 }
 function showdate($date){
  $newdate=$date{6}.$date{7}."/".$date{4}.$date{5}."/".$date{0}.$date{1}.$date{2}.$date{3};
  return $newdate;
 }
 function button($buttonvalue="???",$buttonurl="#",$buttonwidth="", $buttonclass="button", $buttononclick="",$buttontitle=""){
  if($buttonwidth!=""){
   if($buttonwidth!="auto"){
    $buttonwidth.="px";
   }
   $buttonwidth=" style='width:".$buttonwidth.";'";
  }
  if($buttononclick){
   $buttononclick=" onclick='".$buttononclick."'";
  }
  if($buttontitle){
   $buttontitle=" onMouseover='if(loaded==\"1\"){ddrivetip(\"".$buttontitle."\")}' onMouseout='if(loaded==\"1\"){hideddrivetip()}'"; //title='".$buttontitle."'     
  }
  $button="<div><a href='".$buttonurl."'".$buttononclick.$buttontitle.$buttonwidth." class='".$buttonclass."'>".$buttonvalue."</a></div>\n";	
  return $button;
 }
 function getmediainfo($mysqlrow,$mysqlfields,$mysqlnames){
  global $data_messages;
  $mediainfo="";
  for($i=0;$i<count($mysqlfields);$i++){
   if($mysqlrow[$mysqlfields[$i]]){
    if($mysqlnames[$i]){
     $process_name=$mysqlnames[$i];
    } else {
     $process_name=$mysqlfields[$i];
    }
    if($mysqlfields[$i]=="price"){
     if($mysqlrow[$mysqlfields[$i]]=="0.00"){
      $mediainfo.="<tr><td width=150>".$process_name."</td><td>".$data_messages[25]."</td></tr>";
     } else {
      $mediainfo.="<tr><td width=150>".$process_name."</td><td>&pound;".$mysqlrow["price"]."</td></tr>";
     }
    } else {
     $mediainfo.="<tr><td width=150>".$process_name."</td><td>".htmlspecialchars($mysqlrow[$mysqlfields[$i]], ENT_QUOTES)."</td></tr>";
    }
   }
  }
  if($mediainfo!=""){
   $mediainfo="<table>".$mediainfo."</table>";
  }
  return $mediainfo;
 }
 function filedownload($file,$newfile="download",$hiddenPath="originals/"){
  global $data_messages;
  $file_real=$hiddenPath.$file;
  if (file_exists($file_real)){
   $extension = strtolower(substr(strrchr($file, "."), 1));
   $newfile.=".".$extension;
   switch($extension){
    case "asf":     $type = "video/x-ms-asf";                break;
    case "avi":     $type = "video/x-msvideo";               break;
    case "exe":     $type = "application/octet-stream";      break;
    case "mov":     $type = "video/quicktime";               break;
    case "mp3":     $type = "audio/mpeg";                    break;
    case "mpg":     $type = "video/mpeg";                    break;
    case "mpeg":    $type = "video/mpeg";                    break;
    case "rar":     $type = "encoding/x-compress";           break;
    case "txt":     $type = "text/plain";                    break;
    case "wav":     $type = "audio/wav";                     break;
    case "wma":     $type = "audio/x-ms-wma";                break;
    case "wmv":     $type = "video/x-ms-wmv";                break;
    case "zip":     $type = "application/x-zip-compressed";  break;
    default:        $type = "application/force-download";    break;
   }
   $header_file = (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) ? preg_replace('/\./', '%2e', $newfile, substr_count($newfile, '.') - 1) : $newfile; // Fix IE bug [0]
   header("Pragma: public");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
   header("Cache-Control: public", false);
   header("Content-Description: File Transfer");
   header("Content-Type: " . $type);
   header("Accept-Ranges: bytes");
   header("Content-Disposition: attachment; filename=\"" . $header_file . "\";");
   header("Content-Transfer-Encoding: binary");
   header("Content-Length: " . filesize($file_real));
   if ($stream = fopen($file_real, 'rb')){
    while(!feof($stream) && connection_status() == 0){
     set_time_limit(0);
     print(fread($stream,1024*8));
     flush();
    }
    fclose($stream);
    exit();
   }
  }else{
   return "<div class='message'>".$data_messages[26]."</div>";
  }
 }

?>