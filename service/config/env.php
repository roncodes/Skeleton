<?php
// Establish environment
if(file_exists(FCPATH . '.production')) {
	$config->set('environment', 'production');
} elseif(file_exists(FCPATH . '.testing')) {
	$config->set('environment', 'testing');
} elseif(file_exists(FCPATH . '.development')) {
	$config->set('environment', 'development');
} else {
	$config->set('environment', 'development');
}
// Save environment
define('ENVIRONMENT', $config->get('environment'));