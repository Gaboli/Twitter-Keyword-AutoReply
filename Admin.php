#!/usr/bin/php
<?php
ini_set('max_execution_time', 300);
// Twitter autoreply Admin
// By Devendra Kumar <devendra.kumar@gaboli.com>
date_default_timezone_set('Australia/Melbourne');

require('config.php');
require('TwitterAutoReply.php');
require('db.php');
// Consumer key and consumer secret
$twitter = new TwitterAutoReply(CONSUMER_KEY, CONSUMER_SECRET);
// Token and secret
$twitter->setToken(USER_TOKEN,USER_SECRET);

//selecting records
$sql ="SELECT * FROM search_reply";
//query the database
$rs=mysql_query($sql) or die("SQL: ".$sql." >> ".mysql_error());

// ArrayIterator
$keyword = new ArrayIterator;
$reply = new ArrayIterator;

//retrieve our table contents
while($row =mysql_fetch_array($rs))
	{
	$keyword[]=$row['keyword'];
	$reply[]=$row['reply_msg'];
	}

$iteration = new MultipleIterator;
$iteration->attachIterator($keyword);
$iteration->attachIterator($reply);
foreach($iteration as $val)
{
	$twitter->addReply($val[0], $val[1]);
}
$twitter->run();
?>
