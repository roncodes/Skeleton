<?php
/**
 * The test endpoint for USERS
 */
$endpoint = $skeleton->new->Endpoint('users');

$endpoint->get(function() {
	echo 'Yolo!';
});

$endpoint->get(':id', function($e) {
	var_dump($e->id);
});

// $endpoint->get(function($skeleton, $endpoint, $request) {
// 	$endpoint->out($endpoint->model->getAll());
// });

// $endpoint->get('$1', function($skeleton, $endpoint, $request) {
// 	$endpoint->out($endpoint->model->get($request->segment(2)));
// });

// $endpoint->get(['userId', 'testVar' => 'Hello World'], function($e) {
// 	$e->load->model('User_Model', 'users');
// 	$users = $e->users->get($e->userId);
// 	var_dump($users);
// });
