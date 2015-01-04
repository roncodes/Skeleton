<?php
/**
 * The test endpoint for testing
 */
$endpoint = $skeleton->new->Endpoint('users')->setTable('users');

$endpoint->get('/', function($skeleton, $endpoint, $request) {
	$endpoint->out($endpoint->model->getAll());
});

$endpoint->get('/$1', function($skeleton, $endpoint, $request) {
	$endpoint->out($endpoint->model->get($request->param('id')));
});
