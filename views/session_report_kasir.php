<?php
if($_SESSION['hak_akses'] == "acceptance" || $_SESSION['hak_akses'] == "pic"){
  echo "<script>window.location.href= '?page=home';</script>";
}
include('models/m_kasir.php');
include('models/m_data_ap2.php');
include('models/m_airline.php');
$sesi = new Kasir($connection);
$data_ap = new Data_ap2($connection);
$m_airline = new Airline($connection);
$d_airline = $m_airline->call_all_airline();



if(isset($_POST['btn_void'])){
  $smu = $_POST['smu'];
  $id = $_POST['id'];
  $subsmu = substr($smu, 4);
  $newsub="void";
  $newsmu = $newsub.$subsmu;
  $keterangan1 = $_POST['keterangan'];
  $keterangan2 = $_POST['tanggalan'];
  $keterangan = $keterangan1." tanggal awal(".$keterangan2.")";
  $call = $sesi->callidfromcargo($smu);
  $idcargo = $call->fetch_object()->id;

  // $id_void = $data_ap->select_by_payment_id($id);
  //    $id_void = $id_void->fetch_object();
  //    $data_void = array(
  //     'USR' => 'USR_APK',
  //     'PSW' => '*APK2020#',
  //     'TANGGAL' => date('Y-m-d H:i', strtotime($id_void->tanggal)),
  //     'NO_INVOICE' => $id_void->no_invoice,
  //     'HAWB' => '0',
  //     'SMU' => substr($smu, 0, -1),
  //   );

  // var_dump($data_void); die();

  $voidcargo = $sesi->voidsmutocargo($idcargo, $newsmu, "void", $_SESSION['name']);
  if(!$voidcargo){
    echo "<script>alert('Cargo gagal void')</script>";
  }else{
    $void = $sesi->voidsmu($id, $newsmu, $keterangan);
    if(!$void){
      echo "<script>alert('Smu gagal void')</script>";
    }else{
     $id_void = $data_ap->select_by_payment_id($id);
     $id_void = $id_void->fetch_object();
     $data_void = array(
      'USR' => 'user.api.poslog',
      'PSW' => 'user.api.poslog',
      'TANGGAL' => date('Y-m-d H:i', strtotime($id_void->tanggal)),
      'NO_INVOICE' => $id_void->no_invoice,
      'HAWB' => '0',
      'SMU' => substr($smu, 0, -1),
    );
     
     $curl = curl_init();
     curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://apisigo.angkasapura2.co.id/api/void_invo_dtl',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $data_void,
      CURLOPT_HTTPHEADER => array(
        'Cookie: dtCookie=CD78B9A24184B932B72CB79ED316B71D|X2RlZmF1bHR8MQ'
      ),
    ));

     $response = curl_exec($curl);

     curl_close($curl);
     $response = json_decode($response, true);

     if(!$response){
      $status = 'no connection';
    }else{
      if($response['status'] == '200'){
        $status = 'berhasil';
      }elseif($response['status'] == '500'){
        $status = 'internal server eror';
      }else{
        $status = 'Error Unknown';
      }
    }

    if($status == 'berhasil'){
      $update = $data_ap->update_void($id);
    }else{
      $update = $data_ap->update_void_gagal($id);
    }
    (@$response['message'])? $notif = $response['message'] : $notif = 'error occured';
    echo "<script>alert('Void berhasil Ap status : ".$response['message']."')</script>";
  }
}
}
?>

<div class = "kontener2 px-5 pt-4 " style="padding-bottom: 10em;">
  <nav style="--bs-breadcrumb-divider: '》';" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item ps-4" aria-current="page"><i class="fa-solid fa-database"></i> Acceptance</h3></li>
      <li class="breadcrumb-item" aria-current="page">session</h3></li>
      <li class="breadcrumb-item active" aria-current="page">Session report</h3></li>
    </ol>
  </nav>
  <div class = "content p-4" style="font-family: roboto;">
    <h6><i class="fa-solid fa-compact-disc"></i> Session</h6>
    <div class="row g-0 p-0 mb-5">
      <div class = "description col-sm-2 px-3">
        <h5>Session report</h5>
        <p>Fill data besides to view or print </p>
      </div>
      <div class = "main-form col-sm-10 px-3 ">
        <form class="sesreport" action="" method="post">
          <div>
            <div class="row">
              <div class="col-sm-3 mb-2">
                <label for="">Select Airline:</label>
                <select class="select2 form-select form-select-sm" name="airline" id="airline" data-placeholder="Select Airline">
                  <option></option>
                  <option value="all">All Airline</option>
                  <?php 
                  while ($airline = $d_airline->fetch_object()) {
                    ?>
                    <option value="<?= $airline->airline_name ?>"><?= $airline->airline_name?></option>
                    <?php
                  }
                  ?>
                </select>                
              </div>
            </div>
            <label class="" for="">Session : </label>
            <input list="sessionList" class="form-control form-control-sm" name="session" id="tester">
            <datalist id="sessionList">
              <?php 
              $val = $sesi->distses();
              while($result= $val->fetch_object()):?>
                <option value="<?php echo $result->session_kasir; ?>">by: <?php echo $result->proses_by; ?></option>
              <?php endwhile; ?>            
            </datalist>
            <button type="submit" class="btn btn-sm" name="search">search</button>
          </div>
        </form>
      </div>
    </div>
    <div class="row g-0 p-0 m-0">
      <div class = " tabel-area col-sm-12 px-3 pt-3">
        <?php 
        if (isset($_POST['search']) || isset($_GET['session'])) {
          if(isset($_POST['search'])){
            $val_session = $_POST['session'];
          }else{
            $val_session = $_GET['session'];
          }
          if(@$_POST['airline']){
            if($_POST['airline'] == 'all'){
              $data3=$sesi->joindata($val_session);
            }else{
              $data3=$sesi->joindata_airline($val_session, $_POST['airline']);
            }
          }else{
            $data3=$sesi->joindata($val_session);
          }

          ?>
          <form action="<?php echo 'models/p_kasir.php?data='.$val_session; ?>" method="post" target="_blank"> 
            <button type="submit" class="btn btn-outline-primary mb-2" name="print_session"><i class="fa-solid fa-print"></i> printdata</button>
            <button type="submit" class="btn btn-outline-success mb-2" name="save_session_excel"><i class="fa-solid fa-file-excel"></i> save to excel</button>
          </form>
          <table class="t-sesreport table table-hover table-striped text-nowrap table-bordered" id="tSessionReport">
            <thead>
              <tr class="tex-center">
                <th class="text-center">#</th>
                <th class="text-center" style="width: 1000px;">ACTION</th>
                <th class="text-center">SESSION</th>
                <th class="text-center">AIR_WILL_BILL</th>
                <th class="text-center">BTB</th>
                <th class="text-center">AGENT</th>
                <th class="text-center">SHIPPER</th>
                <th class="text-center">ADMIN</th>
                <th class="text-center">SEWA_GUDANG</th>
                <th class="text-center">KADE</th>
                <th class="text-center">AP2</th>
                <th class="text-center">A_SURCHARGE</th>
                <th class="text-center">PPN</th>
                <th class="text-center">MATERAI</th>
                <th class="text-center">TOTAL</th>
                <th class="text-center">PIC</th>
                <th class="text-center">STATUS</th>
                <th class="text-center">OFFICER</th>
                <th class="text-center">LAST_EDITOR</th>
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
              while($result2 = $data3->fetch_object()) {
                $nomor++;
                ?>
                <tr>
                  <td><?php echo $nomor; ?></td>

                  <td class="d-flex justify-content-between">
                    <?php 
                    if ($result2->status != 'void') : ?>
                      <form action="views/print_one_invoice.php?data=<?php echo $result2->smu; ?>" method="post" target="_blank">
                        <button type="submit" class="btn btn-outline-primary" name="reprint">reprint</button>
                      </form>



                      <a href="" data-smu="<?php echo $result2->smu; ?> " data-id="<?php echo $result2->id; ?>" data-tanggalan="<?php echo $result2->tanggalan; ?>" id="btnVoid" data-bs-toggle="modal" data-bs-target="#modalVoid">
                        <button type="button" class="btn btn-outline-danger ms-2" name="void" onclick="return confirm('Anda yakin untuk void data ini ?')" >
                          void
                        </button>
                      </a>

                    <?php endif; ?>
                    <?php 
                    if ($result2->status == 'void'){
                      echo "void by: $result2->last_editor";
                    }
                    ?>
                  </td>
                  <td><?php echo $result2->session_kasir; ?></td>
                  <td><?php echo $result2->smu; ?></td>
                  <td><?php echo $result2->no_do; ?></td>
                  <td><?php echo $result2->agent_name; ?></td>
                  <td><?php echo $result2->shipper_name; ?></td>
                  <td><?php echo number_format($result2->admin); ?></td>
                  <td><?php echo number_format($result2->sewa_gudang); ?></td>
                  <td><?php echo number_format($result2->kade); ?></td>
                  <td><?php echo number_format($result2->pjkp2u); ?></td>
                  <td><?php echo number_format($result2->airport_tax); ?></td>
                  <td><?php echo number_format($result2->ppn); ?></td>
                  <td><?php echo number_format($result2->materai); ?></td>
                  <td><?php echo number_format($result2->total); ?></td>
                  <td><?php echo $result2->pic; ?></td>
                  <td><?php echo $result2->status; ?></td>
                  <td><?php echo $result2->proses_by; ?></td>
                  <td><?php echo $result2->last_editor; ?></td>
                </tr>
                <?php 
                if($result2->status != "void"){
                  $tsmu = $tsmu + 1;
                  $tadm = $tadm +$result2->admin;
                  $tsg = $tsg + $result2->sewa_gudang;
                  $tkade = $tkade + $result2->kade;
                  $tap2 = $tap2 +$result2->pjkp2u;
                  $tas = $tas + $result2->airport_tax;
                  $tppn = $tppn + $result2->ppn;
                  $tmaterai = $tmaterai + $result2->materai;
                  $ttotal = $ttotal + $result2->total;
                }elseif($result2->status == "void"){
                  $vsmu = $vsmu + 1;
                  $vadm = $vadm +$result2->admin;
                  $vsg = $vsg + $result2->sewa_gudang;
                  $vkade = $vkade + $result2->kade;
                  $vap2 = $vap2 +$result2->pjkp2u;
                  $vas = $vas + $result2->airport_tax;
                  $vppn = $vppn + $result2->ppn;
                  $vmaterai = $vmaterai + $result2->materai;
                  $vtotal = $vtotal + $result2->total;
                }
                ?>
                <?php 
              }; 
              $tadm = $tadm;
              $tsg = $tsg;
              $tkade = $tkade;
              $tap2 = $tap2;
              $tas = $tas;
              $tppn = $tppn;
              $tmaterai = $tmaterai;
              $ttotal = $ttotal;

              $vadm = $vadm;
              $vsg = $vsg;
              $vkade = $vkade;
              $vap2 = $vap2;
              $vas = $vas;
              $vppn = $vppn;
              $vmaterai = $vmaterai;
              $vtotal = $vtotal;
              ?>
            </tbody>
          </table>
          <div class="pertelaan-btb  mt-5 d-flex justify-content-between">
            <div class=" d-flex justify-content-between pe-4" style="width: 50%">
              <div>
                Gross <br>
                Total SMU <br>
                Total Admin <br>
                Total Sewa Gudang<br>
                Total Kade<br>
                Total PJKP2U<br>
                Total Airport Surcharge<br>
                Total PPN<br>
                Total Materai <br>
                Grand Total
              </div>
              <div class="text-end">
                <br>
                <?php echo $tsmu+$vsmu."smu"; ?><br>
                <?php echo "Rp ".number_format($tadm+$vadm); ?> <br>
                <?php echo "Rp ".number_format($tsg+$vsg); ?><br>
                <?php echo "Rp ".number_format($tkade+$vkade); ?><br>
                <?php echo "Rp ".number_format($tap2+$vap2); ?><br>
                <?php echo "Rp ".number_format($tas+$vas); ?><br>
                <?php echo "Rp ".number_format($tppn+$vppn); ?><br>
                <?php echo "Rp ".number_format($tmaterai+$vmaterai); ?> <br>
                <?php echo "Rp ".number_format($ttotal+$vtotal); ?>
                <br>
              </div>
            </div>
          </div>
          <div class="pertelaan-btb  mt-5 d-flex justify-content-between">
            <div class=" d-flex justify-content-between pe-4" style="width: 50%">
              <div>
                Nett <br>
                Total SMU <br>
                Total Admin <br>
                Total Sewa Gudang<br>
                Total Kade<br>
                Total PJKP2U<br>
                Total Airport Surcharge<br>
                Total PPN<br>
                Total Materai <br>
                Grand Total
              </div>
              <div class="text-end">
                <br>
                <?php echo "$tsmu smu"; ?><br>
                <?php echo "Rp ".number_format($tadm); ?> <br>
                <?php echo "Rp ".number_format($tsg); ?><br>
                <?php echo "Rp ".number_format($tkade); ?><br>
                <?php echo "Rp ".number_format($tap2); ?><br>
                <?php echo "Rp ".number_format($tas); ?><br>
                <?php echo "Rp ".number_format($tppn); ?><br>
                <?php echo "Rp ".number_format($tmaterai); ?> <br>
                <?php echo "Rp ".number_format($ttotal); ?>
                <br>
              </div>
            </div>
            <div class=" d-flex justify-content-between pe-4" style="width: 50%">
              <div>
                void <br>
                Total SMU <br>
                Total Admin <br>
                Total Sewa Gudang<br>
                Total Kade<br>
                Total PJKP2U<br>
                Total Airport Surcharge<br>
                Total PPN<br>
                Total Materai <br>
                Grand Total
              </div>
              <div>
               <br>
               <?php echo "$vsmu smu"; ?><br>
               <?php echo "Rp ".number_format($vadm); ?> <br>
               <?php echo "Rp ".number_format($vsg); ?><br>
               <?php echo "Rp ".number_format($vkade); ?><br>
               <?php echo "Rp ".number_format($vap2); ?><br>
               <?php echo "Rp ".number_format($vas); ?><br>
               <?php echo "Rp ".number_format($vppn); ?><br>
               <?php echo "Rp ".number_format($vmaterai); ?> <br>
               <?php echo "Rp ".number_format($vtotal); ?>
             </div>
           </div>
         </div>
       <?php }; ?>
     </div>
   </div>
 </div>
</div>


<!-- Modal void-->
<div class="modal fade" id="modalVoid" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog thedialog">
    <div class="modal-content themodal">
      <div class="modal-header theheader">
        <h5 class="modal-title" id="exampleModalLabel">Void SMU</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="post"> 
        <div class="modal-body">
          <div>
            <input type="text" class="form-control" id="smu" hidden name="smu">
            <input type="text" id="tanggalan" name="tanggalan" hidden>
            <label for="smux" class="form-label">Nomor SMU</label>
            <input type="text" class="form-control" id="smux" disabled name="smux">
            <input type="text" class="form-control" id="id" name="id" hidden>
            <label for="void" class="form-label mt-3">Keterangan Void :</label>
            <textarea class="form-control" id="void" rows="3" required name="keterangan"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-warning" name="btn_void" >Void</button>
        </div>
      </form>
    </div>
  </div>
</div>






<script src="assets/jquery/jquery-3.6.0.js" crossorigin="anonymous"></script>
<script src="assets/dataTables/datatables.js"></script>
<script src="assets/select2/select2.min.js"></script>
<script>
  $(document).ready(function(){

    $(".select2").select2({
      placeholder: $(this).data('placeholder'),
    });

    $(document).on("click", "#btnVoid", function(){
      var smu = $(this).data('smu');
      var id = $(this).data('id');
      var tanggalan = $(this).data('tanggalan');

      $("#modalVoid #smu").val(smu);
      $("#modalVoid #smux").val(smu);
      $("#modalVoid #id").val(id);
      $("#modalVoid #tanggalan").val(tanggalan);
      $('#tSessionReport').DataTable();
    });
  })

</script>
