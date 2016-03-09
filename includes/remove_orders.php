<?php
 include("connect.php");
$T=mysql_query("delete FROM `orders` where `status` != 'paid' and date_sub(curdate(),interval 90 day) > `date`;");
?>