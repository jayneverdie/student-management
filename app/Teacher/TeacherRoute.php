<?php

$app->get('/teacher/view', 'App\Teacher\TeacherController:ViewTeacher')->add($auth);

$app->group('/api/v1/teacher', function () use ($app, $auth, $accessApi) {
	$app->post('/all', 'App\Teacher\TeacherController:all')->add($auth);
	$app->post('/load/namefix', 'App\Teacher\TeacherController:getNameFix')->add($auth);
	$app->post('/load/sex', 'App\Teacher\TeacherController:getSex')->add($auth);
	$app->post('/load/education', 'App\Teacher\TeacherController:getEducation')->add($auth);
	$app->post('/load/career', 'App\Teacher\TeacherController:getCareer')->add($auth);
	$app->post('/create', 'App\Teacher\TeacherController:create')->add($auth);
	$app->get('/readcard', 'App\Teacher\TeacherController:readCard')->add($auth);
	$app->post('/map', 'App\Teacher\TeacherController:getMapClassroom')->add($auth);
	$app->post('/student', 'App\Teacher\TeacherController:getMapStudent')->add($auth);
	$app->post('/create/map', 'App\Teacher\TeacherController:createMap')->add($auth);
	$app->post('/load/academicyear', 'App\Teacher\TeacherController:getAcademicyear')->add($auth);
	$app->post('/map/delete', 'App\Teacher\TeacherController:mapDelete')->add($auth);
});