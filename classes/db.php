<?php

/**
 * Database helper class
 * @description - Builds queries to create database and tables based on config, connects to database
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
  private $debug = false;

  function __construct($config, $debug)
  {
    $this->user = $config['user'];
    $this->password = $config['password'];
    $this->host = $config['host'];
    $this->database = $config['database'];
    $this->debug = $debug;

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
    @$connection = new mysqli($this->host, $this->user, $this->password);
    if (!empty($connection->connect_error)) {
      die($connection->connect_error);
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
          $sql_columns .= $name . ' ' . $type . $seperator . "\n";
          $index = $index + 1;
        }
      }

      if (isset($model->foreign_key) && isset($model->references)) {
        $sql_columns = $sql_columns . ', FOREIGN KEY' . '(' . $model->foreign_key . ') ' . 'REFERENCES ' . $model->references . '(id)';
      }

      $sql = "
        CREATE TABLE
        $model->name (
          $sql_columns
        );
      ";

      if ($this->debug) {
        echo "--\n";
        echo "-- Table: $model->name\n";
        echo "-- \n\n";
        echo SqlFormatter::format($sql, false);
        echo "\n\n";
      } else {
        /*
        * Attempt to Create Table
        * @todo Need to define where alters will will happend and when
        */
        $table = $this->connection->query("SHOW TABLES LIKE '$model->name';");
        if ($table->num_rows == 0) {
          $this->connection->query($sql);
        }
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
   * http://www.dofactory.com/sql/update
   */
  public function updateSchema() {
    /**
     * Examples:
     * ALTER table 'tablename' DROP 'columnname'
     * ALTER TABLE `quotes` CHANGE `name` `name` VARCHAR(200);
     */
  }

}

 ?>
