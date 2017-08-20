<?php
/**
 *
 */
class Database extends App
{
  protected $connection = null;
  protected $user = null;
  protected $password = null;
  private $host = null;
  private $database = null;
  private $messages = [];
  private $crud_operations = array(
    'create',
    'read',
    'update',
    'delete'
  );


  function __construct($config)
  {
    $this->user = $config['user'];
    $this->password = $config['password'];
    $this->host = $config['host'];
    $this->database = $config['database'];

    /**
     * Check db connection
     */
     $this->connectTest();

     /**
      * Create the database if it doesn't exist
      */
     $this->doesDatabaseExist();
     $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database);
     if (!$this->connection) {
        die('Could not connect.');
     }
  }

  public function getConnection()
  {
    return $this->connection;
  }

  private function connectTest()
  {
    $connection = new mysqli($this->host, $this->user, $this->password);
    if (!empty($connection->connect_error)) {
      $this->messages[] = 'No database connection. Check Credentials.';
      die();
    }
  }

  private function doesDatabaseExist() {
    $connection = new mysqli($this->host, $this->user, $this->password);
    $sql = "SHOW DATABASES LIKE '$this->database'";
    $result = $connection->query($sql);
    if (!$result->num_rows && $this->database) {
      $this->createDatabase();
    }
  }

  private function createDatabase()
  {
    $connection = new mysqli($this->host, $this->user, $this->password);
    $result = $connection->query("CREATE DATABASE IF NOT EXISTS $this->database;");
  }

  public function model($schema)
  {
    $this->createTable($schema);
  }

  public function createTable($schema)
  {
    foreach( $schema as $model) {
      /** Define default columns */
      $sql_columns = '
      `id` INT NOT NULL AUTO_INCREMENT ,
      `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
      PRIMARY KEY (`id`),
      ';
      $seperator = ',';

      $model = (object)$model;
      $column_max_index = count($model->columns)-1;
      // build sql string
      if ($model->columns) {
        $index = 0;
        foreach( $model->columns as $name => $type ) {
          if ( $index == $column_max_index ) {
            $seperator = '';
          }
          $sql_columns .= $name . ' ' . $type . $seperator;
          $index = $index + 1;
        }
      }
      // create table
      $table = $this->connection->query("SHOW TABLES LIKE '$model->name';");
      if ($table->num_rows == 0) {
        $sql = "
        CREATE TABLE
        $model->name (
          $sql_columns
        );
        ";
        $this->connection->query($sql);
        $this->createApi($model->name);
      }
      // Add route by name
      $GLOBALS['routes'][] = $model->name;
    }

  }

  public function createApi($name)
  {
    if (!is_dir('api')){
      mkdir('api');
    }

    if (!is_dir('./api/' . $name)) {
      mkdir('./api/' . $name);
    }

    $file = './api/' . $name . '/index.php';
    if (!is_file($file)) {
      $fh = fopen($file, 'w+');
      fclose($fh);
    }

  }


  public function updateSchema() {
    /**
     * examples

     //
     ALTER table 'tablename' DROP 'columnname'
     ALTER TABLE `quotes` CHANGE `name` `name` VARCHAR(200);

     */

  }

  /**
   * Return Resulting array or true/false for update and insert
   */
  public function query($string, $array = array())
  {

  }

  private function update() {}
  private function insert() {}
  private function select() {}

}

 ?>
