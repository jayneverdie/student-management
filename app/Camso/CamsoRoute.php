<?php

$app->group('/automail/camso', function() use ($app, $auth, $accessPage) {
	$app->get('/run', 'App\Camso\CamsoController:runCamso');
});

