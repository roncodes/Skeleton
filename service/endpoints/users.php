<?php
/**
 * The test endpoint for USERS
 */
$endpoint = $skeleton->new->Endpoint('users')->setTable('users');

/**
 * GET: /users
 * @return JSON of All Users
 */
$endpoint->get(function($e) {
	$e->out($e->model->getAll());
});

/**
 * GET: /users/:id
 * @return  JSON of User by ID
 */
$endpoint->get(['userId'], function($e) {
	echo $e->userId;
});
