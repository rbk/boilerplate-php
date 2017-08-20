<?php

class Users
{
  public $connection;
  public $base_dir;

  function __construct($connection, $base_dir)
  {
    $this->base_dir = $base_dir;
    $this->connection = $connection;
    // echo $this->base_dir;
    // print_r($this->connection);
    // this is an orm file, not a api route
  }

  // GET
  public function index()
  {
    $sql = "select * from users";
    echo 'Index ' . $sql;
  }

  // POST
  public function create($args)
  {
    $sql = "insert into quotes values (1,2,3)";
  }

  // GET
  public function read()
  {
    $sql = "select * from users where id = " . $_GET['id'];
    echo $sql;
  }

  // POST
  public function update()
  {
    $sql = "update quote where id = 1";
  }

  // DELETE
  public function delete()
  {
    $sql = "delete node where id = 1";
  }

  public function init()
  {

  }

}


 ?>
