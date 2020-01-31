<?php

$app->get('/email_category', 'App\Master\MasterController:emailCategory')->add($auth);

$app->group('/api/v1/master', function () use ($app, $auth, $accessApi) {
  $app->get('/email_category', 'App\Master\MasterController:getEmailCategory')->add($auth);
  $app->post('/create_emailcategory', 'App\Master\MasterController:createEmailCategory')->add($auth);
  $app->post('/update_emailcategory', 'App\Master\MasterController:updateEmailCategory')->add($auth);

  // nameprefix
  $app->get('/name_prefix', 'App\Master\MasterController:ViewNamePrefix')->add($auth);
  $app->post('/all', 'App\Master\MasterController:all')->add($auth);
  $app->post('/update', 'App\Master\MasterController:update')->add($auth);
  $app->post('/create', 'App\Master\MasterController:create')->add($auth);
  $app->post('/delete', 'App\Master\MasterController:delete')->add($auth);

  // career
  $app->get('/career', 'App\Master\MasterController:ViewCareer')->add($auth);
  $app->post('/career/all', 'App\Master\MasterController:careerAll')->add($auth);
  $app->post('/career/update', 'App\Master\MasterController:careerUpdate')->add($auth);
  $app->post('/career/create', 'App\Master\MasterController:careerCreate')->add($auth);
  $app->post('/career/delete', 'App\Master\MasterController:careerDelete')->add($auth);

  // relation
  $app->get('/relation', 'App\Master\MasterController:ViewRelation')->add($auth);
  $app->post('/relation/all', 'App\Master\MasterController:relationAll')->add($auth);
  $app->post('/relation/update', 'App\Master\MasterController:relationUpdate')->add($auth);
  $app->post('/relation/create', 'App\Master\MasterController:relationCreate')->add($auth);
  $app->post('/relation/delete', 'App\Master\MasterController:relationDelete')->add($auth);

  // education
  $app->get('/education', 'App\Master\MasterController:ViewEducation')->add($auth);
  $app->post('/education/all', 'App\Master\MasterController:educationAll')->add($auth);
  $app->post('/education/update', 'App\Master\MasterController:educationUpdate')->add($auth);
  $app->post('/education/create', 'App\Master\MasterController:educationCreate')->add($auth);
  $app->post('/education/delete', 'App\Master\MasterController:educationDelete')->add($auth);

  // classroom
  $app->get('/classroom', 'App\Master\MasterController:ViewClassroom')->add($auth);
  $app->post('/classroom/all', 'App\Master\MasterController:classroomAll')->add($auth);
  $app->post('/classroom/update', 'App\Master\MasterController:classroomUpdate')->add($auth);
  $app->post('/classroom/create', 'App\Master\MasterController:classroomCreate')->add($auth);
  $app->post('/classroom/delete', 'App\Master\MasterController:classroomDelete')->add($auth);

  // editable
  $app->get('/getall/relation', 'App\Master\MasterController:getAllRelation')->add($auth);
});