<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require 'facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '429070730524764',
  'secret' => '996c867ebba0e5401efa222acc61efb3',
));

// Get User ID
//100004193730981
$user = $_REQUEST['user_id'];

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
	if( $_REQUEST['msg']!=""){
	 $facebook->api('/'.$user.'/feed','POST',
                   array( 
                     'message' => $_REQUEST['msg'],
                        )
                );
		die("Post Done");
	}
  } catch (FacebookApiException $e) {
    $result = $e->getResult();
	echo json_encode($result);
    $user = null;
  }
}




?>

