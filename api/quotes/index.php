<?php

class Quotes
{
  public $connection;
  public $base_dir;
  public $columns;

  function __construct($connection, $base_dir, $columns)
  {
    $this->base_dir = $base_dir;
    $this->connection = $connection;
    $this->columns = $columns;
    // this is an orm file, not a api route
  }

  // GET
  public function index()
  {
    $sql = "select * from quotes";
    echo 'Index ' . $sql;
  }

  // POST
  public function create($args)
  {

    foreach( $this->columns as $col => $type ) {
      echo $col;
      if (in_array($col, $_GET[$col])) {
        echo $col;
      }
    }

    $quote = mysqli_real_escape_string($this->connection, $_GET['quote']);

    $sql = "INSERT INTO quotes (quote) values ('$quote')";
    // $result = $this->connection->query($sql);
    print_r($result);
    echo $sql;
    print_r($_GET);
  }

  // GET
  public function read()
  {
    $sql = "select * from quotes where id = " . $_GET['id'];
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
