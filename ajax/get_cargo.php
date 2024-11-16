<?php
require_once('../config/config.php');
require_once('../models/database.php');
include('../models/m_btb.php');
include('../models/m_flight.php');

if (@$_GET['id']) {
    $id = $_GET['id'];
    $connection = new Database($host, $user, $pass, $database);
    $class = new Btb($connection);
    $flight = new Flight($connection);
    $cargo = $class->getCargoById($id);

    if (@$cargo) {
        $flightNo = $flight->get_flight_by_smu_code($cargo->smu_code);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['status' => 200, 'data' => $cargo, 'flights' => $flightNo->fetch_all(MYSQLI_ASSOC)]);
    } else {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['status' => 404, 'message' => 'Data not found']);
    }
}
