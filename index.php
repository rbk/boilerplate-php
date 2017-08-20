<?php
require('classes/app.php');
require('classes/db.php');

$app_config = array(
  'database' => array(
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'root',
    'database' => 'boilerplate_php'
  ),
  'models' => array(
    array(
      'name' => 'quotes',
      'columns' => [
        'quote' => 'varchar(255)',
      ]
    ),
    array(
      'name' => 'users',
      'columns' => [
        'name' => 'varchar(255)',
        'email' => 'varchar(255)',
      ]
    ),
  )
);

$app = new App($app_config);
$app->init();


 ?>
