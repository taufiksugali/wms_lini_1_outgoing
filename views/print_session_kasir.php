<?php
session_start();
if ($_SESSION['print']=="off") {
	header("location: ../");
}
require_once('../config/config.php');
require_once('../models/database.php');
include('../models/m_print.php');

$connection = new Database($host, $user, $pass, $database);
$data = new Printdo($connection);
$d_print = $data->joindata($_GET['data']);
$d_void = $data->joindata($_GET['data']);


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print DO</title>
  <link rel="stylesheet" href="../assets/css/print_session_kasir.css">
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">  
</head>
<body>
  <div id="pertelaan" class="px-2">
    <div class="judul text-center">
      <h6>PT POS LOGISTIK INDONESIA <br>
      PERGUDANGAN DOMESTIK SOEKARNO HATTA</h6>
    </div>
    <div class="header">
      <table>
        <tbody>
          <tr>
            <td>No Session</td>
            <td><?php echo $_GET['data']; ?></td>
          </tr>
          <tr>
            <td>PERIODE</td>
            <td id="targetPeriode"></td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <div class="rekapitulasi mt-2">
      Rekapitulasi
      <div class="d-flex">
        <table class="table-outgoing">
          <tr><td colspan="2" class="text-center">Total data Outgoing</td></tr>
          <tr>
            <td>Total Sewa Gudang</td>
            <td class=" text-end fst-italic px-1" id="tosg"></td>
          </tr>
          <tr>
            <td>Total ADM</td>
            <td class=" text-end fst-italic px-1" id="toad"></td>
          </tr>
          <tr>
            <td>Total Kade</td>
            <td class=" text-end fst-italic px-1" id="toka"></td>
          </tr>
          <tr>
            <td>Total PJKP2U-AP2</td>
            <td class=" text-end fst-italic px-1" id="toap"></td>
          </tr>
          <tr>
            <td>Total Airport Surcharge</td>
            <td class=" text-end fst-italic px-1" id="toas"></td>
          </tr>
          <tr>
            <td>Total PPN</td>
            <td class=" text-end fst-italic px-1" id="topp"></td>
          </tr>
          <tr>
            <td>Total Materai</td>
            <td class=" text-end fst-italic px-1" id="toma"></td>
          </tr>
          <tr>
            <td>Total</td>
            <td class=" text-end fst-italic px-1" id="toto"></td>
          </tr>
        </table>
        <table class="table-outgoing ms-2">
          <tr>
            <td colspan="2" class="text-center">Total data void</td>
          </tr>
          <tr>
            <td>Total Sewa Gudang</td>
            <td class=" text-end fst-italic px-1" id="tvsg"></td>
          </tr>
          <tr>
            <td>Total ADM</td>
            <td class=" text-end fst-italic px-1" id="tvad"></td>
          </tr>
          <tr>
            <td>Total Kade</td>
            <td class=" text-end fst-italic px-1" id="tvka"></td>
          </tr>
          <tr>
            <td>Total PJKP2U-AP2</td>
            <td class=" text-end fst-italic px-1" id="tvap"></td>
          </tr>
          <tr>
            <td>Total Airport Surcharge</td>
            <td class=" text-end fst-italic px-1" id="tvas"></td>
          </tr>
          <tr>
            <td>Total PPN</td>
            <td class=" text-end fst-italic px-1" id="tvpp"></td>
          </tr>
          <tr>
            <td>Total Materai</td>
            <td class=" text-end fst-italic px-1" id="tvma"></td>
          </tr>
          <tr>
            <td>Total</td>
            <td class=" text-end fst-italic px-1" id="tvto"></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="main-table mt-2">
      <div>
        DATA OUTGOING
        <table>
          <thead>
            <tr>
              <td>NO</td>
              <td>DATE</td>
              <td>NJG</td>
              <td>SHIPPER</td>
              <td>WEIGHT</td>
              <td>ADM</td>
              <td>SEWA GUDANG</td>
              <td>KADE</td>
              <td>AP2</td>
              <td>AIRPORT SURCHARGE</td>
              <td>PPN</td>
              <td>MATERAI</td>
              <td>TOTAL</td>
            </tr>
          </thead>
          <tbody>
           <?php 
           $nomor = 0;
           $tsmu = 0;
           $tadm = 0;
           $tsg = 0;
           $tkade = 0;
           $tap2 = 0;
           $tas = 0;
           $tppn = 0;
           $tmaterai = 0;
           $ttotal = 0;
           $vsmu= 0;
           $vadm = 0;
           $vsg = 0;
           $vkade = 0;
           $vap2 = 0;
           $vas = 0;
           $vppn = 0;
           $vmaterai = 0;
           $vtotal = 0;
           while($result2 = $d_print->fetch_object()) {
            if($result2->status != "void"){
              $nomor++;
              ?>
              <tr class="dtr" id="dtr<?php echo $number=$nomor; ?>">
                <td><?php echo number_format($number); ?>.</td>
                <td class="tanggal"><?php echo $result2->stimestamp; ?></td>
                <td><?php echo $result2->njg; ?></td>
                <td><?php echo $result2->shipper_name; ?></td>
                <td class="text-center"><?php echo $result2->weight; ?></td>
                <td class="text-end pe-1" style="width: 5%;"><?php echo number_format($result2->admin); ?></td>
                <td class="text-end pe-1" style="width: 8%;"><?php echo number_format($result2->sewa_gudang); ?></td>
                <td class="text-end pe-1" style="width: 8%;"><?php echo number_format($result2->kade); ?></td>
                <td class="text-end pe-1" style="width: 8%;"><?php echo number_format($result2->pjkp2u); ?></td>
                <td class="text-end pe-1" style="width: 8%;"><?php echo number_format($result2->airport_tax); ?></td>
                <td class="text-end pe-1" style="width: 8%;"><?php echo number_format($result2->ppn); ?></td>
                <td class="text-end pe-1" style="width: 8%;"><?php echo number_format($result2->materai); ?></td>
                <td class="text-end pe-1" style="width: 8%;"><?php echo number_format($result2->total); ?></td>
              </tr>
              <?php 

              $tsmu = $tsmu + $result2->weight;
              $tadm = $tadm +$result2->admin;
              $tsg = $tsg + $result2->sewa_gudang;
              $tkade = $tkade + $result2->kade;
              $tap2 = $tap2 +$result2->pjkp2u;
              $tas = $tas + $result2->airport_tax;
              $tppn = $tppn + $result2->ppn;
              $tmaterai = $tmaterai + $result2->materai;
              $ttotal = $ttotal + $result2->total;

              ?>
              <?php
            };
          };
          ?>
          <tr>
            <td colspan="4" class="ps-4">TOTAL</td>
            <td class="text-center"><?php echo number_format($tsmu); ?></td>
            <td class="text-end pe-1" style="width: 5%;" id="nettad"><?php echo number_format($tadm); ?></td>
            <td class="text-end pe-1" style="width: 8%;" id="nettsg"><?php echo number_format($tsg); ?></td>
            <td class="text-end pe-1" style="width: 8%;" id="netkad"><?php echo number_format($tkade); ?></td>
            <td class="text-end pe-1" style="width: 8%;" id="nettap"><?php echo number_format($tap2); ?></td>
            <td class="text-end pe-1" style="width: 8%;" id="nettas"><?php echo number_format($tas); ?></td>
            <td class="text-end pe-1" style="width: 8%;" id="nettpp"><?php echo number_format($tppn); ?></td>
            <td class="text-end pe-1" style="width: 8%;" id="nettma"><?php echo number_format($tmaterai); ?></td>
            <td class="text-end pe-1" style="width: 8%;" id="nettto"><?php echo number_format($ttotal); ?></td>
          </tr>                   
        </tbody>
      </table>
    </div>
  </div>
  <div class="void-table mt-2">
      <div>
        DATA VOID
        <table>
          <thead>
            <tr>
              <td>NO</td>
              <td>DATE</td>
              <td>NJG</td>
              <td>SHIPPER</td>
              <td>WEIGHT</td>
              <td>ADM</td>
              <td>SEWA GUDANG</td>
              <td>KADE</td>
              <td>AP2</td>
              <td>AIRPORT SURCHARGE</td>
              <td>PPN</td>
              <td>MATERAI</td>
              <td>TOTAL</td>
            </tr>
          </thead>
          <tbody>
           <?php 
           $nomor = 0;
           $tsmu = 0;
           $tadm = 0;
           $tsg = 0;
           $tkade = 0;
           $tap2 = 0;
           $tas = 0;
           $tppn = 0;
           $tmaterai = 0;
           $ttotal = 0;
           $vsmu= 0;
           $vadm = 0;
           $vsg = 0;
           $vkade = 0;
           $vap2 = 0;
           $vas = 0;
           $vppn = 0;
           $vmaterai = 0;
           $vtotal = 0;
           while($result3 = $d_void->fetch_object()) {
            if($result3->status == "void"){
              $nomor++;
              ?>
              <tr>
                <td><?php echo $nomor; ?></td>
                <td><?php echo $result3->stimestamp; ?></td>
                <td><?php echo $result3->njg; ?></td>
                <td><?php echo $result3->shipper_name; ?></td>
                <td class="text-center"><?php echo $result3->weight; ?></td>
                <td class="text-end pe-1" style="width: 5%;"><?php echo number_format($result3->admin); ?></td>
                <td class="text-end pe-1" style="width: 8%;"><?php echo number_format($result3->sewa_gudang); ?></td>
                <td class="text-end pe-1" style="width: 8%;"><?php echo number_format($result3->kade); ?></td>
                <td class="text-end pe-1" style="width: 8%;"><?php echo number_format($result3->pjkp2u); ?></td>
                <td class="text-end pe-1" style="width: 8%;"><?php echo number_format($result3->airport_tax); ?></td>
                <td class="text-end pe-1" style="width: 8%;"><?php echo number_format($result3->ppn); ?></td>
                <td class="text-end pe-1" style="width: 8%;"><?php echo number_format($result3->materai); ?></td>
                <td class="text-end pe-1" style="width: 8%;"><?php echo number_format($result3->total); ?></td>
              </tr>
              <?php 
              
              $tsmu = $tsmu + $result3->weight;
              $tadm = $tadm +$result3->admin;
              $tsg = $tsg + $result3->sewa_gudang;
              $tkade = $tkade + $result3->kade;
              $tap2 = $tap2 +$result3->pjkp2u;
              $tas = $tas + $result3->airport_tax;
              $tppn = $tppn + $result3->ppn;
              $tmaterai = $tmaterai + $result3->materai;
              $ttotal = $ttotal + $result3->total;
              
              ?>
              <?php
            };
          };
          ?>
          <tr>
            <td colspan="4" class="ps-4">TOTAL</td>
            <td class="text-center"><?php echo number_format($tsmu); ?></td>
            <td class="text-end pe-1" style="width: 5%;" id="vodtad"><?php echo number_format($tadm); ?></td>
            <td class="text-end pe-1" style="width: 8%;" id="vodtsg"><?php echo number_format($tsg); ?></td>
            <td class="text-end pe-1" style="width: 8%;" id="vodkad"><?php echo number_format($tkade); ?></td>
            <td class="text-end pe-1" style="width: 8%;" id="vodtap"><?php echo number_format($tap2); ?></td>
            <td class="text-end pe-1" style="width: 8%;" id="vodtas"><?php echo number_format($tas); ?></td>
            <td class="text-end pe-1" style="width: 8%;" id="vodtpp"><?php echo number_format($tppn); ?></td>
            <td class="text-end pe-1" style="width: 8%;" id="vodtma"><?php echo number_format($tmaterai); ?></td>
            <td class="text-end pe-1" style="width: 8%;" id="vodtto"><?php echo number_format($ttotal); ?></td>
          </tr>                   
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php 
$target = "dtr".($number);
?>

<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jquery -->
<script src="../assets/jquery/jquery-3.6.0.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    // $('#smu').val("");
    // $('#coly').val("");
    // $('#weight').val("");
    // $('#volume').val("");
    // $('#revisi').val("");
    // $('#cancel').val("");
    var a = $("#nettad").text();
    var b = $("#nettsg").text();
    var c = $("#netkad").text();
    var d = $("#nettap").text();
    var e = $("#nettas").text();
    var f = $("#nettpp").text();
    var g = $("#nettma").text();
    var h = $("#nettto").text();
    var i = $("#vodtad").text();
    var j = $("#vodtsg").text();
    var k = $("#vodkad").text();
    var l = $("#vodtap").text();
    var m = $("#vodtas").text();
    var n = $("#vodtpp").text();
    var o = $("#vodtma").text();
    var p = $("#vodtto").text();

    $("#toad").text(a);
    $("#tosg").text(b);
    $("#toka").text(c);
    $("#toap").text(d);
    $("#toas").text(e);
    $("#topp").text(f);
    $("#toma").text(g);
    $("#toto").text(h);
    $("#tvad").text(i);
    $("#tvsg").text(j);
    $("#tvka").text(k);
    $("#tvap").text(l);
    $("#tvas").text(m);
    $("#tvpp").text(n);
    $("#tvma").text(o);
    $("#tvto").text(p);


    var x = $(".main-table tbody .dtr:first-child .tanggal").text();
    var y = $(".main-table tbody #<?php echo $target; ?> .tanggal").text();
    var z = x+"--"+y;

    $("#targetPeriode").text(z);
    console.log(z);
  window.print();
  })
</script>
</body>
</html> 