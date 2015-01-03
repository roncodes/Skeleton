<?php
/**
 * The test endpoint for testing
 */
$endpoint = $skeleton->new->Endpoint('Test', function($s, $e) {
	$fields = R::inspect('users');
	var_dump($fields);
})->setTable('users');