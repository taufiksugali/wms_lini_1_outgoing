<?php 
if(!isset($_GET['proses'])){

  $proccess = "none";
  $proccess2 = "none";
}else{ 
  $result = ($_GET['proses']);
  if($result=="berhasil"){
    $proccess = "block";
    $proccess2 = "none";
  }
  elseif($result=="gagal"){
    $proccess = "none";
    $proccess2 = "block";
  }
}
include('models/m_airline.php');

?>

<div class = "position-relative alert-active" >
  <div class = "d-alert position-absolute start-50 translate-middle" style="display: <?php echo $proccess; ?>" data-aos="fade-right" data-aos-duration="2000">
    <div class="alert alert-dismissible  alert-success fade show" role="alert">
      <strong>Success!</strong> The data you have entered has been successfully entered.
      <button type="button" class="alert-close btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
</div>
<div class = "position-relative alert-active" >
  <div class = "d-alert position-absolute start-50 translate-middle" style="display: <?php echo $proccess2; ?>" data-aos="fade-right" data-aos-duration="2000">
    <div class="alert alert-dismissible  alert-danger fade show" role="alert">
      <strong>Failed!</strong> The data you have entered has been failed to enter.
      <button type="button" class="alert-close btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
</div>
<div class = "kontener2 px-5 py-4">
  <nav style="--bs-breadcrumb-divider: '》';" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item ps-4" aria-current="page"><i class="fa-solid fa-database"></i> Acceptance</h3></li>
      <li class="breadcrumb-item active" aria-current="page">Add flight</h3></li>
    </ol>
  </nav>
  <div class = "content p-4" style="font-family: roboto;">
    <h6><i class="fa-solid fa-circle-plus"></i> Add airline</h6>
    <form action="models/p_btb.php" method="post">
      <div class="row g-0 p-0 m-0">
        <div class = "description col-sm-2 px-3">
          <h5>Airline ID</h5>
          <p>Add airline ID and the name of airline</p>
        </div>
        <div class = "col-sm-4 px-3" id="formAddFlight">
          <div class="mb-3">
            <label for="awb" class="form-label">Airline ID</label>
            <input type="text" class="form-control form-control-sm" id="flight" placeholder="Airline ID" name="airline_id" required>
            <div class="d-flex justify-content-start">
              <div>
                <label for="noflight" class="form-label mt-3">Airline Name</label>
                <input type="text" class="form-control form-control-sm" id="destination" placeholder="Airline name" name="airline_name">
              </div>
            </div>
            <button type="submit" class="btn btn-dark btn-sm mt-3 " id="addAirLine" name="add_airline">Add new airline</button>
          </div>

        </div>
        <div class="col-sm-6 ">
          <div class="tableflightnumber">
            <?php 
            $flight = new Airline($connection);
            $result = $flight->call_all_airline();
            ?>
            <table class="table table-hover" id="tableFlight">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Airline ID</th>
                  <th>Airline Name</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $nomor = 0;
                while($s_result=$result->fetch_object()) : 
                  $nomor++; ?>
                  <tr>
                    <th><?php echo $nomor; ?></th>
                    <th><?php echo $s_result->airline_id; ?></th>
                    <th><?php echo $s_result->airline_name; ?></th>
                  </tr>                  
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>



<!-- Modal edit flight-->
<div class="modal fade" id="modalEditFlight" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog thedialog">
    <div class="modal-content themodal">
      <div class="modal-header theheader">
        <h5 class="modal-title" id="exampleModalLabel">Void SMU</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="models/p_btb.php" method="post"> 
        <div class="modal-body d-flex justify-content-center">
          <div>
            <div class="px-3" >
              <div  >
                Flight Number:
                <input type="text" class="form-control" id="flightNumber" name="flight_number" >
              </div>
              <div class="d-flex">
                <div >
                  Destination:
                  <input type="text" class="form-control" id="flightDestination" name="flight_destination" >
                </div>
                <div >
                  TLC:
                  <input type="text" class="form-control" id="flightTlc" name="flight_tlc" >
                  <input type="text" class="form-control" id="flightId" name="flight_id" hidden>
                </div>
              </div>
              <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-outline-primary" name="editflight">Edit</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- <script src="assets/jquery/jquery-3.6.0.js" crossorigin="anonymous"></script> -->
<script>
  $(document).ready(function(){
    $(document).on("click", "#btnEdit", function(){
      var id = $(this).data('id');
      var flight = $(this).data('flight');
      var destination = $(this).data('destination');
      var tlc = $(this).data('tlc');

      $("#modalEditFlight #flightNumber").val(flight);
      $("#modalEditFlight #flightDestination").val(destination);
      $("#modalEditFlight #flightTlc").val(tlc);
      $("#modalEditFlight #flightId").val(id);
    })
  })

</script>