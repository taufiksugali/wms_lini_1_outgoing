<?php
session_start();
require_once('../config/config.php');
require_once('../models/database.php');
include('m_user.php');
include('m_kasir.php');
$connection = new Database($host, $user, $pass, $database);
$data = new User($connection);
$sesi = new Kasir($connection);
$kasir = $sesi->session();
$get = $data->panggil($_SESSION['name']);
$data_user = $get->fetch_object();
$data_ses = $kasir->fetch_object();
$session = $data_ses->running;
$pharsing = $data_ses->pharsing;
if (isset($_POST['createsession'])) {
  $tanggal = date('dmy');
  $id = $data_user->id;
  $ses = $data_ses->session;
  $d_pharsing = $ses . $id . $tanggal;
  $update = $sesi->create($d_pharsing);
  header('location: ../?page=session-kasir');
}
if (isset($_POST['endsession'])) {
  $sesi->end();
  header('location: ../?page=session-kasir');
}
if (isset($_POST['print_session'])) {
  $_SESSION['print'] = "on";
  $data = $_GET['data'];
  header('location: ../views/print_session_kasir.php?data=' . $data);
}
if (isset($_POST['save_session_excel'])) {
  $session = $_GET['data'];
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
        <th>NJG</th>
        <th>BTB</th>
        <th>REGULATED AGENT</th>
        <th>AGENT</th>
        <th>SHIPPER</th>
        <th>PIC</th>
        <th>NPWP</th>
        <th>ADMIN</th>
        <th>SEWA GUDANG</th>
        <th>KADE</th>
        <th>AP2</th>
        <th>AIRPORT TAX</th>
        <th>PPN</th>
        <th>MATERAI</th>
        <th>TOTAL</th>
        <th>STATUS</th>
        <th>OFFICER</th>
        <th>LAST EDITOR</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $a = $sesi->joindata($session);
      $no = 0;
      $admin = 0;
      $sg = 0;
      $kade = 0;
      $ap2 = 0;
      $as = 0;
      $ppn = 0;
      $materai = 0;
      $total = 0;
      while ($result = $a->fetch_object()) : ?>
        <?php $no++; ?>
        <tr>
          <td><?php echo $no; ?></td>
          <td><?php echo $result->smu; ?></td>
          <td><?php echo $result->njg; ?></td>
          <td><?php echo $result->no_do; ?></td>
          <td><?php echo @$result->ra_name; ?></td>
          <td><?php echo $result->agent_name; ?></td>
          <td><?php echo $result->shipper_name; ?></td>
          <td><?php echo $result->pic; ?></td>
          <td><?php echo $result->npwp; ?></td>
          <td><?php echo $result->admin; ?></td>
          <td><?php echo $result->sewa_gudang; ?></td>
          <td><?php echo $result->kade; ?></td>
          <td><?php echo $result->pjkp2u; ?></td>
          <td><?php echo $result->airport_tax; ?></td>
          <td><?php echo $result->ppn; ?></td>
          <td><?php echo $result->materai; ?></td>
          <td><?php echo $result->total; ?></td>
          <td><?php echo $result->status; ?></td>
          <td><?php echo $result->proses_by; ?></td>
          <td><?php echo $result->last_editor; ?></td>
        </tr>
        <?php
        $admin = $admin + $result->admin;
        $sg = $sg + $result->sewa_gudang;
        $kade = $kade + $result->kade;
        $ap2 = $ap2 + $result->pjkp2u;
        $as = $as + $result->airport_tax;
        $ppn = $ppn + $result->ppn;
        $materai = $materai + $result->materai;
        $total = $total + $result->total;
        ?>
      <?php endwhile; ?>
      <tr>
        <td></td>
        <td>Total</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><?php echo $admin; ?></td>
        <td><?php echo $sg; ?></td>
        <td><?php echo $kade; ?></td>
        <td><?php echo $ap2; ?></td>
        <td><?php echo $as; ?></td>
        <td><?php echo $ppn; ?></td>
        <td><?php echo $materai; ?></td>
        <td><?php echo $total; ?></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </tbody>
  </table>
<?php };
?>