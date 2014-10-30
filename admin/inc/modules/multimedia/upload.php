<?php
/**
 * Multiple file upload with progress bar php and jQuery
 * 
 * @author Resalat Haque
 * @link http://www.w3bees.com
 * 
 */

$max_size = 1024*200; // 200kb
$extensions = array('jpeg', 'jpg', 'png');
$dir = 'uploads/';
$count = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_FILES['files']))
{
	// loop all files
	foreach ( $_FILES['files']['name'] as $i => $name )
	{
		// if file not uploaded then skip it
		if ( !is_uploaded_file($_FILES['files']['tmp_name'][$i]) )
			continue;

	    // skip large files
		if ( $_FILES['files']['size'][$i] >= $max_size )
			continue;

		// skip unprotected files
		if( !in_array(pathinfo($name, PATHINFO_EXTENSION), $extensions) )
			continue;

		// now we can move uploaded files
	    if( move_uploaded_file($_FILES["files"]["tmp_name"][$i], $dir . $name) )
	    	$count++;
	}
}

echo json_encode(array('count' => $count));

?>