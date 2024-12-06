<?php
require_once('../config/config.php');
require_once('../models/database.php');
include('../models/m_kasir.php');
include('../models/m_btb.php');
include('../models/m_hubnet.php');
if (@$_GET['action']) {
    if ($_GET['action']  == 'store') {
        $connection = new Database($host, $user, $pass, $database);
        $class = new Kasir($connection);
        $btb = new Btb($connection);
        $hubnet = new Hubnet($connection);
        $api = $hubnet->getHubnetAPI();
        $url = $api->api_address;
        $user = $api->api_user;
        $password = $api->api_pass;
        $smuId = $_POST['id'];
        $cargo = $btb->getCargoById($smuId);
        http_response_code(404);
        if (!$cargo->agent_address) {
            http_response_code(404);
            $respond = [
                'status' => 404,
                'message' => 'Agent address not found',
            ];
            header('Content-Type: application/json');
            echo json_encode($respond);
            die();
        }

        if (!$cargo->shipper_address) {
            http_response_code(404);
            $respond = [
                'status' => 404,
                'message' => 'Shipper address not found',
            ];
            header('Content-Type: application/json');
            echo json_encode($respond);
            die();
        }

        if (!$cargo->schedule_time) {
            http_response_code(404);
            $respond = [
                'status' => 404,
                'message' => 'Departure time not found '  . $cargo->flight_no . ', date: ' . date('Y-m-d', strtotime($cargo->tanggal)),
            ];
            header('Content-Type: application/json');
            echo json_encode($respond);
            die();
        }

        // $schedule = $btb->getSmuSchedule($cargo->flight_no, date('Y-m-d', strtotime($cargo->tanggal)));
        // if ($schedule->num_rows == 0) {
        //     http_response_code(404);
        //     $respond = [
        //         'status' => 404,
        //         'message' => 'Schedule not found ' . $cargo->flight_no . ', date: ' . date('Y-m-d', strtotime($cargo->tanggal)),
        //     ];
        //     header('Content-Type: application/json');
        //     echo json_encode($respond);
        //     die();
        // }

        $curl = curl_init();

        if ($cargo->weight > $cargo->volume) {
            if ($cargo->weight < 10) {
                $cw = 10;
            } else {
                $cw = $cargo->weight;
            }
        } else {
            if ($cargo->volume < 10) {
                $cw = 10;
            } else {
                $cw = $cargo->volume;
            }
        }

        $dataSend = [
            [
                "AWB_NO" => $cargo->smu,
                "FLT_NUMBER" => $cargo->flight_no,
                "FLT_DATE" => date('Y-m-d H:i', strtotime($cargo->schedule_time)),
                "ORI" => "CGK",
                "DEST" => $cargo->tlc,
                "T" => $cargo->quantity,
                "K" => $cargo->weight,
                "CH_WEIGHT" => $cw,
                "MC" => $cargo->volume,
                "AGT_NAME" => $cargo->agent_name,
                "AGT_ADD" => $cargo->agent_address,
                "SHP_NAME" => $cargo->shipper_name,
                "SHP_ADD" => $cargo->shipper_address,
                "KATEGORI_CARGO" => $cargo->type,
                "COMMODITY" => $cargo->general_name,
                "REMARKS" => $cargo->comodity ?? 'PKT',
            ]
        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . 'nle-udara/receive-data-logistik',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($dataSend),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: ' . base64_encode($user . ':' . $password)
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo json_encode($response);
        $result = json_decode($response);
        // echo $result->message;
        if ($result->status == 1) {
            http_response_code(200);
            $respond = [
                'status' => 200,
                'message' => $result->message,
                'reff_number' => $result->data->ref_id,
                'dataSend' => $dataSend
            ];
        } else {
            http_response_code(404);
            $respond = [
                'status' => 500,
                'message' => $result->message
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($respond);
    }
}
