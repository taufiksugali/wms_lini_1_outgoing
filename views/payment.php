<?php 
include('models/m_kasir.php');
$data = new Kasir($connection);
// cek session
$btb = $data->session();
$session = $btb->fetch_object()->running;
// ambil data flight untuk list flight_no
if($session == "no"){
  echo "<script>window.location.replace('?page=session-kasir&makesession')</script>";
  // header("Refresh:0; url=?page=btb");
  // header('location: ?page=sessionbtb&makesession');
}
$price = $data->calprice();
$list = $price->fetch_object();

$adm = $list->admin;
$sg = $list->sg;
$kade = $list->kade;
$pjkp2u = $list->pjkp2u;
$materai = $list->materai;
$as = $list->airport_surcharge;
?>
<div class = "kontener2 px-5 py-4">
  <nav style="--bs-breadcrumb-divider: '》';" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item ps-4" aria-current="page"><i class="fa-solid fa-money-bill-1-wave"></i> Cashier</h3></li>
      <li class="breadcrumb-item active" aria-current="page">Payment</h3></li>
    </ol>
  </nav>
  <div class = "content p-4" style="font-family: roboto;">
    <h6><i class="fa-solid fa-money-bill-1-wave"></i> Payment AWB</h6>
    <div class="row g-0 p-0 m-0">
      <div class = "description col-sm-2 px-3">
        <h5>Payment</h5>
        <p>Chose the data besides to process the payment</p>
      </div>
      <div class = "field-payment col-sm-10 ps-3 table-area">
        <!-- here -->
        <?php
        if(!isset($_GET['agent'])){
          include('views/p_payment/new_payment_agent.php');
        }else{
          include('views/p_payment/pay_view.php');
        }
        ?>  
        <!-- end here -->
      </div>
    </div>
  </div>
</div>