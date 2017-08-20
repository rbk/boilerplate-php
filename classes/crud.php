<?php

class Crud
{
  public $connection;
  public $base_dir;
  public $columns;
  public $tablename;

  function __construct($connection, $base_dir, $columns, $tablename)
  {
    $this->base_dir = $base_dir;
    $this->connection = $connection;
    $this->columns = $columns;
    $this->tablename = $tablename;
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
    $cols = [];
    $values = [];

    $column_names_available = [];
    foreach( $this->columns as $col => $type ) {
      $column_names_available[] = $col;
    }

    // print_r($column_names_available);

    foreach( $_GET as $key => $value ) {
      if (in_array($key, $column_names_available)) {
        $cols[] = "`" . mysqli_real_escape_string($this->connection,$key) . "`";
        $values[] = "'" . mysqli_real_escape_string($this->connection,$value) . "'";
      }
    }
    $cols = implode(',', $cols);
    $values = implode(',', $values);
    $sql = "INSERT INTO `$this->tablename` ($cols) VALUES ($values)";
    $result = $this->connection->query($sql);
    if ($result) {
      echo $this->connection->insert_id;
    }
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
