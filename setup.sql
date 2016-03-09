DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `value` char(200) NOT NULL,
  `name` char(30) NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=MyISAM AUTO_INCREMENT=1
INSERT INTO `admin` VALUES 
(1,'0','Total Records'),
(2,'My Title','Title'),
(3,'0','Admin: Max Records'),
(4,'9','Admin: Record Priority Max'),
(5,'7','Show Priority Less Than'),
(6,'8','Extra Record Priority Max'),
(7,'1','Show Login'),
(8,'paypal@myemail.co.uk','Paypal Email'),
(9,'0','Show Extra Button'),
(10,'1','Thumbnail Columns'),
(11,'6','Thumbnail Rows'),
(12,'0','Show Slide Show Button'),
(13,'5','Slide Show Refresh'),
(14,'0','Auto Color Setting'),
(15,'#ddccff','Auto Color Base'),
(16,'black','Background Color'),
(17,'green','Button Color'),
(18,'#cc0000','Selected Button Color'),
(19,'gold','Mouseover Button Color'),
(20,'sans-serif','Font'),
(21,'white','Font Color'),
(22,'14','Font Size'),
(23,'gold','Border Color'),
(24,'1','Admin: Use WYSIWYG Editor'),
(25,'my_banner.jpg','Banner Image'),
(26,'1','Admin: Display Messages'),
(27,'Meta Description','Meta Description'),
(28,'Meta Keywords','Meta Keywords'),
(29,'30','MP3 Sample Length'),
(30,'No M3U','No M3U File'),
(31,'192','MP3 Bitrate'),
(32,'19','Options Text Max Length'),
(33,'`priority` ASC, `type` ASC, `misc2` DESC, `number` ASC','Gallery Order'),
(34,'email@me.com','Web Email'),
(35,'180','Button Width'),
(36,'900','Page Width'),
(37,'500','Center Width'),
(38,'1000','Admin: Page Width'),
(39,'images/bg.jpg','Center Image');

DROP TABLE IF EXISTS `artist`;
CREATE TABLE `artist` (
  `number` int(4) NOT NULL AUTO_INCREMENT,
  `artist` char(30) NOT NULL,
  `genre` char(30) NOT NULL,
  `notes` char(100) NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=MyISAM

DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(45) NOT NULL,
  `menu` char(45) NOT NULL DEFAULT 'Home',
  PRIMARY KEY (`number`)
) ENGINE=MyISAM AUTO_INCREMENT=1

INSERT INTO `gallery` VALUES 
(1,'Home','1'),
(2,'Media','2'),
(3,'Links','3');

DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `gallery` char(45) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '5',
  `description` char(200) NOT NULL DEFAULT '',
  `type` char(10) NOT NULL,
  `price` varchar(8) NOT NULL,
  `location` char(30) NOT NULL,
  `misc1` char(30) NOT NULL,
  `misc2` char(30) NOT NULL,
  `misc3` char(30) NOT NULL,
  PRIMARY KEY (`number`),
  KEY `gallery` (`gallery`),
  KEY `priority` (`priority`)
) ENGINE=MyISAM AUTO_INCREMENT=1

DROP TABLE IF EXISTS `mediainfo`;
CREATE TABLE `mediainfo` (
  `number` int(3) NOT NULL AUTO_INCREMENT,
  `version` char(10) COLLATE utf8_bin NOT NULL,
  `field` char(15) COLLATE utf8_bin NOT NULL,
  `display` char(30) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=MyISAM AUTO_INCREMENT=1
INSERT INTO `mediainfo` VALUES 
(1,'long','misc2','Media Code'),
(2,'long','type','Media Type'),
(3,'long','misc1','Artist'),
(4,'long','description','Track'),
(5,'long','misc3','Rhythm'),
(6,'long','price','Price'),
(7,'short','misc1','Artist'),
(8,'short','description','Track'),
(9,'short','price','Price'),
(10,'short','misc3','Rhythm');

DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `number` int(6) NOT NULL AUTO_INCREMENT,
  `name` char(45) NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=MyISAM AUTO_INCREMENT=1
INSERT INTO `menu` VALUES 
(1,'Home'),
(7,'Tell A Friend About Us'),
(8,'Links');

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `number` int(10) NOT NULL AUTO_INCREMENT,
  `description` char(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=MyISAM AUTO_INCREMENT=1
INSERT INTO `messages` VALUES
(1,'Login'),
(2,'Register To Purchase Items'),
(3,'Search Site'),
(4,'Please Login'),
(5,'User'),
(6,'Password'),
(7,'Submit'),
(8,'Registration Page'),
(9,'Privacy Policy'),
(10,'Email Address:'),
(11,'User:'),
(12,'Password:'),
(13,'Register'),
(14,'<< Enter text here >>'),
(15,'Search'),
(16,'Page:'),
(17,'Next Page'),
(18,'Previous Page'),
(19,'Successfully logged out!'),
(20,'Logged in as'),
(21,'Password doesn\'t match'),
(22,'User not found'),
(23,'Logged in as'),
(24,'User and Password Required'),
(25,'Available upon request'),
(26,'Requested file does not exist for download'),
(27,'Log Out'),
(28,'View Basket'),
(29,'Your subject contains illegal characters'),
(30,'Invalid e-mail address.'),
(31,'Your message body contains invalid characters.'),
(32,'Your message was sent'),
(33,'An error occurred while we were attempting to send your message. Please try again later.'),
(34,'You have to fill every field in'),
(35,'Sorry, you have not paid for or already downloaded this media.'),
(36,'If you believe this to be an error, please contact us.'),
(37,'You must be logged in to download files.'),
(38,'Your Basket'),
(39,'Status'),
(40,'Item'),
(41,'Qty'),
(42,'Price'),
(43,'Payment Cleared'),
(44,'No Payment'),
(45,'Download'),
(46,'Delivery Only'),
(47,'Please login to view your basket'),
(48,'That user name already exists, please choose another.'),
(49,'Try Again'),
(50,'Thank you for registering!'),
(51,'An email has been sent to you for your records. Log in by clicking the button below.'),
(52,'Please fill in each box'),
(53,'You are required to register or login to make a purchase.'),
(54,'Please login if you want to remove an item from your basket.'),
(55,'Photo not found!'),
(56,'Media Player'),
(57,'Press play to listen to a short snippet.'),
(58,'No snippet found!'),
(59,'Text file not found!'),
(60,'Media / Mp3`s Not Currently Available'),
(61,'Download the full'),
(62,'for only £'),
(63,'Add to Basket'),
(64,'This'),
(65,'is Delivery Only. Please contact us for the full purchase details.'),
(66,'Purchase this'),
(67,'for only £'),
(68,'- Deliver only'),
(69,'Search Results'),
(70,'NA'),
(71,'Download available just click to play snippet'),
(72,'No Snippet, but download is available'),
(73,'Snippet available, but delivery only'),
(74,'No snippet and delivery only'),
(75,'Error: No text file'),
(76,'ERR!'),
(77,'Basket'),
(78,'Item'),
(79,'Qty'),
(80,'Price'),
(81,'Total: £'),
(82,'Checkout'),
(83,'Forgotten Login Details'),
(84,'Enter the Email Address used when you Registered with us.'),
(85,'Email address entered not found please contact our Webmaster.'),
(86,'An email has been sent to you with your login details.');

DROP TABLE IF EXISTS `messagesx`;
CREATE TABLE `messagesx` (
  `number` int(11) NOT NULL AUTO_INCREMENT,
  `description` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=MyISAM AUTO_INCREMENT=1
INSERT INTO `messagesx` VALUES 
(1,'PayPal Message'),
(2,'Registration Message'),
(3,'Email Address Message'),
(4,'Order Message'),
(5,'Forgot Message');

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `number` int(10) NOT NULL AUTO_INCREMENT,
  `user` char(20) NOT NULL,
  `code` int(11) NOT NULL,
  `quantity` int(5) NOT NULL,
  `status` char(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`number`),
  KEY `user` (`user`)
) ENGINE=MyISAM AUTO_INCREMENT=1 

DROP TABLE IF EXISTS `paypal`;
CREATE TABLE `paypal` (
  `number` int(10) NOT NULL AUTO_INCREMENT,
  `passed` varchar(15) NOT NULL,
  `item_name` varchar(20) NOT NULL,
  `payment_status` varchar(15) NOT NULL,
  `payment_amount` varchar(20) NOT NULL,
  `payment_currency` varchar(10) NOT NULL,
  `txn_id` varchar(20) NOT NULL,
  `receiver_email` varchar(60) NOT NULL,
  `payer_email` varchar(60) NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=MyISAM AUTO_INCREMENT=1

DROP TABLE IF EXISTS `structure`;
CREATE TABLE `structure` (
  `number` int(3) NOT NULL AUTO_INCREMENT,
  `parent` char(100) NOT NULL,
  `child` char(100) NOT NULL,
  `parenttype` char(10) NOT NULL,
  `childtype` varchar(10) NOT NULL,
  `checktype` char(10) NOT NULL,
  PRIMARY KEY (`number`),
  UNIQUE KEY `child` (`child`)
) ENGINE=MyISAM AUTO_INCREMENT=9
INSERT INTO `structure` VALUES 
(1,'SELECT `name` FROM `menu`','SELECT `menu` FROM `gallery`','table','table','linked'),
(2,'SELECT `name` FROM `gallery`','SELECT `gallery` FROM `photos`','table','table','linked'),
(3,'SELECT `number` FROM `photos` WHERE `type` LIKE \"%\"','originals/*.*','table','file','exact'),
(4,'SELECT `number` FROM `photos` WHERE `type` = \"jpg\"','webpics/*.jpg','table','file','exact'),
(5,'SELECT `number` FROM `photos` WHERE `type` = \"mp3\"','mp3/*.mp3','table','file','exact'),
(6,'SELECT `number` FROM `photos` WHERE `type` = \"mp3\"','m3u/*.m3u','table','file','exact'),
(7,'SELECT `number` FROM `photos` WHERE `type` = \"jpg\"','thumbnails/*.jpg','table','file','exact'),
(8,'SELECT `number` FROM `photos` WHERE `type` = \"txt\"','txt/*.txt','table','file','exact');

DROP TABLE IF EXISTS `tablesetup`;
CREATE TABLE `tablesetup` (
  `number` int(4) NOT NULL AUTO_INCREMENT,
  `table` char(20) NOT NULL,
  `field` char(20) NOT NULL,
  `rule` char(20) NOT NULL,
  `value` char(20) NOT NULL,
  PRIMARY KEY (`number`)
) ENGINE=MyISAM AUTO_INCREMENT=1

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `number` int(5) NOT NULL AUTO_INCREMENT,
  `name` char(20) NOT NULL DEFAULT '',
  `password` char(20) NOT NULL DEFAULT '',
  `email` char(50) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`number`)
) ENGINE=MyISAM AUTO_INCREMENT=2 
INSERT INTO `users` VALUES
(1,'admin','admin','admin@here.co.uk',1,'2009-02-01 08:00:00');