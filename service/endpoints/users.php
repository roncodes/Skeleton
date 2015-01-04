<?php
/**
 * The test endpoint for USERS
 */
$endpoint = $skeleton->new->Endpoint('users')->setTable('users');

// $endpoint->get(function($skeleton, $endpoint, $request) {
// 	$endpoint->out($endpoint->model->getAll());
// });

// $endpoint->get('$1', function($skeleton, $endpoint, $request) {
// 	$endpoint->out($endpoint->model->get($request->segment(2)));
// });

$endpoint->get(['userId', 'testVar' => null], function($skeleton, $endpoint, $request) {
	$endpoint->out($endpoint->userId);
});
