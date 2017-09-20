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
  public $id;

  function __construct($connection, $base_dir, $columns, $tablename, $params, $debug)
  {
    $this->base_dir = $base_dir;
    $this->connection = $connection;
    $this->columns = $columns;
    $this->tablename = $connection->real_escape_string($tablename);
    $this->params = $params;
    $this->debug = $debug;
    $this->processGetParams();

    // Validate input as integer.
    // Very important as it removes strings contained other SQL statements
    if(isset($this->params['id'])) {
      settype($this->params['id'], 'integer');
      $this->id = $this->params['id'];
    }
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
    $response = [];
    $this->sql = "select * from $this->tablename where id = " . $this->id;
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
    $this->sql = "UPDATE $this->tablename SET $this->update WHERE id=$this->id";
    $result = $this->connection->query($this->sql);
    if ($result) {
      $this->display_result(array(
        'updated' => 1,
        'error' => 0,
        'id' => $this->id,
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
    $this->sql = "DELETE FROM $this->tablename WHERE id = $this->id";
    $result = $this->connection->query($this->sql);
    if ($result) {
      $this->display_result(array(
        'deleted' => 1,
        'error' => 0,
        'id' => $this->id,
      ));
    } else {
      $this->display_result(array(
        'deleted' => 0,
        'error' => 1,
        'id' => $this->id,
        'message' => 'Does not exist.'
      ));
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
    if (!is_array($arg)) {
      $arg = array('result' => $arg);
      error_log('not an array');
    }

    $return_array = $arg;

    // array of arrays
    if (isset($return_array[0]) && gettype($return_array[0]) == 'array') {
      foreach($return_array as $array) {
        if ($this->debug) {
          $array['sql'] = $this->sql;
          $array['params'] = $this->params;
        }
        $return_array[] = $array;
      }

    } else {
      // single associative arrays
      if ($this->debug) {
        $return_array['sql'] = $this->sql;
        $return_array['params'] = $this->params;
      }
    }
    echo json_encode($return_array);
  }

}


 ?>
