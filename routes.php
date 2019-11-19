<?php
return [
  'GET:' => [
    'controller' => 'IndexController@index',
  ],
  'GET:/signup' => [
    'controller' => 'AuthController@sign_up',
  ],
  'POST:/signup' => [
    'controller' => 'AuthController@create_user',
  ],
  'GET:/signup/confirm' => [
    'controller' => 'AuthController@sign_up_confirm',
  ],
  'GET:/login' => [ 
    'controller' => 'AuthController@login',
  ],
  'POST:/login' => [ 
    'controller' => 'AuthController@login_check',
  ],
  'GET:/logout' => [ 
    'controller' => 'AuthController@logout',
  ],
  'GET:/login/forget' => [
    'controller' => 'AuthController@forget_password',
  ],
  'POST:/login/forget' => [
    'controller' => 'AuthController@forget_password_confirm',
  ],
  'GET:/login/reset' => [
    'controller' => 'AuthController@reset_password',
  ],
  'POST:/login/reset' => [
    'controller' => 'AuthController@reset_password_confirm',
  ],
  'GET:/api/get_categories' => [
    'controller' => 'ApiController@categories',
  ],
  'GET:/api/get_threads' => [
    'controller' => 'ApiController@threads',
  ],
  'GET:/thread' => [
    'controller' => 'ForumThreadController@forum',
  ],
  'GET:/thread/editor' => [
    'controller' => 'ForumThreadController@editor',
  ],
];
