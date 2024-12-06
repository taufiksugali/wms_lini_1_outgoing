<?php
require_once('../config/config.php');
require_once('../models/database.php');
include('../models/m_kasir.php');
include('../models/m_btb.php');
include('../models/m_sigo.php');
session_start();
if (@$_GET['action']) {
    if ($_GET['action']  == 'store') {
        $connection = new Database($host, $user, $pass, $database);
        $kasir = new Kasir($connection);
        $btb = new Btb($connection);
        $mSigo = new Sigo($connection);
        $invoiceId = $_POST['invoiceId'];

        $api = $mSigo->getSigoAPI();
        $invoice = $kasir->getInvoiceById($invoiceId);

        $url = $api->api_address . "invo_dtl_v2";

        $fields = array(
            'USR' => $api->api_user,
            'PSW' => $api->api_pass,
            'NO_INVOICE' => $invoice->njg,
            'TANGGAL' => date('Y-m-d H:i:s'),
            'SMU' => $invoice->smu,
            'KDAIRLINE' => $invoice->airline_id,
            'FLIGHT_NUMBER' =>  $invoice->flight_no,
            'DOM_INT' => 'D',
            'INC_OUT' => 'O',
            'ASAL' => 'CGK',
            'TUJUAN' => $invoice->tlc,
            'JENIS_KARGO' => $invoice->comodity,
            'TARIF_KARGO' => '1',
            'KOLI' => $invoice->quantity,
            'BERAT' => $invoice->weight,
            'VOLUME' => $invoice->volume,
            'JML_HARI' => 1,
            'CARGO_CHG' => $invoice->sewa_gudang,
            'KADE' => $invoice->kade,
            'TOTAL_PENDAPATAN_TANPA_PPN' => $invoice->total - $invoice->ppn,
            'TOTAL_PENDAPATAN_DENGAN_PPN' => $invoice->total,
            'PJT_HANDLING_FEE' => '0',
            'RUSH_HANDLING_FEE' => '0',
            'RUSH_SERVICE_FEE' => '0',
            'TRANSHIPMENT_FEE' => '0',
            'ADMINISTRATION_FEE' => $invoice->admin,
            'DOCUMENTS_FEE' => '0',
            'PECAH_PU_FEE' => '0',
            'COOL_COLD_STORAGE_FEE' => '0',
            'STRONG_ROOM_FEE' => '0',
            'AC_ROOM_FEE' => '0',
            'DG_ROOM_FEE' => '0',
            'AVI_ROOM_FEE' => '0',
            'DANGEROUS_GOOD_CHECK_FEE' => '0',
            'DISCOUNT_FEE' => '0',
            'RKSP_FEE' => '0',
            'HAWB' => '0',
            'HAWB_FEE' => '0',
            'HAWB_MAWB_FEE' => '0',
            'CSC_FEE' => $invoice->pjkp2u,
            'ENVIROTAINER_ELEC_FEE' => '0',
            'ADDITIONAL_COSTS' => $invoice->airport_tax,
            'NAWB_FEE' => '0',
            'BARCODE_FEE' => '0',
            'CARGO_DEVELOPMENT_FEE' => '0',
            'DUTIABLE_SHIPMENT_FEE' => '0',
            'FHL_FEE' => '0',
            'FWB_FEE' => '0',
            'CARGO_INSPECTION_REPORT_FEE' => '0',
            'MATERAI_FEE' => $invoice->materai,
            'PPN_FEE' => $invoice->ppn
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 3,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => array(
                'Cookie: dtCookie=CD78B9A24184B932B72CB79ED316B71D|X2RlZmF1bHR8MQ'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);

        $sigoData = [
            'invoice_id' => $invoice->payment_id,
            'sigo_address' => $url,
            'sigo_action' => 'insert',
            'sigo_data' => json_encode($fields),
            'sigo_datetime' => date('Y-m-d H:i:s'),
        ];

        if ($response['status'] == 200) {
            $sigoData['sigo_status'] = 1;
            $insertSigo =  $mSigo->insertData($sigoData);
            $respond = [
                'status' => 200,
                'data' => $response
            ];
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($respond);
        } else {
            $sigoData['sigo_status'] = 0;
            $insertSigo =  $mSigo->insertData($sigoData);
            $respond = [
                'status' => 500,
                'data' => $response,
                'message' => 'Failed to push to Sigo'
            ];
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode($respond);
        }
    }
}
