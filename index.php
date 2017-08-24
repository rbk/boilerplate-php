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
    array(
      'name' => 'todos',
      'columns' => [
        'text' => 'varchar(255)',
        'status' => 'varchar(50)',
        'completed_date' => 'date'
      ]
    ),
  )
);

$app = new App($app_config);
$app->init();
die();
if (!isset($_POST['text'])) :
 ?>
 <form class="" action="" method="POST">
   <input type="text" name="text" value="my todo text">
   <input type="text" name="status" value="not complete">
   <input type="submit" >
 </form>

 <?php endif; ?>
