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
      $this->connection = $this->db->getConnection();
    }

    // Require all api routes
    // Instanstiate classes
    // check requests
    public function init() {
      foreach($GLOBALS['routes'] as $route) {
        require($this->base_dir . '/api/' . $route . '/index.php');
        $classname = ucfirst($route);
        $myclass = new $classname($this->connection, $this->base_dir);
        // print_r($myclass);
        // print_r(get_class_methods($myclass));
        if (isset($_GET[$route]) && isset($_GET['all'])) {
          $myclass->index();
        }
        if (isset($_GET[$route]) && isset($_GET['id']) ) {
          $myclass->read($_GET['id']);
        }
        if (isset($_GET[$route]) && isset($_GET['create']) ) {
          $myclass->create($_GET);
        }
        if (isset($_GET[$route]) && isset($_GET['update']) ) {
          $myclass->update($_GET);
        }
        if (isset($_GET[$route]) && isset($_GET['delete']) ) {
          $myclass->delete($_GET);
        }

      }

    }
  }


 ?>
