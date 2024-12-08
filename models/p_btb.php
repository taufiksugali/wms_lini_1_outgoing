<?php
session_start();
require_once('../config/config.php');
require_once('../models/database.php');
include('m_user.php');
include('m_btb.php');

date_default_timezone_set("Asia/Jakarta");

$connection = new Database($host, $user, $pass, $database);
$data = new User($connection);
$sesi = new Btb($connection);
$btb = $sesi->session();
$get = $data->panggil($_SESSION['name']);
$data_user = $get->fetch_object();
$data_ses = $btb->fetch_object();
$session = $data_ses->running;
$pharsing = $data_ses->pharsing;
$do = $sesi->donum()->fetch_object()->no_do;
$next_do = $do + 1;


if (isset($_POST['createsession'])) {
  $tanggal = date('dmy');
  $id = $data_user->id;
  $ses = $data_ses->session;
  $d_pharsing = $ses . $id . $tanggal;
  $update = $sesi->create($d_pharsing);
  header('location: ../?page=sessionbtb');
}
if (isset($_POST['endsession'])) {
  $sesi->end();
  header('location: ../?page=sessionbtb');
}

if (isset($_POST['s_print'])) {
  $head               = $_POST['airlist'];
  $awb                = $head . '-' . $_POST['awb'];
  $noflight           = $_POST['noflight'];
  $shipment_type      = $_POST['shipment_type'];
  $comodity           = $_POST['comodity'];
  $agent              = $_POST['agent'];
  $shipper            = $_POST['shipper'];
  $pic                = $_POST['pic'];
  $quantity           = $_POST['qty'];
  $weight             = $_POST['weight'];
  $volume             = $_POST['volume'];
  $method             = $_POST['method'];
  $shipper_address    = $_POST['shipper_address'];
  $ra_id              = $_POST['ra'];
  $nama               = $_SESSION['name'];
  $tanggal            = date('y-m-d H:i:s');
  $status             = "proced";
  $user               = $_SESSION['name'];

  if ($sesi->check_awb($awb) !== true) {
    header('location: ../?page=btb&error=duplicate');
    exit;
  }

  if ($head == '' or $comodity == '' or $agent == '' or $shipper == '' or $pic == '' or $quantity == '' or $weight == '' or $shipper_address == '' or $ra_id == '' or $noflight == '' or $shipment_type == '') {
    header('location: ../?page=btb');
    $_SESSION['error'] = [
      'message' => 'Please fill in all fields',
    ];
    exit;
  }

  if (strlen($_POST['awb']) !== 8) {
    header('location: ../?page=btb');
    $_SESSION['error'] = [
      'message' => 'Invalid SMU number, SMU number must be 8 digits',
    ];
    exit;
  }
  $_SESSION['print']  = "on";

  $ctimestamp = date('y-m-d H:i:s');
  $data_input = "'$head','$awb','$next_do','$noflight','$shipment_type','$comodity','$agent','$shipper','$pic','$quantity','$weight','$volume','$tanggal','$status','$user','$pharsing','$ra_id','$shipper_address', '$ctimestamp'";
  $execute = $sesi->insert($data_input);




  if ($execute === false) {
    header('location: ../?page=btb&error=duplicate');
  } else {

    // $d_smu = $_POST['awb'];
    // $smu1 = substr($d_smu, 0, -4);
    // $smu2 = substr($d_smu, 4);
    // $d_btb = $sesi->cariBtb($awb)->fetch_object()->no_do;
    // $smupost = $head . $smu1 . '-' . $smu2;

    // $destin = $sesi->cariTlc($noflight)->fetch_object()->tlc;

    // if ($weight > $volume) {
    //   $cweight = $weight;
    // } else {
    //   $cweight = $volume;
    // }

    // $url = "https://www.rimbun.avter.co.id/api/check/awb/store";

    // $curl = curl_init($url);
    // curl_setopt($curl, CURLOPT_URL, $url);
    // curl_setopt($curl, CURLOPT_POST, true);
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // $headers = array(
    //   "Accept: application/json",
    //   "Content-Type: application/json",
    // );
    // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    // $data = <<<DATA
    // {
    //   "no_awb": "$smupost",
    //   "no_btb": $d_btb,
    //   "flight_no": "$noflight",
    //   "origin": "cgk",
    //   "destination": "$destin",
    //   "comodity": "$comodity",
    //   "agent_name": "$agent",
    //   "shipper_name": "$shipper",
    //   "colly": $quantity,
    //   "act_weight": $weight,
    //   "volume": $volume,
    //   "caw_weight": $cweight,
    //   "btb_duty_officer": "$user",
    // }
    // DATA;

    // curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    // //for debug only!
    // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    // $resp = curl_exec($curl);
    // curl_close($curl);
    // // var_dump($resp)
    // $resp = json_decode($resp, TRUE);
    // if (!$resp) {
    //   $respond = "no connection";
    // } else {
    //   if ($resp['success'] == true) {
    //     $respond = "berhasil";
    //   } else {
    //     $respond = "gagal";
    //   }
    // }

    // if ($respond == 'berhasil') {
    //   $_SESSION['result'] = [
    //     'pesan' => 'Data berhasil terupload',
    //     'color' => 'success',
    //     'aksi' => $respond
    //   ];
    // } else {
    //   $_SESSION['result'] = [
    //     'pesan' => 'Data gagal terupload',
    //     'color' => 'danger',
    //     'aksi' => $respond
    //   ];
    // }

    // end post
    header('location: ../views/print_do.php?data=' . $awb);
  }

  // var_dump($next_do);
}


if (isset($_POST['addflight'])) {
  $airline_id     = $_POST['airline_id'];
  if ($airline_id  == 'RI') {
    $flight       = $_POST['flight'];
    $destination  = $_POST['destination'];
    $tlc          = $_POST['tlc'];
    $newflight    = $flight . "-" . $tlc;
  } else {
    $flight       = $_POST['flight'];
    $destination  = $_POST['destination'];
    $tlc          = $_POST['tlc'];
    $newflight    = $airline_id . "-" . $flight;
  }

  // var_dump($airline_id); die();

  $proses = $sesi->insertflight($airline_id, $newflight, $destination, $tlc);
  if ($proses) {
    header('location: ../?page=addflight&proses=berhasil');
  } else {
    header('location: ../?page=addflight&proses=gagal');
  }
}

if (isset($_POST['editflight'])) {
  $flight = $_POST['flight_number'];
  $destination = $_POST['flight_destination'];
  $tlc = $_POST['flight_tlc'];
  $id = $_POST['flight_id'];

  $proses = $sesi->updateflight($id, $flight, $destination, $tlc);
  if ($proses) {
    header('location: ../?page=addflight&proses=berhasil');
  } else {
    header('location: ../?page=addflight&proses=gagal');
  }
}






if (isset($_GET['print'])) {
  $smu = $_GET['data'];
  $reprint = $_GET['print'];
  $_SESSION['print'] = "on";
  header('location: ../views/print_do.php?data=' . $smu . '&' . $reprint);
}
if (isset($_POST['p_reprint'])) {
  $id = $_POST['id_smu'];
  $awb = $_POST['awb'];
  $noflight = $_POST['noflight'];
  $comodity = $_POST['comodity'];
  $agent = $_POST['agent'];
  $shipper = $_POST['shipper'];
  $pic = $_POST['pic'];
  $quantity = $_POST['qty'];
  $weight = $_POST['weight'];
  $volume = $_POST['volume'];
  $nama = $_SESSION['name'];
  $ra_id = $_POST['ragent'];
  $shipper_adress = $_POST['shipper_address'];
  $tgl = date('y-m-d');
  $tanggal = new DateTime($tgl);
  $status = "revisi";
  $user = $_SESSION['name'];
  $_SESSION['print'] = "on";

  $view = $sesi->cargobyid($id);
  $sview = $view->fetch_object();
  $tanggal2 = new DateTime($sview->tanggal);
  $divtgl = $tanggal2->diff($tanggal);
  $div = $divtgl->d;
  $data = "smu='$awb',flight_no='$noflight',comodity='$comodity',agent_name='$agent',shipper_name='$shipper',pic='$pic',quantity='$quantity',weight='$weight',volume='$volume',tanggal='$tgl',status='$status',last_editor='$user'";
  $dataCargp = [
    'smu' => $awb,
    'flight_no' => $noflight,
    'comodity' => $comodity,
    'agent_name' => $agent,
    'shipper_name' => $shipper,
    'pic' => $pic,
    'quantity' => $quantity,
    'weight' => $weight,
    'volume' => $volume,
    'status' => $status,
    'last_editor' => $user,
    'shipper_address' => $shipper_adress,
    'ra_id' => $ra_id
  ];
  $proses = $sesi->updateCgo2($id, $dataCargp);
  if (!$proses) {
    header('location: ../?page=session-report&revisi=failed');
  } else {
    header('location: ../views/print_do.php?data=' . $awb . '&revisi');
  }
}
if (isset($_POST['print_session'])) {
  $_SESSION['print'] = "on";
  $data = $_GET['data'];
  header('location: ../views/print_session.php?data=' . $data);
}
if (isset($_POST['save_excel'])) {
  $session = $_GET['data'];
  $airline = $_POST['airline'];
  $filename = "data" . $session . ".xls";
  header("Content-Type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename= $filename");
?>
  Data session : <?php echo $_GET['data']; ?>
  <table border="1px">
    <thead>
      <tr>
        <th>No.</th>
        <th>SMU</th>
        <th>BTB</th>
        <th>FLIGHT</th>
        <th>COMODITY</th>
        <th>REGULATED AGENT</th>
        <th>AGENT</th>
        <th>SHIPPER</th>
        <th>PIC</th>
        <th>QTY</th>
        <th>WEIGHT</th>
        <th>VOLUME</th>
        <th>STATUS</th>
        <th>OFFICER</th>
        <th>LAST EDITOR</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($airline == 'all') {
        $a = $sesi->cargobyses($session);
      } else {
        $a = $sesi->cargobysesairline($session, $airline);
      }
      $no = 0;
      while ($result = $a->fetch_object()) : ?>
        <?php $no++; ?>
        <tr>
          <td><?php echo $no; ?></td>
          <td><?php echo $result->smu; ?></td>
          <td><?php echo $result->no_do; ?></td>
          <td><?php echo $result->flight_no; ?></td>
          <td><?php echo $result->comodity; ?></td>
          <td><?php echo @$result->ra_name; ?></td>
          <td><?php echo $result->agent_name; ?></td>
          <td><?php echo $result->shipper_name; ?></td>
          <td><?php echo $result->pic; ?></td>
          <td><?php echo $result->quantity; ?></td>
          <td><?php echo $result->weight; ?></td>
          <td><?php echo $result->volume; ?></td>
          <td><?php echo $result->status; ?></td>
          <td><?php echo $result->proses_by; ?></td>
          <td><?php echo $result->last_editor; ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
<?php };

if (isset($_POST['p_cancel'])) {
  if ($_SESSION['hak_akses'] !== "pic") {
    header('location: ../?page=session-report&cancel=failed');
  } else {
    $data = $_GET['data'];
    $proses = $sesi->updatecgoccl($data);
    if (!$proses) {
      header('location: ../?page=session-report&cancel=failed');
    } else {
      header('location: ../?page=session-report&cancel=succes');
    }
  }
}

if (isset($_POST['add_airline'])) {
  include('m_airline.php');
  $airline = new Airline($connection);
  $data = [
    'airline_id'   => $_POST['airline_id'],
    'airline_name' => $_POST['airline_name'],
  ];

  if ($airline->add_new_airline($data)) {
    header('location: ../?page=add_airline&proses=berhasil');
  } else {
    header('location: ../?page=add_airline&proses=gagal');
  }
}

if (isset($_POST['add_smucode'])) {
  include('m_smu_code.php');
  $airline = new Smucode($connection);
  $data = [
    'airline_id'   => $_POST['airline_id'],
    'code' => $_POST['code'],
  ];

  if ($airline->add_new_code($data)) {
    header('location: ../?page=add_smu_code&proses=berhasil');
  } else {
    header('location: ../?page=add_smu_code&proses=gagal');
  }
}
?>