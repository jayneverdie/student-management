<?php

$app->get('/parent/view', 'App\Parent\ParentController:ViewParent')->add($auth);

$app->group('/api/v1/parent', function () use ($app, $auth, $accessApi) {
	$app->post('/all', 'App\Parent\ParentController:all')->add($auth);
	$app->post('/load/namefix', 'App\Parent\ParentController:getNameFix')->add($auth);
	$app->post('/load/sex', 'App\Parent\ParentController:getSex')->add($auth);
	$app->post('/load/education', 'App\Parent\ParentController:getEducation')->add($auth);
	$app->post('/load/career', 'App\Parent\ParentController:getCareer')->add($auth);
	$app->post('/create', 'App\Parent\ParentController:create')->add($auth);
	$app->post('/update', 'App\Parent\ParentController:update')->add($auth);
	$app->get('/readcard', 'App\Parent\ParentController:readCard')->add($auth);
	$app->get('/loadfile', 'App\Parent\ParentController:loadFile')->add($auth);
	$app->post('/map', 'App\Parent\ParentController:getMapRelation')->add($auth);
	$app->post('/load/relation', 'App\Parent\ParentController:getRelation')->add($auth);
	$app->post('/create/map', 'App\Parent\ParentController:createMap')->add($auth);
	$app->post('/relation/update', 'App\Parent\ParentController:mapUpdate')->add($auth);
	$app->post('/map/delete', 'App\Parent\ParentController:mapDelete')->add($auth);
	$app->post('/changeimg', 'App\Parent\ParentController:changeImg')->add($auth);
});