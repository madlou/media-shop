<?php
 header('Content-type: text/html; charset=utf-8');
 include("includes/connect.php");
 include("includes/functions.php");
 include("includes/mail.php");
 include("includes/variables.php");
 $loginhtml=checklogin();
 include("includes/getdata.php");
 include("includes/main.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<?php
 echo("<html>\n");
 echo(" <head>\n");
 echo("  <meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\">\n");
 echo("  <link rel=\"shortcut icon\" href=\"favicon.ico\" type=\"image/x-icon\"/>\n");
 echo("  <link rel=\"icon\" href=\"favicon.ico\" type=\"image/x-icon\"/>\n"); 
 if($data_mysql_admin["Meta Description"]){
  echo("  <meta name=\"description\" content=\"".$data_mysql_admin["Meta Description"]."\">\n");
 }
 if($data_mysql_admin["Meta Keywords"]){
  echo("  <meta name=\"keywords\" content=\"".$data_mysql_admin["Meta Keywords"]."\">\n");
 }
 include("includes/css.php");
 if($data_name_menu){
  $windowtitle=" - ".$data_name_menu;
 }
 if(isset($data_name_gallery)){
  $windowtitle.=" - ".$data_name_gallery;
 }
 echo("  <title>".$data_mysql_admin["Title"].$windowtitle."</title>\n"); 
 echo("  <script type='text/javascript'>\n");
 echo("   function pageloaded(){\n");
 echo("    loaded='1';\n");
 echo("    if(document.getElementById('focushere')){\n");
 echo("     document.getElementById('focushere').focus();\n");
 echo("    }\n");
 echo("   }\n");
 echo("   var loaded='0';\n");
 echo("  </script>\n");
 if($data_cookie_admin){
  include("includes/admin_header.php");                            //css code
 }
 echo(" </head>\n");
 echo(" <body onload='pageloaded()'>\n");
?>
   <div id="frame">
    <div id="contentheader">
<?php
 if(!$data_cookie_admin){
  echo("     <img src='images/".$data_mysql_admin["Banner Image"]."' style='margin-bottom:4px' alt='".$windowtitle."'>\n");
 }
?>
   </div>
   <hr class="hidethis">
   <div id="contentleft">
<?php
 if(!isset($slideshow)){
  include("html/1.txt");
  echo($site_nav);
  if($site_nav_admin){
   echo("<hr>\n");
   echo($site_nav_admin);
  }
    include("html/2.txt");
 }
?>
   </div>
   <hr class="hidethis">
   <div id="contentcenter">
<?php
 if(!isset($slideshow)){
  include("html/3.txt");
 }
 echo("<div id='maindiv'>\n");
 echo($site_main);
 echo("</div>\n");
 if(!isset($slideshow)){
  include("html/4.txt");
  if(isset($site_basket)){
   echo($site_basket);
  }
 }
?>
   </div>
   <hr class="hidethis">
   <div id="contentright">
<?php
 if(!isset($slideshow)){
  include("html/5.txt");
  echo($site_options);
  if($site_thumbnails){
   echo("<hr>\n");
  }
  echo($site_thumbnails);
  echo($site_changepage);
  if($site_options_admin){
   echo("<hr>\n");
  }
  echo($site_options_admin);
  include("html/6.txt");
 }
?>
   </div>
   <br clear="all"><!-- without this little <br> NS6 and IE5PC do not stretch the frame div down to encopass the content DIVs -->
  </div>
  <div id="dhtmltooltip"></div>
<?php
 if($site_thumbnails){
  echo("  <script type='text/javascript' language='javascript' src='pngfix.js'></script>\n");
  echo("  <script type='text/javascript' language='javascript' src='tooltip.js'></script>\n");
 }
?>
 </body>
</html>