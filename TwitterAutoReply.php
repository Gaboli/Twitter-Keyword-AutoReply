<?php
// Twitter autoreply
// By Devendra Kumar <devendra.kumar@gaboli.com>
require('config.php');
require_once('tmhUtilities.php');
require_once('tmhOAuth.php');
class TwitterAutoReply
{
	// Constants
	const SEARCH_URL = 'http://search.twitter.com/search.json?q=%s&since_id=%s';
	const UPDATE_URL = 'https://twitter.com/statuses/update.json';
	const VERIFY_URL = 'http://api.twitter.com/1/account/verify_credentials.json';
	const REQUEST_TOKEN_URL = 'https://twitter.com/oauth/request_token';
	const ACCESS_TOKEN_URL = 'https://twitter.com/oauth/access_token';
	const AUTH_URL = 'http://twitter.com/oauth/authorize';
	
	// Variables
	private $_replies = array();
	private $_oauth;
	
	// Methods
	public function __construct($consumer_key, $consumer_secret)
	{
		$this->_oauth = new tmhOAuth(array(
		'consumer_key' => $consumer_key,
		'consumer_secret' => $consumer_secret,
		'user_token' => USER_TOKEN,
		'user_secret' => USER_SECRET,
		'curl_ssl_verifypeer' => false,
		));
	}
	
	public function setToken($token, $secret)
	{
		//$this->_oauth->setToken($token, $secret); // done with manual process... TODO: fix it

	}
	
	public function addReply($term, $reply)
	{
		$this->_replies[$term] = $reply;
	}
	
	public function run()
	{
		 include 'db.php';
		echo '========= ', date('Y-m-d g:i:s A'), " - Started =========\n";
		// Get the last ID we replied to
		$since_id = @file_get_contents('since_id');
		if ($since_id == null)
			$since_id = 0;
			
		// This will store the ID of the last tweet we get
		$max_id = $since_id;
		
		// Verify their Twitter account is valid
		if (!$this->verifyAccountWorks())
		{
			// Get a request token and show instructions
			$this->doAuth();
			die();
		}
		
		// Go through each reply
		foreach ($this->_replies as $term => $reply)
		{
			echo 'Performing search for "', $term, '"... ';
			$search = json_decode(file_get_contents(sprintf(self::SEARCH_URL, urlencode($term), $since_id)));
			echo 'Done, ', count($search->results), " results.\n";		  
			// Store the max ID
			if ($search->max_id_str > $max_id)
				$max_id = $search->max_id_str;
			
			//print_r($search);
			// Now let's go through the results
			foreach ($search->results as $tweet)
			{
				$msg_user_id=$tweet->from_user_id_str;

				$msg_user_name= $tweet->from_user;
				$sql= "select * from Msg_User_Data where msgId = '$msg_user_id'";
				$rs=mysql_query($sql) or die($sql.">>".mysql_error());
				$num=mysql_num_rows($rs);
								
				if($num == 0)
				{
					$this->sendReply($tweet, $reply);
					$sql1="insert into Msg_User_Data ( msgId, msgUserName, createdDate, modifiedDate)
					values ('{$msg_user_id}','{$msg_user_name}',NOW(),NOW())";
					if(mysql_query($sql1)){
					//if successful query
						echo "New record was saved.";
					}else{
						//if query failed
						die($sql.">>".mysql_error());
					}
				}
				elseif($num>0)
				{
					$row=mysql_fetch_array($rs);
				$old_msg_date=$row['modifiedDate'];
				$curr_date = date("Y-m-d h:i:s");
				$after_add_interval_date = strtotime(date("Y-m-d h:i:s", strtotime($old_msg_date)) . "+1 month");
				$after_add_interval_date1 = date('Y-m-d h:i:s', $after_add_interval_date);
					if($after_add_interval_date1<$curr_date)
					{
							
						$this->sendReply($tweet, $reply);
						$sql2="update Msg_User_Data set modifiedDate = '$curr_date' where msgId = '$msg_user_id'";
						$res= mysql_query($sql2);
						if($res)
						{
								echo "User Data has been updated";
								
						}
						else
						{
								echo "data not updated";
						}
					}
					else
					{
						echo "Recently we replied this user. One month duration is not completed.";
					}
				}
				
			}
		}
		
		file_put_contents('since_id', $max_id);
		echo '========= ', date('Y-m-d g:i:s A'), " - Finished =========\n";
	}
	
	private function sendReply($tweet, $reply)
	{
		echo '@', $tweet->from_user, ' said: ', $tweet->text, "\n";
		$this->_oauth->request('POST',$this->_oauth->url('1/statuses/update'), array(
		'status' => '@' . $tweet->from_user . ' ' . $reply,
		'in_reply_to_status_id' => $tweet->id_str,
		));
	}
	
	private function verifyAccountWorks()
	{
		try
		{
			$response = $this->_oauth->request('GET',$this->_oauth->url('1/statuses/update'), array());
			return true;
		}
		catch (Exception $ex)
		{
			return false;
		}
	}
	
	private function doAuth()
	{
		// First get a request token, and prompt them to go to the URL
		$request_token_info = $this->_oauth->getRequestToken(self::REQUEST_TOKEN_URL);
		echo 'Please navigate to the following URL to get an authentication token:', "\n";
		echo self::AUTH_URL, '?oauth_token=', $request_token_info['oauth_token'], "\n";
		echo 'Once done (and you have a PIN number), press ENTER.';
		fread(STDIN, 10);
		
		echo 'PIN Number: ';
		$pin = trim(fread(STDIN, 10));
		
		// Now let's swap that for an access token
		$this->_oauth->setToken($request_token_info['oauth_token'], $request_token_info['oauth_token_secret']);
		$access_token_info = $this->_oauth->getAccessToken(self::ACCESS_TOKEN_URL, null, $pin);
		
		echo 'Success, ', $access_token_info['screen_name'], ' has authorized the application. Please change your setToken line to something like the following:', "\n";
		echo '$twitter->setToken(\'', $access_token_info['oauth_token'], '\', \'', $access_token_info['oauth_token_secret'], '\');';
		die();
	}
	
	public function testTweet()
	{
		$this->_oauth->fetch(self::UPDATE_URL, array(
			'status' => 'Test from TwitterAutoReply',
		), OAUTH_HTTP_METHOD_POST);
	}
}
?>
