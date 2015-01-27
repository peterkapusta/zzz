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
                    if (isset($_GET['name'])) {
                        $res = $dataSource->getByCounty($_GET['name']);
                    }
                }
            } else {
                $res = $dataSource->getAll();
            }
        }
        echo json_encode($res);
        break;

    case "POST":
        echo "OK";
        $test = 0;
        $request_body = file_get_contents('php://input');
        $location = json_decode($request_body);
        
        $dataSource->updateLocation("seen", $location->seen, $location);
        break;

}
?>