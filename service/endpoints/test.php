<?php
/**
 * The test endpoint for testing
 */
$skeleton->new->Endpoint('Test', function($s, $e) {
	echo $e->dbTable;
})->setTable('users');