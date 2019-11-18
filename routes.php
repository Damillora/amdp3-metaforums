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
  'GET:/signup/success' => [
    'controller' => 'AuthController@sign_up_success',
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
];
