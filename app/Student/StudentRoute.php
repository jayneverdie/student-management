<?php

$app->get('/student/view', 'App\Student\StudentController:ViewStudent')->add($auth);

$app->group('/api/v1/student', function () use ($app, $auth, $accessApi) {
	$app->post('/all', 'App\Student\StudentController:all')->add($auth);
	$app->post('/load/namefix', 'App\Student\StudentController:getNameFix')->add($auth);
	$app->post('/load/sex', 'App\Student\StudentController:getSex')->add($auth);
	$app->post('/load/education', 'App\Student\StudentController:getEducation')->add($auth);
	$app->post('/load/classroom', 'App\Student\StudentController:getClassroom')->add($auth);
	$app->post('/create', 'App\Student\StudentController:create')->add($auth);
	$app->get('/readcard', 'App\Student\StudentController:readCard')->add($auth);
});