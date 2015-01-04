<?php
/**
 * The test endpoint for USERS
 */
$endpoint = $skeleton->new->Endpoint('users')->setTable('users');

// $endpoint->get(function($skeleton, $endpoint, $request) {
// 	$endpoint->out($endpoint->model->getAll());
// });

// $endpoint->get('*', function($skeleton, $endpoint, $request) {
// 	$endpoint->out($endpoint->model->get($request->param('id')));
// });

$endpoint->get(['userId', 'testVar' => null], function($skeleton, $endpoint, $request) {
	$endpoint->out($endpoint->userId);
});
