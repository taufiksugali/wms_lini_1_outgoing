<?php
session_start();
$_SESSION['print']= "off";
if(!isset($_SESSION['name'])){
  header('location: views/login.php');
  exit();
}
date_default_timezone_set("Asia/Bangkok");
require_once('config/config.php');
require_once('models/database.php');
include('models/m_user.php');
$connection = new Database($host, $user, $pass, $database);
$data = new User($connection);
$get = $data->panggil($_SESSION['name']);
$data_user = $get->fetch_object();

?>

<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <!-- custom css -->
  <link href="assets/css/index.css" rel="stylesheet" crossorigin="anonymous">
  <!-- fontawesome -->
  <link href="assets/fontawesome/css/all.css" rel="stylesheet" crossorigin="anonymous">
  <!-- datatable -->
  <link href="assets/dataTables/datatables.css" rel="stylesheet" crossorigin="anonymous">
  <!-- timepicker css -->
  <link rel="stylesheet" type="text/css" href="assets/datetimepicker-master/build/jquery.datetimepicker.min.css">
  <link href="assets/select2/select2.min.css" rel="stylesheet" />

  <!-- jquery -->
  <script src="assets/jquery/jquery-3.6.0.js"></script>
  <!-- aos -->
  <script src="assets/aos/dist/aos.js"></script>
  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="assets/bootstrap/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  
  <!-- timepicker js -->
  <script src="assets/datetimepicker-master/build/jquery.datetimepicker.full.js"></script>


  <title>Lini1 Outgoing</title>
</head>
<body>
  <div class="kontener">
    <div class="loading position-fixed" id="loading">
      <div class = "d-flex justify-content-center align-items-center" style=" height: 100%; width: 100%;">
        <img src="assets/image/ajax-loader.gif" alt="">
        
      </div>
      
    </div>
    <div class="header position-relative">
      <div class = "title position-absolute top-0 py-2 px-4 d-flex justify-content-between">
        <img src="assets/image/poslog.png" alt="">
        <div class="useractive position-relative">
          <div class = "position-absolute end-0">
            <ul>
              <li class="nav-item dropdown" style="list-style: none;">
                <a class="nav-link dropdown-toggle pt-4 pe-0" href="" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa-solid fa-user-large"></i> 
                  <?php echo ucwords($_SESSION['name']); ?>
                </a>
                <ul class="dropdown-menu text-center p-0" aria-labelledby="navbarDropdown">
                  <li><a href="models/m_logout.php" style="text-decoration: none" onclick="return confirm('Anda yakin untuk Log Out ?')">Log out &emsp; &emsp; <i class="fa-solid fa-right-from-bracket"></i></a></li>

                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- menu here -->
    <?php 
    if($data_user->hak_akses == "acceptance" || $data_user->hak_akses == "pic"){
      include('views/partials/menu_acceptance.php');
    }elseif($data_user->hak_akses == "kasir" || $data_user->hak_akses == "supervisor"){
      include('views/partials/menu_kasir.php');
    }
    ?>
    <!-- batas menu -->

    <?php 

    if(@$_GET['page'] == 'home' || @$_GET['page'] == ''){
      include "views/home.php";
    } elseif(@$_GET['page'] == 'btb'){
      include "views/btb.php";
    }
    elseif(@$_GET['page'] == 'sessionbtb'){
      include "views/session_btb.php";
    }
    elseif(@$_GET['page'] == 'addflight'){
      include "views/add_flight_number.php";
    }
    elseif(@$_GET['page'] == 'session-report'){
      include "views/session_report.php";
    }
    elseif(@$_GET['page'] == 'session-kasir'){
      include "views/session_kasir.php";
    }
    elseif(@$_GET['page'] == 'payment'){
      include "views/payment.php";
    }
    elseif(@$_GET['page'] == 'finance-report'){
      include "views/session_report_kasir.php";
    }
    elseif(@$_GET['page'] == 'create_manifest'){
      include "views/dls_view.php";
    }
    elseif(@$_GET['page'] == 'view_manifest'){
      include "views/view_manifest.php";
    }
    elseif(@$_GET['page'] == 'report_all_tonnage'){
      include "views/report_all_bydate.php";
    }
    elseif(@$_GET['page'] == 'cargo_details'){
      include "views/details.php";
    }
    elseif(@$_GET['page'] == 'calendar_tonnage'){
      include "views/calendar_tonnage.php";
    }
    elseif(@$_GET['page'] == 'finance_manifest'){
      include "views/finance_manifest.php";
    }
    elseif(@$_GET['page'] == 'dls'){
      include "views/dls_view.php";
    }elseif(@$_GET['page'] == 'add_airline'){
      include "views/add_airline.php";
    }elseif(@$_GET['page'] == 'add_smu_code'){
      include "views/smu_code.php";
    }elseif(@$_GET['page'] == 'update_time'){
      include "views/update_time.php";
    }
    ?>

    <div class ="position-absolute bottom-0 start-0 footer px-4 d-flex justify-content-end">
      <p class="mb-0 me-2"><b>poweredby</b></p>
      <img src="assets/image/lini1.png" alt="" style="height:40px;">
    </div>
  </div>

  
  
  








  <!-- datatables -->
  <script src="assets/dataTables/datatables.js"></script>
  <!-- js aos init -->
  <script>
    AOS.init();
  </script>

  <!-- js menu handle-->
  <script>
    $(document).ready(function(){
      $(window).resize(function() {
        if($(window).width() < 650){
          $(".kontener2").removeClass('px-5');
          $(".kontener2").addClass('px-1');
        }else{
          $(".kontener2").addClass('px-5');
          $(".kontener2").removeClass('px-1');
        }
      });
      if($(window).width() < 650){
        $(".kontener2").removeClass('px-5');
        $(".kontener2").addClass('px-1');
      }else{
        $(".kontener2").addClass('px-5');
        $(".kontener2").removeClass('px-1');
      }
      
      $(".menu1, .menu2, .menu3").hover(function(){
        $(this).css("background-color", "rgba(252, 252, 252, 0.3)");
        $(this).children(".span").css("width", "100%");
        $(this).children("a").css("text-shadow", "0 0 20px white");
        $(this).children("ul").css("text-shadow", "inset 0 0 20px white")
      }, function(){
        $(this).css("background-color", "transparent");
        $(this).children(".span").css("width", "0%");
        $(this).children("a").css("text-shadow", "none");
        $(this).children("ul").css("text-shadow", "none")
      });
    });
  </script>

  <!-- js hide alert -->
  <script>
    $(document).ready(function(){
      setTimeout(function(){
        $(".alert-active").css("display","none");
      }, 3500);
    });
  </script>
  <!-- calldatatablefunction -->
  <script>
    $(document).ready(function(){
      $('#tableFlight').DataTable();
      $('#tSessionReport').DataTable();
      $('#tPayment').DataTable();
      $('#allReportOutgoing').DataTable();
      $('#allReportOutgoingVoid').DataTable();
    });

  </script>

  <!-- submenu handle -->
  <script>
    $(function(){
      $(".dropdown-menu > li > a.trigger").on("click",function(e){
        var current=$(this).next();
        var grandparent=$(this).parent().parent();
        if($(this).hasClass('left-caret')||$(this).hasClass('right-caret')){
          $(this).toggleClass('right-caret left-caret');
          grandparent.find('.left-caret').not(this).toggleClass('right-caret left-caret');
          grandparent.find(".sub-menu:visible").not(current).hide();
          current.toggle();
          e.stopPropagation();
        }
      });
      $(".dropdown-menu > li > a:not(.trigger)").on("click",function(){
        var root=$(this).closest('.dropdown');
        root.find('.left-caret').toggleClass('right-caret left-caret');
        root.find('.sub-menu:visible').hide();
      });
    });
  </script>

  

  <script>
    $(document).ready(function(){
      $("#loading").css("display","none");

    })
  </script>

  <?php
  if(@$_GET['page'] == 'finance-report'){
    if (isset($_POST['search']) || isset($_GET['session'])) {
      if(isset($_POST['search'])){
      }elseif(isset($_GET['session'])){ ?>
        <script>
          $(document).ready(function(){
            var smu = '<?php echo $_GET["smu"] ?>';
            $("#tSessionReport_filter label input").val(smu);
            console.log(smu);         

          })
        </script>
      <?php }
    }
  }
  if(@$_GET['page'] == 'session-report'){
    if (isset($_POST['search']) || isset($_GET['session'])) {
      if(isset($_POST['search'])){
      }elseif(isset($_GET['session'])){ ?>
        <script>
          $(document).ready(function(){
            var smu = '<?php echo $_GET["smu"] ?>';
            $("#tSessionReport_filter label input").val(smu);
            console.log(smu);         

          })
        </script>
      <?php }
    }
  }
  ?>

</body>
</html>