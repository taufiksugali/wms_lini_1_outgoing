<?php 
include('models/m_kasir.php');
if(!isset($_GET['makesession'])){

  $makesession = "none";
}else{ 
  $makesession = "block";
}
?>
<div class = "position-relative alert-active" >
  <div class = "d-alert position-absolute start-50 translate-middle" style="display: <?php echo $makesession; ?>" data-aos="fade-right" data-aos-duration="2000">
    <div class="alert alert-dismissible  alert-danger fade show" role="alert">
      <strong>Make session first !</strong> To start data processing please create a session.
      <button type="button" class="alert-close btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
</div>
<div class = "kontener2 px-5 pt-4 " style="padding-bottom: 10em;">
  <nav style="--bs-breadcrumb-divider: '》';" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item ps-4" aria-current="page"><i class="fa-solid fa-database"></i> Cashier</h3></li>
      <li class="breadcrumb-item" aria-current="page">session</h3></li>
      <li class="breadcrumb-item active" aria-current="page">Create/view session</h3></li>
    </ol>
  </nav>
  <div class = "content p-4" style="font-family: roboto;">
    <h6><i class="fa-solid fa-compact-disc"></i> Session</h6>
    <div class="row g-0 p-0 mb-5">
      <div class = "description col-sm-2 px-3">
        <h5>Session create</h5>
        <p>Make session first to start payment processing </p>
      </div>
      <div class = "main-form col-sm-10 px-3 ">
        <?php 
        $sesi = new Kasir($connection);
        $btb = $sesi->session();
        $data_ses = $btb->fetch_object();
        $session = $data_ses->running;
        $pharsing = $data_ses->pharsing;
        if($session == "no"): ?>
          <form action="models/p_kasir.php" method="post">
            <button type="submit" class="btn btn-dark" id="createSession" name="createsession" onclick="return confirm('Anda yakin untuk membuat session baru?')">Create Session</button>
          </form>

        <?php endif; ?>
        <?php if($session == "yes") : ?>
          <div class="d-flex justify-content-center align-items-start">
            <h6>Ongoing:</h6>
            <h3 class="ms-2"><b><?php echo $pharsing; ?></b></h3>
          </div>
          <div class="d-flex justify-content-end">
            <form action="models/p_kasir.php" method="post">
              <button type="submit" class="btn btn-dark" id="endSession" name="endsession" onclick="return confirm('Anda yakin untuk mengakhiri session?')">End Session</button>
            </form>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="row g-0 p-0 m-0">
      <div class = "tes col-sm-2 px-3">
        <h5></h5>
        <p></p>
      </div>
    </div>
  </div>
</div>

