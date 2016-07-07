<?php
/*
 * This is the main program.
 *  Run this periodically (using cron).
 */
require_once ("const.php");

require_once ("FBUtil.php");
require_once ("gVisionAPI.php");

date_default_timezone_set('UTC');

// Get group id
$fb_group_id = FB_GROUP_ID;
$time_str = "-100 minutes";

if ($argc >= 2) {
	$fb_group_id = $argv[1];

	if ($argc == 3) {
	    $time_str = $argv[2];
	}
} 

// get FB feed first, assuming the cron job is at least 10 minutes
$fbFeed = feedFB($fb_group_id, strtotime($time_str));
$feedArr = json_decode($fbFeed);

// loop and check the message and image
$index = 0;
$spam_count = 0;
foreach ($feedArr->data as $key => $value) {
	$index++;

    echo ("Checking post: https://facebook.com/" . $value->id . "\n");

	// test (delete post with [deleteme])
	// TODO: need to implement text based spam filtering
	if (strstr($value->message, "[deleteme]")!==FALSE) {
		reportAndDelete($value);
		$spam_count++;
		continue;		
	}

	// Let's focus on the picture first.
	if (!isset($value->full_picture)) {
		echo (" - No full_picture!\n");
		continue;
	}

    echo (" - Checking the full_picture: " . $value->full_picture);

	// Check with google vision API
	$imgRes = doGoogleVisionRequest($value->full_picture);
	$imgResArr = json_decode($imgRes);
	$value->gRes = $imgResArr->responses[0]->safeSearchAnnotation;

	// image spam??
	if (is_safe($value->gRes)) {
		echo("    [OK]\n");
	} else {
		echo("    [SPAM]\n");
	    $spam_count++;
		reportAndDelete($value);		
	}
}

echo ("Checked $index posts and $spam_count spams!\n");

function reportAndDelete($post) {
	if (isset($post->id)) {
		$post->fb_url = "https://www.facebook.com/" . $post->id;
		// report
		mail(ADMIN_EMAIL, "Facebook SPAM Warning!", print_r($post, true));

		if (SHOULD_DELETE) {
			// delete (be careful! It really deletes.)
			deletePost($post->id);
		}
	}
}

/*
UNKNOWN	Unknown likelihood.
VERY_UNLIKELY	The image very unlikely belongs to the vertical specified.
UNLIKELY	The image unlikely belongs to the vertical specified.
POSSIBLE	The image possibly belongs to the vertical specified.
LIKELY	The image likely belongs to the vertical specified.
VERY_LIKELY	The image very likely belongs to the vertical specified.
*/
function is_safe_likelihood($likelihood) {
	return !($likelihood == "POSSIBLE" ||
		$likelihood == "LIKELY" ||
		$likelihood == "VERY_LIKELY");
}

/*
[adult] => VERY_UNLIKELY
[spoof] => VERY_UNLIKELY
[medical] => VERY_UNLIKELY
[violence] => VERY_UNLIKELY
*/
function is_safe($res) {
	return is_safe_likelihood($res->adult) &&
		//is_safe_likelihood($res->spoof) &&   // Check for adults and violence 
		//is_safe_likelihood($res->medical) && // for now
		is_safe_likelihood($res->violence); 
}

?>
