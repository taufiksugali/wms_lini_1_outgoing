<div class="payment-table p-3">
  <div class="row mb-3">
      <div class="col-md-3 form-group">
        <label>
        Airline
      </label>
      <select class="form-control select2" id="airline" data-placeholder="Select Airline">
        <option></option>
        <option value="all">All Airline</option>
        <?php 
        while ($row = $airlines->fetch_object()){
          ?>
          <option 
          value="<?= $row->airline_id; ?>"
          <?php 
          if(@$_GET['airline']){
            if($row->airline_id == $_GET['airline']){
              echo "selected";
            }
          }
          ?>
          ><?= $row->airline_name ;?></option>
          <?php
        }
        ?>
      </select>
    </div>
    <div class="col-md-2 d-flex justify-content-end align-items-end">
      <button class="btn btn-sm btn-primary px-3" onclick="getAirline()">Find!</button>
    </div>
  </div>
  <table class="table text-nowrap" id="tPayment">
    <thead>
      <tr class="text-center">
        <th>sa</th>
        <th>#</th>
        <th>Agent</th>
        <th>Shipper</th>
        <th>SMU</th>
        <th>Quantity</th>
        <th>Weight</th>
        <th>Volume</th>
        <th>Adm</th>
        <th>SG</th>
        <th>Kade</th>
        <th>Ap2</th>
        <th>Airport_Surcharge</th>
        <th>Ppn</th>
        <th>Materai</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      if(@$_GET['airline']){
        $pay = $data->get_distinct_payment_by_airline($_GET['airline']);
      }else{
        $pay = $data->get_distinct_payment();
      }
      $total_smu = 0;
      $total_qty = 0;
      $total_weight = 0;
      $total_volume = 0;
      $total_adm = 0;
      $total_sg = 0;
      $total_kade = 0;
      $total_ap2 = 0;
      $total_asg = 0;
      $total_ppn = 0;
      $total_materai = 0;
      $grand_total = 0;
      while ($info = $pay->fetch_object()) {
        $agent= $info->agent_name;
        $shipper = $info->shipper_name;
        // $r_agent = $info->agent_name;
        // $r_shipper = $next->shipper_name;
                  // $call = $data->calall($r_agent, $r_shipper);
                  // $r_call = $call->fetch_object();
                  // var_dump($r_call);
        // $z = $data->countsmu($r_agent, $r_shipper);
        // $y = $data->sumqty($r_agent, $r_shipper);
        // $x = $data->sumweight($r_agent, $r_shipper);
        // $w = $data->sumvol($r_agent, $r_shipper);
        ?>
        <tr>
          <td>
            <!-- <a href="?page=payment&agent=<?php //echo $info->agent_name; ?>&shipper=<?php //echo $next->shipper_name; ?>"> -->
              <?php 
              if(@$_GET['airline']){
                $url = '?page=payment&agent='.$info->agent_name.'&airline='.$_GET['airline'];
              }else{
                $url = '?page=payment&agent='.$info->agent_name;
              }
              ?>
              <form action="<?php echo $url; ?>" method="post">
                <input type="text" name="i_agent" value="<?php echo $info->agent_name; ?>" hidden>
                <input type="text" name="i_shipper" value="<?php echo $info->shipper_name; ?>" hidden>
                <button type="submit" class="btn btn-sm btn-outline-primary" name="b_view">view</button>
              </form>
              <!-- </a> -->
            </td>
            <td>1</td>
            <td><?php echo $info->agent_name; ?></td>
            <td><?php echo $info->shipper_name; ?></td>
            <td><?php echo $tsmu = $info->smu; ?></td>
            <td><?php echo number_format($tqty = $info->quantity); ?></td>
            <td><?php echo number_format($tweight = $info->weight); ?></td>
            <td><?php echo number_format($tvol = $info->volume); ?></td>
            <td><?php echo $tadm = $adm * $tsmu; ?></td>
            <td>
              <?php
              if($tvol <= $tweight){
                if($tweight < 10){
                  $h_sg = 10;
                }else{
                  $h_sg = $tweight;
                }
              }else{
                $h_sg = $tvol;
              }
              echo $tsg = $h_sg * $sg;
              ?>
            </td>
            <td><?php echo number_format($tkade = $kade * $tweight); ?></td>
            <td><?php echo number_format($tpjkp2u = $pjkp2u * $tweight); ?></td>
            <td><?php echo number_format($tas = $as * $h_sg); ?></td>
            <td><?php echo number_format($ppn = (($tadm + $tsg +$tkade + $tpjkp2u + $tas) * 11) / 100); ?></td>
            <td><?php echo number_format($tmaterai = (($tadm + $tsg +$tkade + $tpjkp2u + $tas + $ppn) < 10000000) ? 0 : 10000); ?></td>
            <td><?php echo number_format($total = $tadm + $tsg +$tkade + $tpjkp2u + $tas + $ppn + $tmaterai); ?></td>
          </tr>
          <?php 
          $total_smu = $total_smu+$tsmu;
          $total_qty = $total_qty+$tqty;
          $total_weight = $total_weight+$tweight;
          $total_volume = $total_volume+$tvol;
          $total_adm = $total_adm+$tadm;
          $total_sg = $total_sg+$tsg;
          $total_kade = $total_kade+$tkade;
          $total_ap2 = $total_ap2+$tpjkp2u;
          $total_asg = $total_asg+$tas;
          $total_ppn = $total_ppn+$ppn;
          $total_materai = $total_materai+$tmaterai;
          $grand_total = $grand_total+$total;
        };
        ?>
        <tr >
          <td style="font-size: 0.9rem;"><b>Total</b></td>
          <td></td>
          <td></td>
          <td></td>
          <td><?php echo number_format($total_smu); ?></td>
          <td><?php echo number_format($total_qty); ?></td>
          <td><?php echo number_format($total_weight); ?></td>
          <td><?php echo number_format($total_volume); ?></td>
          <td><?php echo number_format($total_adm); ?></td>
          <td><?php echo number_format($total_sg); ?></td>
          <td><?php echo number_format($total_kade); ?></td>
          <td><?php echo number_format($total_ap2); ?></td>
          <td><?php echo number_format($total_asg); ?></td>
          <td><?php echo number_format($total_ppn); ?></td>
          <td><?php echo number_format($total_materai); ?></td>
          <td><?php echo number_format($grand_total); ?></td>
        </tr>
      </tbody>
    </table>
  </div>

