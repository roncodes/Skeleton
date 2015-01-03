<?php
/**
 * The test endpoint for testing
 */
$endpoint = $skeleton->new->Endpoint('Test', function($skeleton, $endpoint, $request) {
	if($request->method() == 'GET') {
		Skeleton_Helper::say('Hello World!');
	}
});