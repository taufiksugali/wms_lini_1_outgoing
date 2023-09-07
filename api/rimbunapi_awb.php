<?php
if(isset($_GET['data'])){
   $url = "https://www.rimbun.avter.co.id/api/check/awb/".$_GET['data'];

   $curl = curl_init($url);
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

   $headers = array(
      "Accept: application/json",
   );
   curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
   curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

   $resp = curl_exec($curl);
   curl_close($curl);
   echo $resp;
}


?>