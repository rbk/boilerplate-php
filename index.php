<?php

require('classes/app.php');
require('classes/db.php');

$app_config = array(
  'debug' => false,
  'database' => array(
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'root',
    'database' => 'boilerplate_php'
  ),
  'models' => array(
  //   array(
  //     'name' => 'quotes',
  //     'columns' => [
  //       'quote' => 'varchar(255)',
  //       'source' => 'varchar(255)'
  //     ]
  //   ),
  //   array(
  //     'name' => 'users',
  //     'columns' => [
  //       'name' => 'varchar(255)',
  //       'email' => 'varchar(255)',
  //       'password_hash' => 'varchar(255)'
  //     ]
  //   ),
  array(
    'name' => 'lists',
    'columns' => [
      'title' => 'varchar(255)',
      'description' => 'text(500)'
      ]
    ),
    array(
      'name' => 'todos',
      'columns' => [
        'text' => 'varchar(255)',
        'status' => 'varchar(50)',
        'completed_date' => 'date',
        'list_id' => 'int',
      ],
      'foreign_key' => 'list_id',
      'references' => 'lists',
      'sample_sql_references' => 'FOREIGN KEY (list_id) REFERENCES lists(id)',
    ),
  )
);

$app = new App($app_config);
$app->init();

?>
