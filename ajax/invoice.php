<?php
require_once('../config/config.php');
require_once('../models/database.php');
include('../models/m_kasir.php');
include('../models/m_btb.php');
include('../models/m_hubnet.php');
include('../models/m_sigo.php');
date_default_timezone_set("Asia/Jakarta");

if (@$_GET['action']) {
    if ($_GET['action']  == 'create_invoice') {
        try {

            session_start();
            $connection = new Database($host, $user, $pass, $database);
            $kasir = new Kasir($connection);
            $btb = new Btb($connection);

            $s_session = $kasir->session()->fetch_object();
            $s_kasir = $s_session->pharsing;

            $smuId = $_POST['id'];
            $npwp = $_POST['npwp'];
            $hubnetStatus = $_POST['hubnet_status'];
            $cargo = $btb->getCargoById($smuId);

            $d_njg = $kasir->calnjg();
            $njg1 = $d_njg->fetch_object();
            $njg = $njg1->njg;
            $new_njg = $njg + 1;

            $pricelist = $kasir->calprice()->fetch_object();
            $admin = $pricelist->admin;
            $sg = $pricelist->sg;
            $kade = $pricelist->kade;
            $pjkp2u = $pricelist->pjkp2u;
            $materai = $pricelist->materai;
            $airport_surcharge = $pricelist->airport_surcharge;
            $pricelist_id = $pricelist->pricelist_id;

            if ($cargo->weight >= $cargo->volume && $cargo->weight > 10) {
                $net = $cargo->weight;
            } elseif ($cargo->volume > $cargo->weight && $cargo->volume > 10) {
                $net = $cargo->volume;
            } else {
                $net = 10;
            }

            $tsg = $net * $sg;
            $tpjkp2u =  $cargo->weight <= 10 ? 10 * $pjkp2u : $cargo->weight * $pjkp2u;
            $tkade = $cargo->weight <= 10 ? 10 * $kade : $cargo->weight * $kade;
            $tairport_surcharge = $net * $airport_surcharge;
            $tppn = round((($tsg + $tpjkp2u + $tkade + $admin + $tairport_surcharge) * 11) / 100);
            if (($tsg + $tpjkp2u + $tkade + $admin + $tairport_surcharge + $tppn) < 10000000) {
                $tmaterai = 0;
            } else {
                $tmaterai = $materai;
            }
            $total = round($tsg + $tpjkp2u + $tkade + $admin + $tairport_surcharge + $tppn + $tmaterai);
            $date = date("Y-m-d");
            $name = $_SESSION['name'];
            $stimestamp = date('Y-m-d H:i:s');

            $values = "('$new_njg', '$cargo->no_do', '$cargo->smu', '$date', '$admin', '$tsg','$tkade','$tpjkp2u','$tairport_surcharge','$tppn','$tmaterai','$total', '$name', '$s_kasir', '$pricelist_id', '$npwp', '$stimestamp')";
            $insert = $kasir->insert2($values);
            if (@$insert->id) {
                $kasir->updatestat($cargo->smu);

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

                $hubnet = new Hubnet($connection);
                $api = $hubnet->getHubnetAPI();
                $url = $api->api_address;
                $data = [
                    'invoice_id' => $insert->id,
                    'hub_address' => $api->api_address,
                    'hub_data' => json_encode($dataSend),
                    'hub_datetime' => date('Y-m-d H:i:s'),
                    'hub_status' => $hubnetStatus == 'success' ? 1 : 0,
                ];
                $insertHubnet =  $hubnet->insertData($data);
                // var_dump($insertHubnet);
                // die();

                http_response_code(200);
                $respond = [
                    'status' => 200,
                    'invoice_id' => $insert->id
                ];
                header('Content-Type: application/json');
                echo json_encode($respond);
            } else {
                http_response_code(500);
                $respond = [
                    'status' => 500,
                    'message' => $insert,
                ];
                header('Content-Type: application/json');
                echo json_encode($respond);
            }
        } catch (Exception $e) {
            http_response_code(500);
            $respond = [
                'status' => 500,
                'message' => $e->getMessage(),
            ];
            header('Content-Type: application/json');
            echo json_encode($respond);
        }
    }

    if ($_GET['action']  == 'void_payment') {
        session_start();
        $connection = new Database($host, $user, $pass, $database);
        $kasir = new Kasir($connection);
        $sigo = new Sigo($connection);
        $btb = new Btb($connection);

        $paymentId = $_POST['payment_id'];
        $remark = $_POST['keterangan'];

        $dataSigo = $sigo->getStoreData($paymentId);
        $paymentData = $kasir->getInvoiceById($paymentId);

        if ($dataSigo != null) {
            if ($dataSigo->sigo_status == 1) {
                $api = $sigo->getSigoAPI();
                $url = $api->api_address . "void_invo_dtl";
                $dataVoid = array(
                    'USR' => $api->api_user,
                    'PSW' => $api->api_pass,
                    'TANGGAL' => date('Y-m-d H:i', strtotime($dataSigo->sigo_datetime)),
                    'NO_INVOICE' => $paymentData->njg,
                    'HAWB' => '0',
                    'SMU' => $paymentData->smu,
                );

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $dataVoid,
                    CURLOPT_HTTPHEADER => array(
                        'Cookie: dtCookie=CD78B9A24184B932B72CB79ED316B71D|X2RlZmF1bHR8MQ'
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                // var_dump($response);
                // die();
                $response = json_decode($response, true);

                if ($response['status'] == '200') {
                    $sigoData = [
                        'invoice_id' => $paymentData->payment_id,
                        'sigo_address' => $url,
                        'sigo_action' => 'void',
                        'sigo_data' => json_encode($dataVoid),
                        'sigo_datetime' => date('Y-m-d H:i:s'),
                        'sigo_status' => 1,
                    ];
                    $sigoMessage = 'success';
                } else {
                    $sigoData = [
                        'invoice_id' => $paymentData->payment_id,
                        'sigo_address' => $url,
                        'sigo_action' => 'void',
                        'sigo_data' => json_encode($dataVoid),
                        'sigo_datetime' => date('Y-m-d H:i:s'),
                        'sigo_status' => 0,
                    ];
                    $sigoMessage = 'failed';
                }
                $sigo->insertData($sigoData);
                $sigo->updateData($dataSigo->sigo_id, ['sigo_status' => 2]);
            } else {
                $sigo->updateData($dataSigo->sigo_id, ['sigo_status' => 3]);
                $sigoMessage = 'success';
            }
        } else {
            $sigoMessage = 'No data sigo recorded.';
        }
        $kasir->updateData($paymentData->payment_id, ['payment_status' => '0', 'keterangan' => $remark, 'void_by' => $_SESSION['name']]);
        $btb->updateData($paymentData->cargo_id, ['status' => 'proced']);

        http_response_code(200);
        $respond = [
            'status' => 200,
            'message' => 'success',
            'sigo' => $sigoMessage,
            'user' => $_SESSION['name'],
        ];
        header('Content-Type: application/json');
        echo json_encode($respond);
    }
}
