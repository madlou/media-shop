<?php
/*

mysqltable()
playmp3()
makemp3()
extractmp3()
resize()
loadnewcords()
edittxt()
createtxt()
randomisefile()
newthumb()
checkstructure()
extracttable()
extractfiles()
*/
 function mysqltable($query,$str_tools="",$action="",$start="", $length=""){
  global $site_options_admin;
  $select=@mysql_query($query);
  if(!$select){
   return "That query returned no data";
  }
  for($i=0;$i<strlen($str_tools);$i++){
   $tools[$i]=substr($str_tools,$i,1);
  }
  $process_return="";
  $total_rows = mysql_num_rows($select);   
  $process_return.="<table id='edittable'>\n";
  $process_return.="<tr>\n";
  for ($i=0; $i<10; $i++){
   if ($tools[$i]){
    $process_return.="<td class='noborder'></td>\n";
   }
  }
  $i="0";
  $columns="0";
  while($row=@mysql_field_name($select,$i)){
   if($row=="number"){
    $process_return.="<td class='tableheader' style='width:40px;'>".$row."</td>\n";
   } else {
    $process_return.="<td class='tableheader'>".$row."</td>\n";
   }
   $column_array[$i]=$row;
   $columns++;
   $rules_select = mysql_query("SELECT `rule`,`value` FROM `tablesetup` WHERE `table` = '$edittable' AND `field` = '$editcol' ORDER BY `rule`,`value`;");
   $rules_array_select[$i]="";
   while($rules_row=@mysql_fetch_array($rules_select)) {
    if($rules_row[0]=="select"){
     $rules_array_select[$i].="<option value='".$rules_row[1]."'>".$rules_row[1]."</option>";
    }
   }
   $i++;
  }
  $process_return.="</tr>";
  while($row=@mysql_fetch_array($select)) {
   $process_return.="<tr>\n";
   if($tools[0]){
    $process_return.="<td class='tableoption' onclick='deleterow(\"".$row["number"]."\")'>X</td>\n";
   }
   if($tools[1]){
    $process_return.="<td class='tableoption' onclick='moveup(\"".$row["number"]."\")'>&uarr;</td>\n";
   }
   if($tools[2]){
    $process_return.="<td class='tableoption' onclick='movedown(\"".$row["number"]."\")'>&darr;</td>\n";
   }
   if($tools[3]){
    if($row["type"]){
     switch($row["type"]){
      case "jpg":
       $process_return.="<td class='tableoption' onclick='newthumb(\"".$row["number"]."\")'>&reg;</td>\n";
      break;
      case "vinyl":
      case "mp3":
//       $process_return.="<td class='tableheader'>-</td>\n";
       $process_return.="<td class='tableoption' onclick='updatemp3(\"".$row["number"]."\")'>&reg;</td>\n";
      break;
      case "txt":
       $process_return.="<td class='tableoption' onclick='edittxt(\"".$row["number"]."\")'>E</td>\n";
      break;
      default:
       $process_return.="<td class='tableheader'>-</td>\n";
      break;
     }
    } else {
     $process_return.="<td class='tableheader'>-</td>\n";
    }
   }
   if($tools[4]){
    if($row["type"]){
     switch($row["type"]){
      case "jpg":
       $process_return.="<td class='tableoption'><a href='webpics/".str_pad($row["number"], 5, "0", STR_PAD_LEFT).".jpg' target='_new'><img class='imgbutton' src='thumbnails/".str_pad($row["number"], 5, "0", STR_PAD_LEFT).".jpg'></a></td>\n";
      break;
      default:
       $process_return.="<td class='tableheader'>-</td>\n";
      break;
     }
    } else {
     $process_return.="<td class='tableheader'>-</td>\n";
    }
   }
   for($i="0";$i<$columns;$i++){
    if($column_array[$i]=="number"){
     $process_return.="<td id='".$row[0]."::".$column_array[$i]."'>".$row[$i]."</td>\n";
    } else if($rules_array_select[$i]&&!$row[$i]){
     $process_return.="<td>";
     $process_return.="<select name='value".($i+1)."' class='editdbinput' onchange='if(this.value==\"othervalue\"){this.outerHTML=\"<input name=\\\"value".($i+1)."\\\" class=\\\"editdbinput\\\">\"}'><option value=''></option>".$tableruleselect[$i]."<option value='othervalue'>*Other*</option></select>";
     $process_return.="</td>\n";
    } else {
     if($column_array[$i]=="password"){
      $process_return.="<td>XXXX</td>\n";
     } else {
      $process_return.="<td class='hand' onclick='makeinput(this)' id='".$row[0]."::".$column_array[$i]."'>".$row[$i]."</td>\n";
     }
    }
   }
   $process_return.="</tr>\n";
  }
  $process_return.="</table>\n";
  $site_options_admin.="<h3>Insert Record</h3>\n";
  $site_options_admin.="<form onsubmit='insert(); return false'>\n";
  for($i=1;$i<$columns; $i++){
   $site_options_admin.="<div>".$column_array[$i]."</div>\n";
   $site_options_admin.="<div><input id='insert".$i."' value=''></div>\n";
  }
  $site_options_admin.="<input type='submit' class='button' value='Insert'>\n";
  $site_options_admin.="</form>\n";
  return $process_return;
 }


 function playmp3($playmp3){
  echo("     <OBJECT ID='MediaPlayer1' CLASSID='CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95' CODEBASE='http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab# Version=5,1,52,701' STANDBY='Loading Microsoft Windows® Media Player components...' TYPE='application/x-oleobject' width='280' height='46'>\n");
  echo("      <param name='fileName' value='m3u/".$playmp3.".m3u'>\n");
  echo("      <param name='animationatStart' value='true'>\n");
  echo("      <param name='transparentatStart' value='true'>\n");
  echo("      <param name='autoStart' value='true'>\n");
  echo("      <param name='showControls' value='true'>\n");
  echo("      <param name='Volume' value='-300'>\n");
  echo("      <embed type='application/x-mplayer2' pluginspage='http://www.microsoft.com/Windows/MediaPlayer/' src='m3u/".$playmp3.".m3u' name='MediaPlayer1' width=280 height=46 autostart=1 showcontrols=1 volume=-300>\n");
  echo("     </OBJECT>\n");
 }
 function makem3u($filenumber){
  $file = fopen("m3u/".str_pad($filenumber, 5, "0", STR_PAD_LEFT).".m3u", 'w');
  fwrite($file, "../mp3/".str_pad($filenumber, 5, "0", STR_PAD_LEFT).".mp3");
  fclose($file);
 }
 function extractmp3($oldfile,$newfile,$length,$bitrate){
  $open_file = fopen($oldfile, "r");
  $bytes=1024/8*$length*$bitrate;
  $content = fread($open_file, $bytes);
  fclose($open_file);
  $open_file = fopen ($newfile, "w");
  fwrite ($open_file, $content);
  fclose ($open_file);
 }
 function resize($srcFile, $srcType, $dstType, $dstWidth, $dstHeight, $dstPath, $thumbtotop){
  if ($srcType == "image/jpeg")
   $handle = ImageCreateFromJPEG($srcFile);
  else if ($srcType == "image/png")
   $handle = @imagecreatefrompng($srcFile);
  else if ($srcType == "image/gif")
   $handle = @imagecreatefromgif($srcFile);
  else
   return false;
  if (!$handle)
   return false;
  $srcWidth  = @imagesx($handle);
  $srcHeight = @imagesy($handle);
  if ($srcWidth >= $dstWidth && $srcHeight >= $dstHeight){
   $newHandle = @imagecreatetruecolor($dstWidth, $dstHeight);
   if (!$newHandle)
    return false;
   if($srcHeight < $srcWidth){
    $ratio = (double)($srcHeight / $dstHeight);
    $cpyWidth = round($dstWidth * $ratio);
    if ($cpyWidth > $srcWidth){
     $ratio = (double)($srcWidth / $dstWidth);
     $cpyWidth = $srcWidth;
     $cpyHeight = round($dstHeight * $ratio);
     $xOffset = 0;
     $yOffset = round(($srcHeight - $cpyHeight) / 2);
    } else {
     $cpyHeight = $srcHeight;
     $xOffset = round(($srcWidth - $cpyWidth) / 2);
     $yOffset = 0;
    }
   } else {
    $ratio = (double)($srcWidth / $dstWidth);
    $cpyHeight = round($dstHeight * $ratio);
    if ($cpyHeight > $srcHeight){
     $ratio = (double)($srcHeight / $dstHeight);
     $cpyHeight = $srcHeight;
     $cpyWidth = round($dstWidth * $ratio);
     $xOffset = round(($srcWidth - $cpyWidth) / 2);
     $yOffset = 0;
    } else {
     $cpyWidth = $srcWidth;
     $xOffset = 0;
     $yOffset = round(($srcHeight - $cpyHeight) / 2);
    }
   }
   if($thumbtotop){
    if (!@imagecopyresampled($newHandle, $handle, 0, 0, $xOffset, 0, $dstWidth, $dstHeight, $cpyWidth, $cpyHeight))
     return false;
    if($srcWidth > $srcHeight){
     echo("<script>alert('Picture is landscape!')</script>");
    }
   } else {
    if (!@imagecopyresampled($newHandle, $handle, 0, 0, $xOffset, $yOffset, $dstWidth, $dstHeight, $cpyWidth, $cpyHeight))
     return false;
   }
   @imagedestroy($handle);
   if ($dstType == "png")
    @imagepng($newHandle, $dstPath.".png");
   else if ($dstType == "jpg")
    @imagejpeg($newHandle, $dstPath.".jpg", 90);
   else if ($dstType == "gif")
    @imagegif($newHandle, $dstPath.".gif");
   else
    return false;
   @imagedestroy($newHandle);
    return true;
  } else {
   return "Sorry, that image is too small. The image must be at least ".$dstWidth."x".$dstHeight." pixels in size.";
  }
  //list($width, $height, $type, $attr) = getimagesize("img/flag.jpg");
  //echo "<img src=\"img/flag.jpg\" $attr alt=\"getimagesize() example\" />";
 }
 function createrecords($filenumber,$adminMP3SampleLength,$adminMP3Bitrate){
  $process_return="";
  $randomfilename="";
  $filename = str_pad($filenumber+1, 5, "0", STR_PAD_LEFT);
  $upload="upload/".$filename;
  $thumbnails="thumbnails/".$filename;
  $webpics="webpics/".$filename;
  $mp3="mp3/".$filename;
  $originals="originals/".$filename;
  $txt="txt/".$filename;
  if(file_exists($upload.".jpg")||file_exists($upload.".mp3")||file_exists($upload.".txt")){
   if(file_exists($upload.".jpg")){
    $extension="jpg";
    resize($upload.".jpg","image/jpeg","jpg","40","40",$thumbnails, NULL);
    if(resize($upload.".jpg","image/jpeg","jpg","460","460",$webpics, "0")!="true"){
     copy($upload.".jpg",$webpics.".jpg");
    }
    copy($upload.".jpg",$originals.".jpg");
    unlink($upload.".jpg");
   } else if(file_exists($upload.".mp3")){
    $extension="mp3";
    $randomfilename=randomisefile($originals).".mp3";
    copy($upload.".mp3",$randomfilename);
    extractmp3($upload.".mp3",$mp3.".mp3",$adminMP3SampleLength,$adminMP3Bitrate);
    makem3u($filenumber+1);
    unlink($upload.".mp3");
   } else if(file_exists($upload.".txt")){
    $extension="txt";
    copy($upload.".txt",$txt.".txt");
    copy($upload.".txt",$originals.".txt");
    unlink($upload.".txt");
   } else { 
    return "<h2>Unknown extension for $upload.???</h2>";
   }
   $temp_row=mysql_fetch_array(mysql_query("SELECT * FROM `media` WHERE `number` = '".($filenumber+1)."';"));
   if($temp_row[0]){
    $update = mysql_query("UPDATE `media` SET `location`='".$randomfilename."', `type`='mp3', `price`='0.99' WHERE `number` = '".($filenumber+1)."';");
   } else {
    $insert = mysql_query("INSERT INTO `media` (`number`,`gallery`,`priority`,`type`,`location`) VALUES ('".($filenumber+1)."', 'NoGallery', '5', '".$extension."','".$randomfilename."');"); 
    if(!$insert){
     return "Insert Failed.  Move the media file back to /uploads/ and try again!";
    }
    $update = @mysql_query("UPDATE `admin` SET `value` = '".($filenumber+1)."' WHERE `number` = '1';");
    $nextfilename = str_pad($filenumber+2, 5, "0", STR_PAD_LEFT);
    $upload="upload/".$nextfilename;
    if(file_exists($upload.".jpg")||file_exists($upload.".mp3")||file_exists($upload.".txt")){
     $process_return.="<h2>There are more photos to create.  The page will keep reloading until done.  Please be patient!</h2>\n";
     $process_return.="<script>setTimeout('window.location.href=window.location.href',100)</script>\n";
     return $process_return;
    }
   }
   $process_return.="<div>Records have been updated.</div>";
  } else {
   $process_return.="<div>No New Records!!</div>";
  }
  return $process_return;
 }
 function edittxt($file){
  if(file_exists($file)){
   $handle = fopen($file, "r");
   if(filesize($file)>0){
    $contents = htmlentities(fread($handle, filesize($file)),ENT_QUOTES);
   }
   fclose($handle);
   $process_return="<h2 id='filelocation'>$file</h2>";
   $process_return.="<form onsubmit='savetxt(); return false'>";
   $process_return.="<div><textarea id='textcontent' cols='150' rows='25' style='width:95%;'>".str_replace("<br />","",$contents)."</textarea></div>";
   $process_return.="<div><input type='submit' value='Update'></div>";
   $process_return.="</form>";
  } else {
   $process_return="No file found";
  }
  return $process_return;
 }
 function createtxt($filename,$text){
  $file = fopen($filename, 'w');
  fwrite($file, $text);
  fclose($file);
 }
 function randomisefile($randfilenumber){
  $randnumber = rand(1,9999999999);
  $randfilenumber = $randfilenumber."_".str_pad($randnumber, 10, "0", STR_PAD_LEFT);
  return $randfilenumber;
 }
 function newthumb($newthumb){
  if(file_exists("originals/" . str_pad($newthumb, 5, "0", STR_PAD_LEFT) . ".jpg")){
   $filePath="originals/".str_pad($newthumb, 5, "0", STR_PAD_LEFT).".jpg";
   $dstPath="thumbnails/".str_pad($newthumb, 5, "0", STR_PAD_LEFT);
   resize($filePath,"image/jpeg","jpg","40","40",$dstPath, NULL);
   $dstPath="webpics/".str_pad($newthumb, 5, "0", STR_PAD_LEFT);
   if(resize($filePath,"image/jpeg","jpg","460","460",$dstPath, "0")!="true"){
    copy($filePath,$dstPath.".jpg");
   }
   return true;
  } else {
   return false;
  }
 }

 function checkstructure(){
  $select = mysql_query("SELECT * FROM `structure`");
  $count=0;
  echo("<table>\n");
  while($row=mysql_fetch_array($select)){
   $checkarray1=array();
   $checkarray2=array();
   if($row[3]=="table"){
    if($row[4]=="file"){
     $ext=substr($row[2],strpos($row[2],"*")+1);
	 $pad=5;
    } else {
     $ext="";
	 $pad=0;
    }
    $checkarray1=extracttable($row[1],$ext, $pad);
   } else if($row[3]=="file"){
    $dir=substr($row[1],0,strrpos($row[1],"/")+1);
    $ext=substr($row[1],strpos($row[1],"*")+1);
    $checkarray1=extractfiles($dir);
   }
   if($row[4]=="table"){
    $checkarray2=extracttable($row[2]);
   } else if($row[4]=="file"){
    $dir=substr($row[2],0,strrpos($row[2],"/")+1);
    $ext=substr($row[2],strrpos($row[2],"*")+1);
    $checkarray2=extractfiles($dir);
   }
   echo("<tr>\n");
   echo("<td>".$row[1]."</td>\n");
   echo("<td>".count($checkarray1)."</td>\n");
   echo("</tr>\n");
   echo("<tr>\n");
   echo("<td>".$row[2]."</td>\n");
   echo("<td>".count($checkarray2)."</td>\n");
   echo("</tr>");
   if($row[5]=="exact"){
    if(count($checkarray1)>count($checkarray2)){
     $runfor=count($checkarray1);
    } else {
     $runfor=count($checkarray2);
    }
    for($i=0;$i<$runfor;$i++){
     if($checkarray1[$i]!=$checkarray2[$i]){
      if(strpos($checkarray1[$i].$checkarray2[$i],"*")>0){
       if(substr($checkarray1[$i],0,strpos($checkarray1[$i],".")-1)!=substr($checkarray2[$i],0,strpos($checkarray2[$i],".")-1)){
        echo("<tr><td colspan='2' style='background-color:red'>".$checkarray1[$i]."  -  ".$checkarray2[$i]." is different.  Checking stopped.</td></tr>");		 
        break;
       }        
      } else {
       echo("<tr><td colspan='2' style='background-color:red'>".$checkarray1[$i]."  -  ".$checkarray2[$i]." is different.  Checking stopped.</td></tr>");
       break;
      }
     }	  
    }
   } else if($row[5]=="linked"){
    $count=0;
    for($i=0;$i<count($checkarray2);$i++){
     $linked=0;
     for($j=0;$j<count($checkarray1);$j++){
      if($checkarray2[$i]==$checkarray1[$j]){
      $linked=1;
      }
     }
     if(!$linked){
      echo("<tr><td colspan='2' style='background-color:red'>".$checkarray2[$i]." is not present in parent</td></tr>");
     }
    }
   }
   echo("<tr>");
   echo("<td colspan='2' style='border: 0px'>&nbsp");
   echo("</td>");
   echo("</tr>");
  }
  echo("</table>\n");
 }

 function extracttable($getselect, $ext="", $pad=0){
  $count=0;
  $select = mysql_query($getselect);
  while($row=mysql_fetch_array($select)){
   if($pad){
    $mysqlarray[$count]=str_pad($row[0],$pad,"0",STR_PAD_LEFT).$ext;
   } else {
    $mysqlarray[$count]=$row[0].$ext;
   }
   $count++;
  }
  return $mysqlarray;
 }
 function extractfiles($dir){
  if (is_dir($dir)) {
   if ($dh = opendir($dir)) {
    $count=0;
    while (($file = readdir($dh)) !== false) {
     if($file&&$file!="."&&$file!=".."&&$file!="Thumbs.db"&&$file!="index.html"&&filetype($dir . $file)!="dir"){
      $filearray[$count]=$file;
      $count++;
     }
    }
    closedir($dh);
   }
  }
  return $filearray;
 }

 function checkuploads($renameupload=false,$newfile=""){
  global $data_mysql_admin;
  $process_return="<h2>Files Ready in 'Upload' Folder</h2>";
  $process_return.=$site_main.=button("Create New Text File",httplink("","","","","checkuploads","","1"));
  if($newfile){
   createtxt("upload/new.txt","<h1>New Text File</h1>\n<div class='message'>Text can go here</div>\n<p>or here</p>\n<p class='centered'>or here</p>");
  }
  $process_return.="<div class='message'>To intergrate the files below in to the site, review the files below,  then click the 'Rename Files' button and then select 'Create Records'.  You can then assign them to a gallery by clicking 'Unassigned'.</div>";
  $dir = "upload/";
  if (is_dir($dir)) {
   if ($dh = opendir($dir)) {
    $process_return.="<table align='center'><tr><td>Current File Name</td><td>File Size (kB)</td><td>New File Number</td><td>File Type</td></tr>";
    $count=$data_mysql_admin["Total Records"];
    if(!$count){
     $count=0;
    }
    $tempcount=0;
    while (($file = readdir($dh)) !== false) {
     if($file&&$file!="."&&$file!=".."&&$file!="Thumbs.db"&&filetype($dir . $file)!="dir"&&$file!="index.html"){
      $count++;
      $process_return.="<tr><td>".$file."</td><td>".filesize($dir . $file)."</td><td>".str_pad($count,5,"0",STR_PAD_LEFT)."</td><td>".substr($file,strpos($file,".")+1)."</td></tr>";   //<td>".filetype($dir . $file)."</td>
      $temp1[$tempcount]=$dir.$file;
      $temp2[$tempcount]=$dir.str_pad($count,5,"0",STR_PAD_LEFT).".".substr($file,strpos($file,".")+1);
      $tempcount++;
     }
    }
    if($renameupload){
     for($i=0;$i<$tempcount;$i++){
      rename($temp1[$i],$temp2[$i]);
     }
    }
    if($data_mysql_admin["Total Records"]==($count)){
     $process_return.="<tr><td colspan='4'><h2>No files pending!</h2></td></tr>";
     $hide_button=true;
    }
    $process_return.="</table>";
    closedir($dh);
   }
  }
  if(!$hide_button){
   if($renameupload){
    $process_return="<h2>Rename Completed</h2>";
    $process_return.="<div class='message'>Now select 'Create Records'.  You will then be able to assign them to a gallery by clicking 'Unassigned'.</div>";
    $process_return.=button("Create Records",httplink("","","","","createrecords"));
   } else {
    $process_return.="<p class='centered'>".button("Rename Files",httplink("","","","","checkuploads","1"))."</p>";
   } 
  }
  return $process_return;
 }
?>