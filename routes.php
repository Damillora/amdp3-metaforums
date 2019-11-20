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
  'GET:/api/favorite' => [
    'controller' => 'ApiController@favorite',
  ],
  'GET:/api/favorite_num' => [
    'controller' => 'ApiController@favorite_num',
  ],
  'GET:/api/get_reports' => [
    'controller' => 'ApiController@reports',
  ],
  'GET:/thread' => [
    'controller' => 'ForumThreadController@forum',
  ],
  'GET:/thread/editor' => [
    'controller' => 'ForumThreadController@editor',
  ],
  'GET:/thread/moderating_editor' => [
    'controller' => 'ForumThreadController@moderating_editor',
  ],
  'POST:/thread/process' => [
    'controller' => 'ForumThreadController@process',
  ],
  'POST:/thread/moderate' => [
    'controller' => 'ForumThreadController@moderate',
  ],
  'GET:/thread/unlock' => [
    'controller' => 'ForumThreadController@unlock',
  ],
  'GET:/profile' => [
    'controller' => 'AccountController@profile',
  ],
  'GET:/me' => [
    'controller' => 'AccountController@me',
  ],
  'POST:/me/update' => [
    'controller' => 'AccountController@update',
  ],
  'GET:/moderation' => [
    'controller' => 'IndexController@moderation',
  ],
];
