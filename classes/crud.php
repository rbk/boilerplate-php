<?php
/**
 * Query system for basic CRUD operations
 * @todo Abstract query building
 */
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
    $this->processGetParams();
    // this is an orm file, not a api route
  }

  public function processGetParams()
  {
    $column_names_available = $this->getColumnNames();

    $this->columns = [];
    $this->values = [];
    $this->update = [];

    foreach( $_GET as $key => $value ) {
      if (in_array($key, $column_names_available)) {
        $escaped_key = mysqli_real_escape_string($this->connection,$key);
        $escaped_value = mysqli_real_escape_string($this->connection,$value);
        $this->columns[] = "`" . $escaped_key . "`";
        $this->values[] = "'" . $escaped_value . "'";
        $this->update[] = "`" . $escaped_key . "` = '" .  $escaped_value . "'";
      }
    }
    $this->columns = implode(',', $this->columns);
    $this->values = implode(',', $this->values);
    $this->update = implode(',', $this->update);

  }

  public function getColumnNames()
  {
    $column_names_available = [];
    foreach( $this->columns as $col => $type ) {
      $column_names_available[] = $col;
    }
    return $column_names_available;

  }

  // GET
  public function index()
  {
    $response = [];
    $sql = "select * from $this->tablename";
    $result = $this->connection->query($sql);
    if ($result) {
      while($row = $result->fetch_assoc()) {
        $response[] = $row;
      }
      $this->display_result($response);
    }
  }

  // POST
  public function create()
  {
    $sql = "INSERT INTO `$this->tablename` ($this->columns) VALUES ($this->values)";
    $result = $this->connection->query($sql);
    if ($result) {
      $this->display_result($this->connection->insert_id);
    } else {
      $this->display_result(array(
        'message' => 'There was a problem inserting into the database.',
        'error' => 1,
        'query' => $sql,
      ));
    }
  }

  // GET
  public function read()
  {
    $response = [];
    $sql = "select * from $this->tablename where id = " . $_GET['id'];
    $result = $this->connection->query($sql);
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $response[] = $row;
      }
      $this->display_result($response);
    } else {
      $this->display_result(array(
        'message' => 'No results found.',
        'row_count' => 0,
        'query' => $sql,
      ));
    }

  }

  // POST
  public function update()
  {
    $id = mysqli_real_escape_string($this->connection,$_GET['id']);
    $sql = "UPDATE $this->tablename SET $this->update WHERE id=$id";
    $result = $this->connection->query($sql);
    if ($result) {
      $this->display_result(array(
        'message' => 'Update row from ' . $this->tablename . ' with id ' . $id,
        'updated' => 1,
        'error' => 0,
        'query' => $sql,
      ));
    } else {
      $this->display_result(array(
        'message' => 'Something went wrong with the update.',
        'updated' => 0,
        'error' => 1,
        'query' => $sql,
      ));
    }
  }

  // DELETE
  public function delete()
  {
    if (isset($_GET['id']) && isset($_GET['delete'])) {
      $id = $_GET['id'];
      $sql = "DELETE FROM $this->tablename WHERE id = $id";
      $result = $this->connection->query($sql);
      if ($result) {
        $this->display_result(array(
          'message' => 'Deleted row from ' . $this->tablename . ' with id ' . $id,
          'deleted' => 1,
          'error' => 0,
          'query' => $sql,
        ));
      }
    }
  }

  /**
   * Search
   * @todo Build this query handler
   */
  public function search()
  {
    $this->display_result('Search');
  }

  /**
   * Output JSON everwhere
   * @todo Maybe make this the default but optionally something else like XML
   */
  public function display_result($arg)
  {
    if (is_array($arg)) {
      echo json_encode($arg);
    } else {
      echo json_encode(array(
        'result' => $arg
      ));
    }
  }

}


 ?>
