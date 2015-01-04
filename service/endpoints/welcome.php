<?php
$skeleton->new->Endpoint(function() {
	JSON::out(array(
		'status' => 'success',
		'message' => 'Welcome to Skeleton API 0.0.1'
	));
});