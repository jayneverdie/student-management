<?php

$app->get('/email_category', 'App\Master\MasterController:emailCategory')->add($auth);

$app->group('/api/v1/master', function () use ($app, $auth, $accessApi) {
  $app->get('/email_category', 'App\Master\MasterController:getEmailCategory')->add($auth);
  $app->post('/create_emailcategory', 'App\Master\MasterController:createEmailCategory')->add($auth);
  $app->post('/update_emailcategory', 'App\Master\MasterController:updateEmailCategory')->add($auth);

  $app->get('/name_prefix', 'App\Master\MasterController:ViewNamePrefix')->add($auth);
  $app->post('/all', 'App\Master\MasterController:all')->add($auth);
  $app->post('/update', 'App\Master\MasterController:update')->add($auth);
  $app->post('/create', 'App\Master\MasterController:create')->add($auth);
  $app->post('/delete', 'App\Master\MasterController:delete')->add($auth);

  $app->get('/career', 'App\Master\MasterController:ViewCareer')->add($auth);
  $app->post('/career/all', 'App\Master\MasterController:careerAll')->add($auth);
  $app->post('/career/update', 'App\Master\MasterController:careerUpdate')->add($auth);
  $app->post('/career/create', 'App\Master\MasterController:careerCreate')->add($auth);
});