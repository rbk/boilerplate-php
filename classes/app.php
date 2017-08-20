<?php

  /**
   * App - Do some helpful stuff
   */
  class App
  {

    protected $base_dir;
    protected $connection;
    protected $db;

    function __construct($config)
    {
      $this->base_dir = getcwd();
      $this->db = new Database($config['database']);
      $this->db->model($config['models']);
    }

    public function init() {
      $this->connection = $db;

      // print_r($GLOBALS['routes']);

      foreach($GLOBALS['routes'] as $route) {
        // Require all api routes
        require($this->base_dir . '/api/' . $route . '/index.php');
        // Instanstiate
        // $classname = ucfirst($route) . 'Model';
        // $myvar = new $classname($this->connection);
      }

    }


  }


 ?>
