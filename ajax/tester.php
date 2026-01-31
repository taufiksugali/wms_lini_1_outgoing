<?php
$url = "https://apisigo.angkasapura2.co.id/api/invo_dtl_v2";
$devUrl = "https://lane.angkasapura2.co.id/dev/api/invo_dtl_v2";

$fields = [
    "USR" => "user.api.poslog",
    "PSW" => "user.api.poslog",
    "NO_INVOICE" => "291025",
    "TANGGAL" => "2025-06-16 14:58:05",
    "SMU" => "778-01991964",
    "KDAIRLINE" => "IP",
    "FLIGHT_NUMBER" => "IP-342",
    "DOM_INT" => "D",
    "INC_OUT" => "O",
    "ASAL" => "CGK",
    "TUJUAN" => "BTJ",
    "JENIS_KARGO" => "GAB PAKET",
    "TARIF_KARGO" => "1",
    "KOLI" => "8",
    "BERAT" => "110",
    "VOLUME" => "0",
    "JML_HARI" => 1,
    "CARGO_CHG" => "178200",
    "KADE" => "41250",
    "TOTAL_PENDAPATAN_TANPA_PPN" => 277470,
    "TOTAL_PENDAPATAN_DENGAN_PPN" => "307992",
    "PJT_HANDLING_FEE" => "0",
    "RUSH_HANDLING_FEE" => "0",
    "RUSH_SERVICE_FEE" => "0",
    "TRANSHIPMENT_FEE" => "0",
    "ADMINISTRATION_FEE" => "5000",
    "DOCUMENTS_FEE" => "0",
    "PECAH_PU_FEE" => "0",
    "COOL_COLD_STORAGE_FEE" => "0",
    "STRONG_ROOM_FEE" => "0",
    "AC_ROOM_FEE" => "0",
    "DG_ROOM_FEE" => "0",
    "AVI_ROOM_FEE" => "0",
    "DANGEROUS_GOOD_CHECK_FEE" => "0",
    "DISCOUNT_FEE" => "0",
    "RKSP_FEE" => "0",
    "HAWB" => "0",
    "HAWB_FEE" => "0",
    "HAWB_MAWB_FEE" => "0",
    "CSC_FEE" => "20020",
    "ENVIROTAINER_ELEC_FEE" => "0",
    "ADDITIONAL_COSTS" => "33000",
    "NAWB_FEE" => "0",
    "BARCODE_FEE" => "0",
    "CARGO_DEVELOPMENT_FEE" => "0",
    "DUTIABLE_SHIPMENT_FEE" => "0",
    "FHL_FEE" => "0",
    "FWB_FEE" => "0",
    "CARGO_INSPECTION_REPORT_FEE" => "0",
    "MATERAI_FEE" => "0",
    "PPN_FEE" => "30522"
];

// echo "<pre>";
// print_r($fields);
// echo "</pre>";

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

// echo "<pre>";
// print_r($response);
// echo "</pre>";
$response = json_decode($response, true);
curl_close($curl);
var_dump($response);
