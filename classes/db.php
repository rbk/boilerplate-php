<?php
/**
 * Database helper classes
 * @description:
 * Connects to database, builds queries for create tables
 * based on config models.
 * @todo Abstract query builder
 */
class Database extends App
{
  protected $connection = null;
  protected $user = null;
  protected $password = null;
  private $host = null;
  private $database = null;
  private $messages = [];

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

     /**
      * Store the connection to mysql
      */
     $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database);
     if (!$this->connection) {
        die('Could not connect.');
     }
  }

  /**
   * Get connection to database
   * @return Mysql connection string
   */
  public function getConnection()
  {
    return $this->connection;
  }

  /**
   * Find out if we can connect to the database
   */
  private function connectTest()
  {
    $connection = new mysqli($this->host, $this->user, $this->password);
    if (!empty($connection->connect_error)) {
      $this->messages[] = 'No database connection. Check Credentials.';
      die();
    }
  }

  /**
   * Check to see if the database exists
   */
  private function doesDatabaseExist() {
    $connection = new mysqli($this->host, $this->user, $this->password);
    $sql = "SHOW DATABASES LIKE '$this->database'";
    $result = $connection->query($sql);
    if (!$result->num_rows && $this->database) {
      $this->createDatabase();
    }
  }

  /**
   * Create database if it doesn't exist
   */
  private function createDatabase()
  {
    $connection = new mysqli($this->host, $this->user, $this->password);
    $result = $connection->query("CREATE DATABASE IF NOT EXISTS $this->database;");
  }

  /**
   * Alias for createTable
   */
  public function model($schema)
  {
    $this->createTable($schema);
  }

  /**
   * Build queries to create tables and load models
   * @todo Save schema to database
   * @todo Add column options for tables
   */
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
      }
      // Add route by name
      $GLOBALS['routes'][] = array(
        'name' => $model->name,
        'columns' => $model->columns,
      );
    }

  }

  /**
   * Update database based on model changes
   * @todo Migrate tables with alters
   * @todo Save model history
   */
  public function updateSchema() {
    /**
     * Examples:
     * ALTER table 'tablename' DROP 'columnname'
     * ALTER TABLE `quotes` CHANGE `name` `name` VARCHAR(200);
     */
  }

  /**
   * Advanced query building functions I may need
   */
  public function query($string, $array = array()) {}
  private function update() {}
  private function insert() {}
  private function select() {}

}

 ?>
