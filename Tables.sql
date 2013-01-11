CREATE TABLE IF NOT EXISTS Msg_User_Data(
Id int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
msgId varchar(255) NOT NULL,
msgUserName varchar(255) NOT NULL,
createdDate DATETIME NOT NULL,
modifiedDate DATETIME NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `search_reply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL,
  `reply_msg` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_time` datetime NOT NULL,
  `modified_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;