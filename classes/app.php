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

    $method = $_SERVER['REQUEST_METHOD'];
    $posted_json = json_decode(file_get_contents('php://input'));
    // echo $method;
    // $posted_json->method = $method;
    echo json_encode(getallheaders());
    // echo json_encode($_POST);
    return;

    if (count($GLOBALS['routes']) < 1){
      echo json_encode(array(
        'message' => 'No models available. See README file for more information',
        'error' => 1
      ));
    }
    foreach($GLOBALS['routes'] as $route) {
      $columns = $route['columns'];
      $tablename = $route['name'];

      if (isset($_GET[$tablename])) {

        require($this->base_dir . '/classes/crud.php');

        $myclass = new Crud($this->connection, $this->base_dir, $columns, $tablename);

        // ALL - LIMIT
        if (isset($_GET['all'])) {
          $myclass->index();
          return;
        }
        // Find one - WHERE CLAUSES
        if (isset($_GET['id']) && !isset($_GET['delete']) && !isset($_GET['update']) && !isset($_GET['create'])) {
          $myclass->read($_GET['id']);
          return;
        }
        // Create one
        if (isset($_GET['create'])) {
          $myclass->create();
          return;
        }
        // Update one - WHERE CLAUSES
        if (isset($_GET['update']) && isset($_GET['id'])) {
          $myclass->update();
          return;
        }
        // Delete one - WHERE CLAUSES
        if (isset($_GET['delete']) ) {
          $myclass->delete($_GET);
          return;
        }
        //  SEARCH
        if (isset($_GET['search']) ) {
          $myclass->search($_GET);
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
