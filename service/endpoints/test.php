<?php
/**
 * The test endpoint for testing
 */
$endpoint = $skeleton->new->Endpoint('Test', function($s, $e) {
	echo $e->dbTable;
})->setTable('users');
$endpoint->output();