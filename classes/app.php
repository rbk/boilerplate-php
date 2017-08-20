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
        $columns = $route['columns'];
        $tablename = $route['name'];
        if (isset($_GET[$tablename])) {
          echo $tablename . '<br><pre>';
          require($this->base_dir . '/classes/crud.php');
          $myclass = new Crud($this->connection, $this->base_dir, $columns, $tablename);
          // print_r($myclass);
          // print_r(get_class_methods($myclass));

          // ALL - LIMIT
          if (isset($_GET['all'])) {
            $myclass->index();
          }
          // Find one - WHERE
          if (isset($_GET['id']) ) {
            $myclass->read($_GET['id']);
          }
          // Create one
          if (isset($_GET['create']) ) {
            $myclass->create($_GET);
          }
          // Update one - WHERE
          if (isset($_GET['update']) ) {
            $myclass->update($_GET);
          }
          // Delete one
          if (isset($_GET['delete']) ) {
            $myclass->delete($_GET);
          }
          //  SEARCH
          if (isset($_GET['delete']) ) {
            $myclass->delete($_GET);
          }
        }

      }

    }
  }


 ?>
