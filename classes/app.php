<?php

  /**
   * Main class for PHP Prototyping
   * @description: Handles all requests base on parameters,
   * sets up crud for models
   * @todo Log actions and requests
   * @todo Implement authentication for requests
   * @todo ...
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

    /**
     * Handled all requests
     * All requests are handled based on the action that is passed with them (ie create, read, update, delete)
     *
     * Require Crud
     * Instantiate Crud Class
     * Check request params
     */
    public function init() {
      foreach($GLOBALS['routes'] as $route) {
        $columns = $route['columns'];
        $tablename = $route['name'];

        if (isset($_GET[$tablename])) {

          require($this->base_dir . '/classes/crud.php');

          $myclass = new Crud($this->connection, $this->base_dir, $columns, $tablename);

          // ALL - LIMIT
          if (isset($_GET['all'])) {
            $myclass->index();
          }
          // Find one - WHERE CLAUSES
          if (isset($_GET['id']) && !isset($_GET['delete'])) {
            $myclass->read($_GET['id']);
          }
          // Create one
          if (isset($_GET['create']) ) {
            $myclass->create($_GET);
          }
          // Update one - WHERE CLAUSES
          if (isset($_GET['update']) ) {
            $myclass->update($_GET);
          }
          // Delete one - WHERE CLAUSES
          if (isset($_GET['delete']) ) {
            $myclass->delete($_GET);
          }
          //  SEARCH
          if (isset($_GET['search']) ) {
            $myclass->search($_GET);
          }
        }

      }

    }
  }


 ?>
