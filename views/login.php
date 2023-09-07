<?php 
session_start();
error_reporting(0);
require_once('../config/config.php');
require_once('../models/database.php');
$connection = new Database($host, $user, $pass, $database);

include('../models/m_login.php');


if(isset($_SESSION['name'])){
  header('location: ../');
}else{
  
  if (isset($_POST['loginBtn'])) {
    $data = new Login($connection);
    $username = $_POST['username'];
    $password = $_POST['password'];

    $datauser = $data->panggil($username,$password);
    $hasil = $datauser->fetch_object()->jumlah;

    if($hasil == 1){
      $datalogin = $data->panggilall($username);
      $result = $datalogin->fetch_object();

      $_SESSION['name']=$result->username;
      $_SESSION['hak_akses']=$result->hak_akses;
      header('location: ../');
    }else{
      $tes1 = "false";
      $alert1 = 'block';
    }

    $alert = "none";
    
  }
  $alert2 = "none";
}
$alert = (@$alert1) ? 'block' : $alert2;
?>

<!doctype html>
  <html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- icon -->
    <link rel=”icon” href="../assets/image/lini1.png" type="png" sizes="20x16">
    <!-- Bootstrap CSS -->
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- custom css -->
    <link href="../assets/css/login.css" rel="stylesheet" crossorigin="anonymous">
    <!-- fontawesome -->
    <link href="../assets/fontawesome/css/fontawesome.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- aos -->
    <link href="../assets/aos/dist/aos.css" rel="stylesheet" crossorigin="anonymous">

    <title>system login</title>
  </head>
  <body>

    <div class="contener " >
      <div class = "position-relative" >
        <div class = "d-alert position-absolute start-50 translate-middle" style="display: <?php echo $alert; ?>" data-aos="fade-right" data-aos-duration="2000">
          <div class="alert alert-dismissible fade show" role="alert">
            <strong> <i class="fa-solid fa-octagon-xmark"></i>Incorrect username/password !</strong> You should check in on some of fields bellow.
            <button type="button" class="alert-close btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        </div>
      </div>
      <div class = "row g-0 p-0 m-0">
        <div class = "col-md-6">

        </div>
        <div class = "right-side col-md-6 p-3">
          <div class="form-login d-flex align-items-center justify-content-center" >
            <form action="" method="post">
              <div class="form">
                <label for="userName" class="form-label">Username</label>
                <input type="text" class="input form-control" id="userName" placeholder="Username" name="username" autocomplete="off" autofocus="Username">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="input form-control" id="password" placeholder="password" name="password"><br>

                <button type="submit" class="btn btn-dark bg-gradient" id="loginBtn" name="loginBtn">Login
                  <div class="spinner-border spinner-border-sm" role="status" id="spinner" style="display: none">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    





    <!-- jquery -->
    <script src="../assets/jquery/jquery-3.6.0.js" crossorigin="anonymous"></script>

    <!-- aos -->
     <script src="../assets/aos/dist/aos.js" crossorigin="anonymous"></script>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
  -->
  <script>

    $(document).ready(function(){
      // $('#spinner').css("display","none");
      $("#loginBtn").click(function(){
        $('#spinner').css("display","inline-block");
      })
      

    });
    AOS.init();
  </script>
</body>
</html>