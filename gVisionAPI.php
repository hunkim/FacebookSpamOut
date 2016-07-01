<?php
require_once ("const.php");

if ($argv[0]=="gVisionAPI.php") {
    $img = "http://home.cse.ust.hk/~hunkim/images/Sungkim2.png";
    if ($argc==2) {
        $img = $argv[1];
    }

    _test($img);
}

function _test($img) {
    echo("Checking $img\n");
    $res = doGoogleVisionRequest($img);
    echo($res);
}

/* https://cloud.google.com/vision/reference/rest/v1/images/annotate#type
TYPE_UNSPECIFIED        Unspecified feature type.
FACE_DETECTION  Run face detection.
LANDMARK_DETECTION      Run landmark detection.
LOGO_DETECTION  Run logo detection.
LABEL_DETECTION Run label detection.
TEXT_DETECTION  Run OCR.
SAFE_SEARCH_DETECTION   Run various computer vision models to compute image safe-search properties.
IMAGE_PROPERTIES        Compute a set of properties about the image (such as the image's dominant colors)
*/
function doGoogleVisionRequest($filename, $type="SAFE_SEARCH_DETECTION") {
        $request['requests'] = ['image'=>[], 'features'=>["type"=>$type,  "maxResults"=>999]];

        $data = file_get_contents($filename);
        if ($data===FALSE) {
                die("Invalid file/url: $filename\n");
        }

        $request['requests']['image']['content'] =  base64_encode($data);
        $payload = json_encode($request);

        $ch = curl_init(GOOGLE_VISION_END_POINT . GOOGLE_VISION_KEY);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
}
?>