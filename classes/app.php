<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

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
  protected $method;
  protected $headers;

  function __construct($config)
  {
    $this->base_dir = getcwd();
    $this->db = new Database($config['database']);
    $this->db->model($config['models']);
    $this->connection = $this->db->getConnection();
    $this->method = (isset($_SERVER['REQUEST_METHOD'])) ? $_SERVER['REQUEST_METHOD'] : 'GET';
    $this->headers = getallheaders();
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

    if ($this->method == 'POST') {
      // Posting from HTML form
      if (strpos($this->headers['Content-Type'], 'x-www-form-urlencoded') !== false) {
        $this->params = $_POST;
      }
      // Ajax POST
      if (strpos($this->headers['Content-Type'], 'plain') !== false) {
        $this->params = (array)json_decode(file_get_contents('php://input'));
      }
    }
    if ($this->method == 'GET') {
      $this->params = $_GET;
    }

    if (count($GLOBALS['routes']) < 1){
      echo json_encode(array(
        'message' => 'No models available. See README file for more information',
        'error' => 1
      ));
    }

    foreach($GLOBALS['routes'] as $route) {
      $columns = $route['columns'];
      $tablename = $route['name'];

      // echo json_encode($this->params);
      // return;

      if (isset($this->params['model']) && $this->params['model'] == $tablename || isset($this->params[$tablename]) ) {

        require($this->base_dir . '/classes/crud.php');

        $myclass = new Crud($this->connection, $this->base_dir, $columns, $tablename, $this->params);

        // ALL - LIMIT
        if (isset($this->params['all'])) {
          $myclass->index();
          return;
        }
        // Find one - WHERE CLAUSES
        if (isset($this->params['id']) && !isset($this->params['delete']) && !isset($this->params['update']) && !isset($this->params['create'])) {
          echo json_encode($this->params);
          return;
          $myclass->read($this->params['id']);
          return;
        }
        // Create one
        if (isset($this->params['create'])) {
          $myclass->create();
          return;
        }
        // Update one - WHERE CLAUSES
        if (isset($this->params['update']) && isset($this->params['id'])) {
          $myclass->update();
          return;
        }
        // Delete one - WHERE CLAUSES
        if (isset($this->params['delete']) ) {
          $myclass->delete($this->params);
          return;
        }
        //  SEARCH
        if (isset($this->params['search']) ) {
          $myclass->search($this->params);
          return;
        }
      }
    }
    echo json_encode(array(
      'message' => 'No proper request made.',
      'error' => 1
    ));

  }
}


?>
