<?php
require_once ("const.php");

if ($argv[0]=="FBUtil.php") {
	test(); 
}

function test() {
	$added = addPost(FB_GROUP_ID);
	$addedArr = json_decode($added);
	print_r($addedArr);

	date_default_timezone_set('UTC');
	$res = feedFB(FB_GROUP_ID,  strtotime("-1 hour"));
	print_r(json_decode($res));

	if (isset($addedArr->id)) {
		echo("Added: " . $addedArr->id . "\n");
		deletePost($addedArr->id);
	}
}

function feedFB($group_id, $since_strtotime) {
	$url = "https://graph.facebook.com/" .
			$group_id.
			"/feed?fields=picture,caption,name,message,from".
			"&limit=9999".
			"&since=" . $since_strtotime . 
			"&access_token=" . FB_ACCESS_TOKEN;

	$res = file_get_contents($url);
	return $res;
}

/*
 * Simple post for testing
 */
function addPost($group_id) {
	$data = [];
	$data['message'] = "[deleteme]";
	$data['access_token'] = FB_ACCESS_TOKEN;
	$post_url = 'https://graph.facebook.com/'.$group_id.'/feed';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $post_url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}

/* 
 * https://developers.facebook.com/docs/graph-api/reference/v2.6/post
 *
 * Permissions:
 *   To delete a user's post, a user access token with publish_actions permission is required.
 *   To delete a Page's post a Page access token and publish_pages permission is required.
 *   To delete a User's post on Page a Page access token is required.
 */
function deletePost($postid) {
	$post_url = 'https://graph.facebook.com/'. $postid .
				"?access_token=" . FB_ACCESS_TOKEN;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $post_url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$ret = curl_exec($ch);
	curl_close($ch);

	echo("Deleting " . $postid . "\n");
	echo($ret . "\n");
}
?>