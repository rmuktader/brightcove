<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require('PHP-MAPI-Wrapper/bc-mapi.php');

$bc = new BCMAPI('XXXX', 'XXXX');

$assetPath = dirname(__FILE__)  . '/assets/';



/* Upload a video */
$vidFile = $assetPath . 'video.mpg';

$metadata = array(
	'name' => 'train',
    'shortDescription' => 'A train',
	'tags' => array('tag1','tag2'),
	//'startDate'	=> '1374843668000', //epoch timestamp in miliseconds. completely optional
	//'endDate'   => '1375102999000',
);

try {
	$vidId = $bc->createMedia('video', $vidFile, $metadata);
} catch (Exception $e) {
	echo $e->getMessage();
	exit;
}


/* Create a playlist */
$metaData = array(
    'name' => 'Temp Playlist',
    'shortDescription' => 'This is a test.',
    'playlistType' => 'explicit'
);
try {
	$playlistId = $bc->createPlaylist('video', $metaData);
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}


/* Add video to a playlist */
try {
	$bc->addToPlaylist( $playlistId, array($vidId));
} catch (Exception $e) {
	echo $e->getMessage();
	exit;
}


/* Add an overlay. must be gif or png */
$overlay = $assetPath . 'overlay.gif';

$metadata = array(
	'tooltip' => 'My tool tip',
	'linkURL' => 'http://google.com',
	'image'	=> array(
		'type' => 'LOGO_OVERLAY',
		'resize' => 'true',
		'diplayname' => 'My Logo Overlay',
	),	
);
$bc->createOverlay($overlay, $metadata, $vidId);


/* Add a video still */
$still = $assetPath . 'still.jpg';
$metadata = array(
	'type' => 'VIDEO_STILL',
	'displayName' => 'A still',
);

$bc->createImage('video', $still, $metadata, $vidId);


/* Add a thumbnail */
$thumb = $assetPath . 'thumb.jpg';

$metadata = array(
    'type' => 'THUMBNAIL',
    'displayName' => 'A thumbnail',
);

$bc->createImage('video', $thumb, $metadata, $vidId);


/* Get the status of the video */
$status = $bc->getStatus('video', $vidId);

echo $status . PHP_EOL; // print UPLOADING, PROCESSING, OR COMPLETED


