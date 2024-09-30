<?php 
if($_SESSION['hak_akses'] == "kasir" || $_SESSION['hak_akses'] == "supervisor"){
  echo "<script>window.location.href= '?page=home';</script>";
}
include('models/m_btb.php');
include('models/m_airline.php');
$sesi = new Btb($connection);
$m_airline = new Airline($connection);
$d_airline = $m_airline->call_all_airline();
if(isset($_GET['cancel'])){
  $alert = $_GET['cancel'];
  if ($alert == "failed") {
    $failed = "block";
    $sucess ="none";
  }
  elseif ($alert == "succes") {
    $failed = "none";
    $sucess ="block";
  }

}else{
 $failed = "none";
 $sucess ="none";
}


?>
<div class = "position-relative alert-active" >
  <div class = "d-alert position-absolute start-50 translate-middle" style="display: <?php echo $failed; ?>" data-aos="fade-right" data-aos-duration="2000">
    <div class="alert alert-dismissible  alert-danger fade show" role="alert">
      <strong>Cancel failed !</strong> Ask your PIC to cancel the Air Will Bill.
      <button type="button" class="alert-close btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
</div>
<div class = "position-relative alert-active" >
  <div class = "d-alert position-absolute start-50 translate-middle" style="display: <?php echo $sucess; ?>" data-aos="fade-right" data-aos-duration="2000">
    <div class="alert alert-dismissible  alert-success fade show" role="alert">
      <strong>Cancel failed !</strong> Ask your PIC to cancel the Air Will Bill.
      <button type="button" class="alert-close btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
</div>
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
        <p>Fill data besides to view or print report </p>
      </div>
      <div class = "main-form col-sm-10 px-3 ">
        <form class="sesreport" action="" method="post">
          <div>
            <div class="row">
              <div class="col-sm-3 mb-2">
                <label for="">Select Airline:</label>
                <select class="select2 form-select form-select-sm" name="airline" id="airline" data-placeholder="Select Airline" >
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
            <input list="sessionList" class="form-control form-control-sm" name="session">
            <datalist id="sessionList">
              <?php
              $data2 = $sesi->distses();
              while($result = $data2->fetch_object()) : ?>
                <option value="<?php echo $result->session; ?>"></option>

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

          $val_session = isset($_POST['search']) ? $_POST['session'] : $_GET['session'];
          if(@$_POST['airline']){
            if($_POST['airline'] == 'all'){
              $data3=$sesi->cargobyses($val_session);
            }else{
              $data3 = $sesi->cargo_by_airline($val_session, $_POST['airline']);              
            }
          }else{
            $data3=$sesi->cargobyses($val_session);
          }


          ?>
          <form action="<?php echo 'models/p_btb.php?data='.$val_session; ?>" method="post" target="_blank"> 
            <input type="text" name="airline" value="<?= (@$_POST['airline'])? $_POST['airline'] : 'all' ; ?>" id="excel_airline" hidden>
            <button type="submit" class="btn btn-outline-primary mb-2" name="print_session"><i class="fa-solid fa-print"></i> printdata</button>
            <button type="submit" class="btn btn-outline-success mb-2" name="save_excel"><i class="fa-solid fa-file-excel"></i> save to excel</button>
          </form>
          <table class="t-sesreport table table-hover table-striped text-nowrap" id="tSessionReport">
            <thead>
              <tr class="tex-center">
                <th class="text-center">#</th>
                <th class="text-center" style="width: 1000px;">ACTION</th>
                <th class="text-center">SESSION</th>
                <th class="text-center">SMU</th>
                <th class="text-center">BTB</th>
                <th class="text-center">FLIGHT</th>
                <th class="text-center">COMODITY</th>
                <th class="text-center">RA</th>
                <th class="text-center">AGENT</th>
                <th class="text-center">SHIPPER</th>
                <th class="text-center">PIC</th>
                <th class="text-center">QTY</th>
                <th class="text-center">WEIGHT</th>
                <th class="text-center">VOLUME</th>
                <th class="text-center">STATUS</th>
                <th class="text-center">OFFICER</th>
                <th class="text-center">LAST EDITOR</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $nomor =0;
              $tcoly = 0;
              $tweight = 0;
              $tvol = 0;

              $tn_smu = 0;
              $tn_coly = 0;
              $tn_weight = 0;
              $tn_volume = 0;

              $tr_smu = 0;
              $tr_coly = 0;
              $tr_weight = 0;
              $tr_volume = 0;

              $tv_smu = 0;
              $tv_coly = 0;
              $tv_weight = 0;
              $tv_volume = 0;

              while($cargo = $data3->fetch_object()):
                $nomor++;
                ?>
                <tr>
                  <td><?php echo $nomor; ?></td>
                  <?php 
                  if($cargo->status == "proced" || $cargo->status == "revisi"): ?>
                    <td class="d-flex m-0 py-3 justify-content-between" >
                      <a href="models/p_btb.php?data=<?php echo $cargo->smu; ?>&print=reprint"><button type="button" name="reprint" class="btn btn-sm btn-primary">reprint</button></a>

                      <a id="revisiBtn"
                      data-id="<?php echo $cargo->id; ?>"
                      data-smu="<?php echo $cargo->smu; ?>"
                      data-flight="<?php echo $cargo->flight_no; ?>"
                      data-comodity="<?php echo $cargo->comodity; ?>"
                      data-agent="<?php echo $cargo->agent_name; ?>"
                      data-shipper="<?php echo $cargo->shipper_name; ?>"
                      data-pic="<?php echo $cargo->pic; ?>"
                      data-quantity="<?php echo $cargo->quantity; ?>"
                      data-weight="<?php echo $cargo->weight; ?>"
                      data-volume="<?php echo $cargo->volume; ?>">
                      <button type="button" name="revisi" class="btn btn-sm btn-warning mx-2" data-bs-toggle="modal" data-bs-target="#editDo">revisi</button>
                    </a>

                    <form action="models/p_btb.php?data=<?php echo $cargo->smu; ?>" method="post">
                      <button class="btn btn-sm btn-danger" name="p_cancel" onclick="return confirm('Anda yakin untuk membatalkan btb <?php echo $cargo->smu; ?> ')">cancel</button>
                    </form>
                  </td>
                <?php endif; ?>
                <?php if($cargo->status == "complete"): ?>
                  <td class="d-flex m-0 py-3 justify-content-between" >
                    <a href="models/p_btb.php?data=<?php echo $cargo->smu; ?>&print=reprint"><button type="submit" name="reprint" class="btn btn-sm btn-primary">reprint</button></a>
                  </td>
                <?php endif; ?>
                <?php 
                if($cargo->status == "cancel"):?>
                  <td class="d-flex justify-content-center align-items-center m-0 py-3 bg-gradient" style="background-color: darkolivegreen; color: white; font-weight: bold; font-size: 1em;height: 100%;">
                    cancel
                  </td>
                <?php endif; ?>
                <?php 
                if($cargo->status == "void"):?>
                  <td class="d-flex justify-content-center align-items-center m-0 py-3 bg-gradient" style="background-color: red; color: white; font-weight: bold; font-size: 1em;height: 100%;">
                    Void
                  </td>
                <?php endif; ?>
                <td><?php echo $cargo->session; ?></td>
                <td><?php echo $cargo->smu; ?></td>
                <td><?php echo $cargo->no_do; ?></td>
                <td><?php echo $cargo->flight_no; ?></td>
                <td><?php echo $cargo->comodity; ?></td>
                <td><?php echo @$cargo->ra_name ; ?></td>
                <td><?php echo $cargo->agent_name; ?></td>
                <td><?php echo $cargo->shipper_name; ?></td>
                <td><?php echo $cargo->pic; ?></td>
                <td><?php echo $cargo->quantity; ?></td>
                <td><?php echo $cargo->weight; ?></td>
                <td><?php echo $cargo->volume; ?></td>
                <td><?php echo $cargo->status; ?></td>
                <td><?php echo $cargo->proses_by; ?></td>
                <td><?php echo $cargo->last_editor; ?></td>
              </tr>
              <?php 
              $tcoly = $tcoly + $cargo->quantity;
              $tweight = $tweight + $cargo->weight;
              $tvol = $tvol + $cargo->volume;
              if($cargo->status != "void"){
                $tn_smu     = $tn_smu + 1;
                $tn_coly    = $tn_coly + $cargo->quantity;
                $tn_weight  = $tn_weight + $cargo->weight;
                $tn_volume  = $tn_volume + $cargo->volume;
              }
              if ($cargo->status == "revisi") {
                $tr_smu = $tr_smu + 1;
                $tr_coly    = $tr_coly + $cargo->quantity;
                $tr_weight  = $tr_weight + $cargo->weight;
                $tr_volume  = $tr_volume + $cargo->volume;
              }
              if ($cargo->status == "void") {
                $tv_smu = $tv_smu + 1;
                $tv_coly    = $tv_coly + $cargo->quantity;
                $tv_weight  = $tv_weight + $cargo->weight;
                $tv_volume  = $tv_volume + $cargo->volume;
              }
              ?>
            <?php endwhile; ?>
          </tbody>
        </table>
        <div class="pertelaan-btb  mt-5 mb-3">
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td colspan="2" class="bg-secondary tex-center text-white">Total tonnage</td>
                  </tr>
                  <tr>
                    <td>Total SMU</td>
                    <td><?php echo $nomor; ?></td>
                  </tr>
                  <tr>
                    <td>Total Coly</td>
                    <td><?php echo $tcoly; ?></td>
                  </tr>
                  <tr>
                    <td>Total Weight</td>
                    <td><?php echo $tweight; ?></td>
                  </tr>
                  <tr>
                    <td>Total Volume</td>
                    <td><?php echo $tvol; ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td colspan="2" class="bg-secondary tex-center text-white">Nett tonnage</td>
                  </tr>
                  <tr>
                    <td>Total SMU</td>
                    <td><?php echo $tn_smu; ?></td>
                  </tr>
                  <tr>
                    <td>Total Coly</td>
                    <td><?php echo $tn_coly; ?></td>
                  </tr>
                  <tr>
                    <td>Total Weight</td>
                    <td><?php echo $tn_weight; ?></td>
                  </tr>
                  <tr>
                    <td>Total Volume</td>
                    <td><?php echo $tn_volume; ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td colspan="2" class="bg-secondary tex-center text-white">Revisi tonnage</td>
                  </tr>
                  <tr>
                    <td>Total SMU</td>
                    <td><?php echo $tr_smu; ?></td>
                  </tr>
                  <tr>
                    <td>Total Coly</td>
                    <td><?php echo $tr_coly; ?></td>
                  </tr>
                  <tr>
                    <td>Total Weight</td>
                    <td><?php echo $tr_weight; ?></td>
                  </tr>
                  <tr>
                    <td>Total Volume</td>
                    <td><?php echo $tr_volume; ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td colspan="2" class="bg-secondary tex-center text-white">Void tonnage</td>
                  </tr>
                  <tr>
                    <td>Total SMU</td>
                    <td><?php echo $tv_smu; ?></td>
                  </tr>
                  <tr>
                    <td>Total Coly</td>
                    <td><?php echo $tv_coly; ?></td>
                  </tr>
                  <tr>
                    <td>Total Weight</td>
                    <td><?php echo $tv_weight; ?></td>
                  </tr>
                  <tr>
                    <td>Total Volume</td>
                    <td><?php echo $tv_volume; ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      <?php }; ?>
    </div>
  </div>
</div>
</div>




<!-- Modal Revisi-->
<div class="modal fade" id="editDo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog thedialog">
    <div class="modal-content themodal">
      <form action="models/p_btb.php" method="post">
        <div class="modal-header theheader">
          <h5 class="modal-title" id="exampleModalLabel">Revisi data</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row gx-2 p-0 m-0">
            <div class="col-md-6">
              <input type="text" class="form-control form-control-sm" id="idSmu" name="id_smu" hidden>
              <label for="awb" class="form-label">No AWB</label>
              <input type="text" class="form-control form-control-sm" id="awb" placeholder="xxx-*" name="awb" required maxlength="12">
              <label for="noflight" class="form-label mt-3">No. Flight</label>
              <input type="text" class="form-control form-control-sm" id="noflight" placeholder="no-flight-xxx*" name="noflight">
              <label for="comodity" class="form-label mt-3">Comodity</label>
              <input type="text" class="form-control form-control-sm" id="comodity" placeholder="GAB/PKT/CONSOLE*" name="comodity">
              <label for="agent" class="form-label mt-3">Agent Name</label>
              <input type="text" class="form-control form-control-sm" id="agent" placeholder="name-agent*" name="agent">
              <label for="shipper" class="form-label mt-3">Shipper Name</label>
              <input type="text" class="form-control form-control-sm" id="shipper" placeholder="name-shipper*" name="shipper">
            </div>
            <div class="col-md-6">
              <label for="pic" class="form-label">PIC</label>
              <input type="text" class="form-control form-control-sm" id="pic" placeholder="pic-name*" name="pic">          
              <label for="qty" class="form-label mt-3">Quantity</label>
              <input type="text" class="form-control form-control-sm" id="qty" placeholder="quantity-xx*" name="qty">
              <label for="weight" class="form-label mt-3">Weight</label>
              <input type="text" class="form-control form-control-sm" id="weight" placeholder="wight-kg*" name="weight">
              <div class="row g-0 p-0 m-0">
                <div class="col-md-9">
                  <label for="volume" class="form-label mt-3">Volume</label>
                  <input type="text" class="form-control form-control-sm" id="volume" placeholder="volume-xx-kg*" name="volume" hidden>
                  <input type="text" class="form-control form-control-sm" id="volumeX" placeholder="volume-xx-kg*" name="volume_x" disabled>
                </div>

                <div class="col-md-3 d-flex align-items-end justify-content-center">
                 <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalVolume">volume</button>
               </div>
             </div>
           </div>
         </div>
       </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="p_reprint" name="p_reprint">Save changes</button>
      </div>
    </form>
  </div>
</div>
</div>


<!-- Modal volume-->
<div class="modal fade" id="modalVolume" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog thedialog">
    <div class="modal-content themodal">
      <div class="modal-header theheader">
        <h5 class="modal-title" id="exampleModalLabel">Tambah data volume</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <button type="button" class="btn btn-sm btn-info mb-2" id="addNew">add</button>
        <input type="text" id="value">
        <table class="">
          <thead>
            <tr>
              <th>#</th>
              <th>Length</th>
              <th>Width</th>
              <th>Height</th>
              <th>PCS</th>
              <th>Value</th>
            </tr>
          </thead>
          <tbody id="tbody">
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="calculate" data-bs-toggle="modal" data-bs-target="#editDo">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script src="assets/jquery/jquery-3.6.0.js" crossorigin="anonymous"></script>
<script src="assets/select2/select2.min.js"></script>
<script>
  $(document).ready(function(){
    $(".select2").select2({
      placeholder: $(this).data('placeholder'),
    })



    $(document).on("click", "#revisiBtn", function(){
      var id = $(this).data('id');
      var smu = $(this).data('smu');
      var flight = $(this).data('flight');
      var comodity = $(this).data('comodity');
      var agent = $(this).data('agent');
      var shipper = $(this).data('shipper');
      var pic = $(this).data('pic');
      var quantity = $(this).data('quantity');
      var weight = $(this).data('weight');
      var volume = $(this).data('volume');

      $("#idSmu").val(id);
      $("#awb").val(smu);
      $("#noflight").val(flight);
      $("#comodity").val(comodity);
      $("#agent").val(agent);
      $("#shipper").val(shipper);
      $("#pic").val(pic);
      $("#qty").val(quantity);
      $("#weight").val(weight);
      $("#volume").val(volume);
      $("#volumeX").val(volume);

    });
  });
</script>
<script>
  var a = 0;
  $("#addNew").click(function(){
    a = a + 1;
    $("#tbody").append("<tr>"
      +"<td>"+a+"</td>"
      +"<td><input type='text' id='l"+a+"'></td>"
      +"<td><input type='text' id='w"+a+"''></td>"
      +"<td><input type='text' id='h"+a+"''></td>"
      +"<td><input type='text' id='p"+a+"''></td>"
      +"<td><input type='text' id='v"+a+"''></td>"
      +"</tr>");
  });


  $("#calculate").click(function(){
    var total=0;
    for(let i=1; i<=a; i++){
      var length = "#l"+i;
      var width = "#w"+i;
      var height = "#h"+i
      var pcs = "#p"+i
      var vol = "#v"+i;

      var vala = $(length).val();
      var valb = $(width).val();
      var valc = $(height).val();
      var valp = $(pcs).val();

      var hasil = Math.round(((vala * valb * valc) / 6000)*valp);

      $(vol).val(hasil);
      total = total + hasil;
      console.log(hasil);
    }
      // var abc = parseInt(total);
    $("#value").val(total);
    console.log(total);

    $("#volume").val(total);
    $("#volumeX").val(total);
  })

  function setExcelAirline(el)
  {
    let airline = el.val();
    $("#excel_airline").val(airline);
  }
</script>