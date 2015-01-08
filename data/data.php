<?php

include_once './DataSource.php';

$dataSource = new DataSource();

switch ($_SERVER['REQUEST_METHOD']) {

    case "GET":
        $id = explode("locations/", $_SERVER['REQUEST_URI']);

        if (isset($id[1]) && $id[1] !== '') {
            $res = $dataSource->get($id[1]);
        } else {
            if (isset($_GET['type'])) {
                if ($_GET['type'] === 'odporucane') {
                    $res = $dataSource->getSuggested();
                } else if ($_GET['type'] === 'county') {
                    if(isset($_GET['name'])) {
                        $res = $dataSource->getByCounty($_GET['name']);
                    }
                }
                
            } else {
                $res = $dataSource->getAll();
            }
        }
        echo json_encode($res);
        break;
    /*
      case "POST":

      // Save a new record in the database

      $result = $obj->register_new_book($_POST);

      break;

      case "PUT":

      // Retrieve additional data
      $d = json_decode(file_get_contents("php://input"), false);

      $result = $obj->loan_book($d);

      break;

      case "DELETE":

      $id = explode("book/", $_SERVER['REQUEST_URI']);

      if (isset($id[1])){
      $result = $obj->delete_book($id[1]);
      }

      break;

     */
}
?>