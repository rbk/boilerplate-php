<?php
/**
 * Query system for basic CRUD operations
 * Creates SQL queries based on parameters
 * @todo Abstract query building
 */
class Crud
{
  public $connection;
  public $base_dir;
  public $columns;
  public $tablename;
  public $params;
  public $method;
  public $sql;
  public $debug;

  function __construct($connection, $base_dir, $columns, $tablename, $params, $debug)
  {
    $this->base_dir = $base_dir;
    $this->connection = $connection;
    $this->columns = $columns;
    $this->tablename = mysqli_real_escape_string($tablename);
    $this->params = $params;
    $this->debug = $debug;
    $this->processGetParams();
  }

  public function processGetParams()
  {
    // Available column from model defined in configuration
    $column_names_available = $this->getColumnNames();
    $this->columns = [];
    $this->values = [];
    $this->update = [];

    foreach( $this->params as $key => $value ) {
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
    $this->sql = "select * from $this->tablename";
    $result = $this->connection->query($this->sql);
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
    $this->sql = "INSERT INTO `$this->tablename` ($this->columns) VALUES ($this->values)";
    $result = $this->connection->query($this->sql);
    if ($result) {
      $this->display_result(array(
        'id' => $this->connection->insert_id,
      ));
    } else {
      $this->display_result(array(
        'message' => 'There was a problem inserting into the database.',
        'error' => 1,
      ));
    }
  }

  // GET
  public function read()
  {
    $id = $this->params['id'];
    settype($id, 'integer');

    $response = [];
    $this->sql = "select * from $this->tablename where id = " . $id;
    $result = $this->connection->query($this->sql);
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $response[] = $row;
      }
      $this->display_result($response);
    } else {
      $this->display_result(array(
        'message' => 'No results found.',
        'row_count' => 0,
      ));
    }

  }

  // POST
  public function update()
  {
    $id = $this->params['id'];
    settype($id, 'integer');
    $this->sql = "UPDATE $this->tablename SET $this->update WHERE id=$id";
    $result = $this->connection->query($this->sql);
    if ($result) {
      $this->display_result(array(
        'updated' => 1,
        'error' => 0,
        'id' => $id,
      ));
    } else {
      $this->display_result(array(
        'message' => 'Something went wrong with the update.',
        'updated' => 0,
        'error' => 1,
      ));
    }
  }

  // DELETE
  public function delete()
  {
    $id = mysqli_real_escape_string($this->connection,$this->params['id']);
    if (isset($this->params['id']) && isset($this->params['delete'])) {

      // Validate input as integer.
      // Very important as it removes strings contained other SQL statements
      settype($id, 'integer');

      $this->sql = "DELETE FROM $this->tablename WHERE id = $id";
      $result = $this->connection->query($this->sql);
      if ($result) {
        $this->display_result(array(
          'deleted' => 1,
          'error' => 0,
          'id' => $id,
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
    $debug = [];
    if ($this->debug) {
      $debug = array(
        'sql' => $this->sql,
        'params' => $this->params,
      );
    }
    if (is_array($arg)) {
      $return_array = array_merge($arg, $debug);
    } else {
      $return_array = array_merge(array('result' => $arg), $debug);
    }
    echo json_encode($return_array);


  }

}


 ?>
