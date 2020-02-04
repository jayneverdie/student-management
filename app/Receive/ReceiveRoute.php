<?php

$app->get('/receive/view', 'App\Receive\ReceiveController:ViewReceive')->add($auth);

$app->group('/api/v1/receive', function () use ($app, $auth, $accessApi) {
	$app->post('/all', 'App\Receive\ReceiveController:all')->add($auth);
	$app->post('/load/student/by/parent', 'App\Receive\ReceiveController:getStudentByParent')->add($auth);
	$app->post('/load/student/by/id', 'App\Receive\ReceiveController:getStudentById')->add($auth);
	$app->post('/load/relation/by/student', 'App\Receive\ReceiveController:getRelationByStudent')->add($auth);
	$app->post('/load/hours', 'App\Receive\ReceiveController:getHours')->add($auth);
	$app->post('/load/minutes', 'App\Receive\ReceiveController:getMinutes')->add($auth);
	$app->post('/send', 'App\Receive\ReceiveController:Send')->add($auth);
	$app->post('/read', 'App\Receive\ReceiveController:Read')->add($auth);
	$app->post('/delete', 'App\Receive\ReceiveController:Delete')->add($auth);
});