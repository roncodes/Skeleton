# Skeleton REST Service Framework

Skeleton is a REST Service framework that focuses on making RESTful services quick and easy. Skeleton is not a typical MVC framework, it is made to create REST services with ease. So there is no concept of views and controllers. Instead of controllers, Skeleton uses "Endpoints". There are no views, only data output.

## Getting Started ##
    git clone git@github.com:theprestig3/Skeleton.git newService
	cd newService
	touch .development

## Inside an Endpoint ##
	# Inside the users endpoint (endpoints/users.php)
	<?php
	$endpoint = $skeleton->new->Endpoint('users')->setTable('users');
	// URI: /users
	$endpoint->get(function($e) {
		$allUsers = $e->model->getAll();
		// Will output all users in JSON
		$e->out($allUsers);
		// To output all users in XML
		// $e->outputAs('xml')->out($allUsers);
	});
	// URI: /users/id
	$endpoint->get(['userId'], function($e) {
		$user = $e->model->get($e->userId);
		// Output user data in JSON
		$e->out($user);
	});

## Using RedBean ORM or Skeleton Models ##

Skeleton comes with RedBean for ORM, you can use either RedBean or the Skeleton Model anytime, here's an example of using ORM or the Skeleton Model to accomplish the same task.

    <?php
	$skeleton->new->Endpoint(funtion($e) {
		// Using a Skeleton Model
		$e->load->model('User_Model', 'users');
		$allUsers = $e->users->getAll();
		// Using RedBean ORM
		$allUsers = R::findAll('users');
	});

## Scaffolding Endpoints ##

Skeleton endpoints can use scaffold responses for typical request such as GET, POST, PUT, DELETE. To make use of a generated response, check out the following example.

    <?php
	$skeleton->new->Endpoint('users')->setTable('users')->factoryOutput();
	// GET: /users -- will result in a JSON response of all users
	// GET: /users/1 -- will result in JSON response of user with ID 1
	// POST: /users -- will create a user
	// DELETE: /users/1 -- will delete user with ID 1
	// PUT /users/1 -- will update user with ID 1

## Todo ##

Skeleton is a baby so there is still a ton to do, this list will probably continue to grow.

- Authentication and Permissions
- More Documentation and Examples
- Unit Tests
- Router
- Skeleton Socket Server
- Skeleton Scaffolding
		

	