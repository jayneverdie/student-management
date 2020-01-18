<?php

$app->group('/automail/api', function() use ($app, $auth, $accessPage) {
	$app->get('/api/booking', 'App\Api\ApiController:booking');
	// $app->get('/tbc/si', 'App\Aot\AotController:tbcSI');
	// $app->get('/tbc/daily', 'App\Aot\AotController:tbcDaily');
});
