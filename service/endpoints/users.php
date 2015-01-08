<?php
/**
 * The test endpoint for USERS
 */
$endpoint = $skeleton->new->Endpoint('users')->setTable('users');

/**
 * GET: /users
 * @return JSON of All Users
 */
$endpoint->get(function($e, $skeleton) {
	$allUsers = $e->model->getAll();
	$e->out($allUsers);
});

/**
 * GET: /users/:userId
 * @return JSON of User by ID
 */
$endpoint->get(['userId'], function($e) {
	$user = $e->model->get($e->userId);
	$e->out($user);
});

/**
 * GET: /users?userId=
 * @return JSON of User by ID
 */
$endpoint->get('?userId=:id', function($e) {
	echo 'yolo';
});

/**
 * GET: /users/:id/profile
 * @return XML of profile by User ID
 */
$endpoint->get(':id/profile', function($e) {
	$e->load->model('Profile_Model', 'profiles');
	$profile = $e->profiles->getByUserId($e->id);
	$e->outputAs('xml')->out($profile);
});
