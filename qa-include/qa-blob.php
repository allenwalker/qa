<?php

/*
	Question2Answer 1.4.3 (c) 2011, Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-include/qa-blob.php
	Version: 1.4.3
	Date: 2011-09-27 18:06:46 GMT
	Description: Response to blob requests, outputting blob from the database


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/


//	Ensure no PHP errors are shown in the Ajax response

	@ini_set('display_errors', 0);


//	Load the QA base file which sets up a bunch of crucial functions

	require 'qa-base.php';


//	Output the blob in question

	require_once QA_INCLUDE_DIR.'qa-db-blobs.php';

	$blob=qa_db_blob_read(qa_get('qa_blobid'));

	header('Cache-Control: max-age=2592000, public'); // allows browsers and proxies to cache the blob

	switch ($blob['format']) {
		case 'jpeg':
		case 'jpg':
			header('Content-Type: image/jpeg');
			break;
			
		case 'gif':
			header('Content-Type: image/gif');
			break;
			
		case 'png':
			header('Content-Type: image/png');
			break;
			
		case 'swf':
			header('Content-Type: application/x-shockwave-flash');
			break;
			
		default:
			$filename=preg_replace('/[^A-Za-z0-9 \\._-]/', '-', $blob['filename']); // for compatibility with HTTP headers and all browsers
			
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			break;
	}	
	
	echo $blob['content'];


/*
	Omit PHP closing tag to help avoid accidental output
*/