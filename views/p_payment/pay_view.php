<?php
$agent = $data->getAgentByName($_GET['agent']);
if ($agent->agent_npwp != null) {
?>
  <script>
    var agentName = '<?= $agent->agent_name ?>';
    var agentId = '<?= $agent->agent_id ?>';
    var agentNpwp = '<?= $agent->agent_npwp ?>';
  </script>
<?php
} else {
?>
  <script>
    var agentName = '<?= $agent->agent_name ?>';
    var agentId = '<?= $agent->agent_id ?>';
    var agentNpwp = null;
  </script>
<?php
}
?>
<div class="payment-table " style="overflow: auto;">
  <table class="table" id="tPaymentx">
    <thead>
      <tr>
        <th><input type="checkbox" onchange="checkAll(this)" name="chk[]" id="sample"></th>
        <th>#</th>
        <th>Agent</th>
        <th>Shipper</th>
        <th>SMU</th>
        <th>Quantity</th>
        <th>Weight</th>
        <th>Volume</th>
        <th>Nett</th>
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
      if (isset($_POST['b_view'])) {
        $dagent = $_POST['i_agent'];
        $dshipper = $_POST['i_shipper'];
      } else {
        $dagent = "";
        $dshipper = "";
        echo "<script>window.location.replace('?page=payment')</script>";
      }
      if (@$_GET['airline']) {
        $pay = $data->calall_by_airline($dagent, $dshipper, $_GET['airline']);
      } else {
        $pay = $data->calall($dagent, $dshipper);
      }
      $total_smu = 0;
      $total_qty = 0;
      $total_weight = 0;
      $total_volume = 0;
      $total_tnet = 0;
      $total_adm = 0;
      $total_sg = 0;
      $total_kade = 0;
      $total_ap2 = 0;
      $total_asg = 0;
      $total_ppn = 0;
      $total_materai = 0;
      $grand_total = 0;
      $numbering = 1;
      while ($fil = $pay->fetch_object()) : ?>
        <tr>
          <td><input type="checkbox" id="checkAll" name="chkbox[]"></td>
          <td><?= $numbering++; ?></td>
          <td class="text-nowrap"><?php echo $fil->agent_name; ?></td>
          <td class="text-nowrap"><?php echo $fil->shipper_name; ?></td>
          <td class="text-nowrap"><?php echo $fil->smu; ?></td>
          <td><?php echo $tqty = $fil->quantity; ?></td>
          <td><?php echo $tweight = $fil->weight; ?></td>
          <td><?php echo $tvol = $fil->volume; ?></td>

          <td>
            <?php
            if ($tweight >= $tvol && $tweight > 10) {
              $tnet = $tweight;
            } elseif ($tvol > $tweight && $tvol > 10) {
              $tnet = $tvol;
            } else {
              $tnet = 10;
            }
            echo $tnet;
            ?>
          </td>
          <td><?php echo $adm; ?></td>
          <td><?php echo $tsg = $tnet * $sg; ?></td>
          <td><?php echo $tkade = $tnet === 10 ? $kade * 10 : $kade * $tweight; ?></td>
          <td><?php echo $tpjkp2u = $tweight <= 10 ? $pjkp2u * 10 : $pjkp2u * $tweight; ?></td>
          <td><?php echo $tas = $as * $tnet; ?></td>
          <td><?php echo $ppn = (($adm + $tsg + $tkade + $tpjkp2u + $tas) * 11) / 100; ?></td>
          <td><?php echo $tmaterai = (($adm + $tsg + $tkade + $tpjkp2u + $tas + $ppn) < 10000000) ? 0 : 10000; ?></td>
          <td>
            <?php $total = $adm + $tsg + $tkade + $tpjkp2u + $tas + $ppn + $tmaterai;
            echo round($total); ?>

          </td>
        </tr>
        <?php
        $total_qty = $total_qty + $tqty;
        $total_weight = $total_weight + $tweight;
        $total_volume = $total_volume + $tvol;
        $total_tnet = $total_tnet + $tnet;
        $total_adm = $total_adm + $adm;
        $total_sg = $total_sg + $tsg;
        $total_kade = $total_kade + $tkade;
        $total_ap2 = $total_ap2 + $tpjkp2u;
        $total_asg = $total_asg + $tas;
        $total_ppn = $total_ppn + $ppn;
        $total_materai = $total_materai + $tmaterai;
        $grand_total = $grand_total + $total;
        ?>
      <?php endwhile; ?>
      <tr style="font-weight: bold;">
        <td style="font-size: 0.9rem">Total</td>
        <td style="font-size: 0.9rem"></td>
        <td style="font-size: 0.9rem"></td>
        <td style="font-size: 0.9rem"></td>
        <td style="font-size: 0.9rem"></td>
        <td style="font-size: 0.9rem"><?php echo number_format($total_qty); ?></td>
        <td style="font-size: 0.9rem"><?php echo number_format($total_weight); ?></td>
        <td style="font-size: 0.9rem"><?php echo number_format($total_volume); ?></td>
        <td style="font-size: 0.9rem"><?php echo number_format($total_tnet); ?></td>
        <td style="font-size: 0.9rem"><?php echo number_format($total_adm); ?></td>
        <td style="font-size: 0.9rem"><?php echo number_format($total_sg); ?></td>
        <td style="font-size: 0.9rem"><?php echo number_format($total_kade); ?></td>
        <td style="font-size: 0.9rem"><?php echo number_format($total_ap2); ?></td>
        <td style="font-size: 0.9rem"><?php echo number_format($total_asg); ?></td>
        <td style="font-size: 0.9rem"><?php echo number_format($total_ppn); ?></td>
        <td style="font-size: 0.9rem"><?php echo number_format($total_materai); ?></td>
        <td style="font-size: 0.9rem"><?php echo number_format($grand_total); ?></td>
      </tr>
    </tbody>
  </table>
</div>
<div class="d-flex justify-content-end mt-4">
  <button type="button" class="btn btn-ungu" data-bs-toggle="modal" data-bs-target="#modalProses" onclick="inputdata()">View Total</button>
</div>




<!-- Modal volume-->
<div class="modal fade" id="modalProses" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog thedialog">
    <div class="modal-content themodal">
      <div class="modal-header theheader">
        <h5 class="modal-title" id="exampleModalLabel">Create Invoice</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="views/print-invoice.php" method="post" target="_blank">
          <input type="text" id="input_npwp" name="npwp" hidden>
          <div class="row g-0 p-0">
            <div class="col-4">
              <label for="" class="form-label form-label-sm">Total SMU</label><br>
              <label for="" class="form-label form-label-sm">Total Adm</label><br>
              <label for="" class="form-label form-label-sm">Total Sewa Gudang</label><br>
              <label for="" class="form-label form-label-sm">Total Jasa Kade</label><br>
              <label for="" class="form-label form-label-sm">Total PJKP2U</label><br>
              <label for="" class="form-label form-label-sm">Total Airport Surcharge</label><br>
              <label for="" class="form-label form-label-sm">Total PPN</label><br>
              <label for="" class="form-label form-label-sm">Total Materai</label><br>
              <label for="" class="form-label form-label-sm">Grand Total</label>
            </div>
            <div class="col-4 text-center">
              <br>
              <?php echo $adm; ?><br>
              <?php echo $sg; ?><br>
              <?php echo $kade; ?><br>
              <?php echo $pjkp2u; ?><br>
              <?php echo $as; ?><br>
              11%<br>
              <?php echo $materai; ?><br>
            </div>
            <div class="col-4 text-end">
              <input class="" type="text" value="<?php echo $dagent; ?>" name="d_agent" hidden>
              <input class="" type="text" value="<?php echo $dshipper; ?>" name="d_shipper" hidden>
              <input class="" type="text" name="d_smu" id="dSmu" hidden>
              <input class="fw-bold text-end form-control form-control-sm form-bayar mb-2 px-3" type="text" id="tSmu" disabled value="50000">
              <input class="fw-bold text-end form-control form-control-sm form-bayar mb-2 px-3" type="text" id="tAdm" disabled value="50000">
              <input class="fw-bold text-end form-control form-control-sm form-bayar mb-2 px-3" type="text" id="tSg" disabled value="50000">
              <input class="fw-bold text-end form-control form-control-sm form-bayar mb-2 px-3" type="text" id="tKade" disabled value="50000">
              <input class="fw-bold text-end form-control form-control-sm form-bayar mb-2 px-3" type="text" id="tAp2" disabled value="50000">
              <input class="fw-bold text-end form-control form-control-sm form-bayar mb-2 px-3" type="text" id="tAs" disabled value="50000">
              <input class="fw-bold text-end form-control form-control-sm form-bayar mb-2 px-3" type="text" id="tPpn" disabled value="50000">
              <input class="fw-bold text-end form-control form-control-sm form-bayar mb-2 px-3" type="text" id="tMaterai" disabled value="50000">
              <input class="fw-bold text-end form-control form-control-sm form-bayar mb-2 px-3" type="text" id="tTotal" disabled value="50000">
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" name="print_invoice" id="printInvoice" onclick="createInvoice()">Print</button>
        <button type="submit" id="post_button" name="print_invoice" hidden>post</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalNpwp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog thedialog modal-md">
    <div class="modal-content themodal">
      <div class="modal-header theheader">
        <h5 class="modal-title" id="exampleModalLabel">Add New NPWP</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-white" style="height: 23em !important">
        <!-- <div class="alert alert-primary " role="alert">
          <div class="d-flex align-items-center">
            <i class="fas fa-info-circle me-2 fs-1"></i>
            <div>
              <strong>The agent doesn't have NPWP</strong> <br>
              Please enter the NPWP Agent number if it’s needed on the invoice. Otherwise, you may simply click <strong>"Print Anyway"</strong> to proceed without the NPWP.
            </div>
          </div>
          <div class="d-flex justify-content-end">
            <button class="btn btn-success" onclick="printAnyway()">Print Anyway</button>
          </div>
        </div> -->

        <div class="form-group">
          <label>Agent</label>
          <input type="text" class="form-control form-control-sm" id="agent_name" readonly>
        </div>
        <div class="form-group">
          <label>NPWP</label>
          <input type="text" class="form-control form-control-sm is-invalid" id="agent_npwp" name="agent_npwp">
          <div class="invalid-feedback">The NPWP number must contain between 15 and 16 digits.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" name="print_invoice" disabled id="save_npwp_btn" onclick="saveNpwp()">Save & Print</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="customNpwpModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content themodal">
      <div class="modal-header theheader">
        <h5 class="modal-title" id="exampleModalLabel">Personal NPWP</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-white" style="height: 23em !important">
        <div class="form-group">
          <label>NPWP</label>
          <input type="text" class="form-control form-control-sm is-invalid npwp-input" name="custom_npwp" id="custom_npwp">
          <div class="invalid-feedback">The NPWP number must contain between 15 and 16 digits.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" name="print_invoice" disabled id="save_npwp_custom_btn" onclick="saveCustomNpwp()">Save & Print</button>
      </div>
    </div>
  </div>
</div>





<script src="assets/jquery/jquery-3.6.0.js"></script>
<script>
  function checkAll(e) {
    var checkboxes = $('input');
    if (e.checked) {
      for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
          checkboxes[i].checked = true;
        }
      }
    } else {
      for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].type == 'checkbox') {
          checkboxes[i].checked = false;
        }
      }
    }
  };

  function inputdata() {
    if (agentNpwp) {
      $("#input_npwp").val(agentNpwp)
    }
    var baris = $("tbody tr");
    var xx = $("tbody tr").length;
    var smu = 0;
    var weight = 0;
    var volume = 0;
    var adm = 0;
    var sg = 0;
    var kade = 0;
    var ap2 = 0;
    var as = 0;
    var ppn = 0;
    var materai = 0;
    var total = 0;
    var data = xx - 1;
    var dsmu = "";
    //console.log(data);

    for (var i = 0; i < data; i++) {
      var target = baris.children();
      var target2 = target.children();
      var target3 = target2.children();
      if (target2[i].checked) {
        smu = smu + 1;
        dsmu = dsmu + $(baris[i].children[4]).text() + ",";
        adm = adm + parseInt($(baris[i].children[9]).text());
        sg = sg + parseInt($(baris[i].children[10]).text());
        kade = kade + parseInt($(baris[i].children[11]).text());
        ap2 = ap2 + parseInt($(baris[i].children[12]).text());
        as = as + parseInt($(baris[i].children[13]).text());
        ppn = ppn + parseInt($(baris[i].children[14]).text());
        materai = materai + parseInt($(baris[i].children[15]).text());
        total = total + parseInt($(baris[i].children[16]).text());
      }
      // var yy = target2[i];
      // console.log(yy);
    }
    $("#dSmu").val(dsmu);
    $("#tSmu").val(smu);
    $("#tAdm").val(adm);
    $("#tSg").val(sg);
    $("#tKade").val(kade);
    $("#tAp2").val(ap2);
    $("#tAs").val(as);
    $("#tPpn").val(ppn);
    $("#tMaterai").val(materai);
    $("#tTotal").val(total);
  }
  //is(':checked')
</script>
<script>
  $('#agent_npwp').on('input', function() {
    this.value = this.value.replace(/[^0-9.,-]/g, '');

    var numOnly = this.value.replace(/[^0-9]/g, '').length;
    console.log(numOnly);
    if (numOnly == 15 || numOnly == 16) {
      $("#save_npwp_btn").prop('disabled', false);
    } else {
      $("#save_npwp_btn").prop('disabled', true);
    }
  });

  $('.npwp-input').on('input', function() {
    this.value = this.value.replace(/[^0-9.,-]/g, '');

    var numOnly = this.value.replace(/[^0-9]/g, '').length;
    console.log(numOnly);
    if (numOnly == 15 || numOnly == 16) {
      $("#save_npwp_custom_btn").prop('disabled', false);
    } else {
      $("#save_npwp_custom_btn").prop('disabled', true);
    }
  });

  const createInvoice = () => {
    Swal.fire({
      title: 'Select NPWP Type',
      text: 'Please select NPWP type below, select personal if the the customer is individual or the regular agent need to use another NPWP number.',
      icon: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Agent',
      cancelButtonText: 'Cancel',
      denyButtonColor: '#fbff17',
      denyButtonText: 'Personal',
      showDenyButton: true,
    }).then(result => {
      if (result.isConfirmed) {
        if (agentNpwp) {
          $("#input_npwp").val(agentNpwp);
          $("#post_button").trigger('click');
          window.location.href = '?page=payment'
        } else {
          $("#agent_name").val(agentName)
          $("#modalNpwp").modal('show');
        }
      } else if (result.isDenied) {
        $("#customNpwpModal").modal('show');
      }
    })
  }

  const saveNpwp = (el) => {
    let npwp = $("#agent_npwp").val();
    if (npwp != '') {
      $.ajax({
        type: "POST",
        url: 'ajax/agent_ajax.php',
        data: {
          'save_npwp': true,
          'npwp': npwp,
          'agent_id': agentId
        },
      }).then(result => {
        if (result.status == 'success') {
          $("#modalNpwp").modal('hide');
          $("#input_npwp").val(result.npwp);
          $("#post_button").trigger('click');
          window.location.href = '?page=payment'
        }
      })
    } else {
      alert('NPWP number cannot be empty');
    }
  }

  const printAnyway = () => {
    $("#post_button").trigger('click');
  }

  const saveCustomNpwp = (el) => {
    let npwp = $("#custom_npwp").val();
    $("#customNpwpModal").modal('hide');
    $("#input_npwp").val(npwp);
    $("#post_button").trigger('click');
    window.location.href = '?page=payment'
  }
</script>