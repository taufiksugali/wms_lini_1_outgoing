<?php
include('models/m_btb.php');
include('models/m_smu_code.php');
$m_code = new Smucode($connection);
$data = new Btb($connection);
// cek session
$btb = $data->session();
$session = $btb->fetch_object()->running;
// ambil data flight untuk list flight_no

if ($session == "no") {
    echo "<script>window.location.replace('?page=sessionbtb&makesession')</script>";
    // header("Refresh:0; url=?page=btb");
    // header('location: ?page=sessionbtb&makesession');
}
if (isset($_GET['error'])) {
    $error = $_GET['error'];
} else {
    $error = "";
}
$duplicate = ($error == "duplicate") ? "block" : "none";
?>
<div class="position-relative alert-active">
    <div class="d-alert position-absolute start-50 translate-middle" style="display: <?php echo $duplicate; ?>" data-aos="fade-right" data-aos-duration="2000">
        <div class="alert alert-dismissible  alert-danger fade show" role="alert">
            <strong>Duplicate SMU !</strong> The data you entered has been entered previously.
            <button type="button" class="alert-close btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
<?php
if (@$_SESSION['error']) {
?>
    <div class="position-relative alert-active">
        <div class="d-alert position-absolute start-50 translate-middle" style="display: block" data-aos="fade-right" data-aos-duration="2000">
            <div class="alert alert-dismissible  alert-danger fade show" role="alert">
                <strong> Error !</strong> <?= $_SESSION['error']['message']; ?>.
                <button type="button" class="alert-close btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
<?php
    unset($_SESSION['error']);
}

if (isset($_SESSION['result'])) {
    echo '<div class = "position-relative alert-active" ><div class = "d-alert position-absolute start-50 translate-middle"data-aos="fade-right" data-aos-duration="2000"><div class="alert alert-dismissible  alert-' . $_SESSION['result']['color'] . ' fade show" role="alert"><strong>' . $_SESSION['result']['pesan'] . '</strong> status: ' . $_SESSION['result']['aksi'] . '.<button type="button" class="alert-close btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div></div></div>';
}
unset($_SESSION['result']);
$allcode = $m_code->all_code();
$allclass = $m_code->get_cargo_classes();
$allagent = $data->get_all_agent();
$allra = $data->get_all_ra();
?>

<link href="assets/tom-select/tom-select.default.min.css" rel="stylesheet" crossorigin="anonymous">
<style>
    .ts-control>input {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        z-index: 20;

        box-shadow: none !important;
    }

    #selectFlight_input {
        padding-left: 0px !important;
        padding-right: 0px !important;
        width: auto !important;

    }

    #selectFlight_input:focus {
        box-shadow: none !important;
    }

    #selectFlight_input:active {
        box-shadow: none !important;
    }

    #selectFlight_input:hover {
        box-shadow: none !important;
    }

    #selectCType_input {
        padding-left: 0px !important;
        padding-right: 0px !important;
        width: auto !important;

    }

    #selectCType_input:focus {
        box-shadow: none !important;
    }

    #selectCType_input:active {
        box-shadow: none !important;
    }

    #selectCType_input:hover {
        box-shadow: none !important;
    }

    #selectAgent_input {
        padding-left: 0px !important;
        padding-right: 0px !important;
        width: auto !important;

    }

    #selectAgent_input:focus {
        box-shadow: none !important;
    }

    #selectAgent_input:active {
        box-shadow: none !important;
    }

    #selectAgent_input:hover {
        box-shadow: none !important;
    }

    #selectCode_input {
        padding-left: 0px !important;
        padding-right: 0px !important;
        width: auto !important;

    }

    #selectCode_input:focus {
        box-shadow: none !important;
    }

    #selectCode_input:active {
        box-shadow: none !important;
    }

    #selectCode_input:hover {
        box-shadow: none !important;
    }


    #selectRA_input {
        padding-left: 0px !important;
        padding-right: 0px !important;
        width: auto !important;

    }

    #selectRA_input:focus {
        box-shadow: none !important;
    }

    #selectRA_input:active {
        box-shadow: none !important;
    }

    #selectRA_input:hover {
        box-shadow: none !important;
    }

    .ts-control>.input {
        z-index: 10;
    }

    .non-border {
        border: none !important;
    }
</style>

<div class="row position-relative" style="width: 100%;">
    <div class="col-sm-6">
    </div>
    <div class="col-sm-6 d-flex justify-content-end">
        <div class="position-fixed" id="toastArea" style="width: 50%; top: 5rem">

        </div>
    </div>
</div>

<div class="kontener2 px-5 py-4">

    <nav style="--bs-breadcrumb-divider: '》';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item ps-4" aria-current="page"><i class="fa-solid fa-database"></i> Acceptance</h3>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Input AWB</h3>
            </li>
        </ol>
    </nav>

    <div class="content p-4" style="font-family: roboto;">

        <h6><i class="fa-solid fa-database"></i> Airway Bill Process</h6>
        <form action="models/p_btb.php" method="post" id="mainForm">
            <div class="row g-0 p-0 m-0">
                <div class="description col-sm-2 px-3">
                    <h5>Process</h5>
                    <p>Type / select to search or find data</p>
                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#modalAddAgent"> <i class="fas fa-book"></i> Add Agent</button>
                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalAddRA"> <i class="fas fa-book"></i> Add Regulated Agent</button>
                    </div>
                </div>
                <div class="main-form col-sm-5 px-3 ">
                    <div class="mb-3">
                        <label for="awb" class="form-label">Airway Bill<span class="text-danger">*</span></label>
                        <div class="d-flex">
                            <select class="default-tom-select d-flex" placeholder="Select" name="airlist" style="width: 35%;" id="awalan" onchange="getFlight($(this))" required data-constname="selectCode">
                                <option></option>
                                <?php
                                while ($code = $allcode->fetch_object()) {
                                ?>
                                    <option value="<?= $code->code ?>"><?= $code->code ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <input type="text" class="form-control form-control-sm ms-2" id="awb" placeholder="AWB Number" name="awb" required maxlength="8" style="width: 63%">
                            <div class="position-relative">
                                <div class="spinner-border spinner-border-sm position-absolute pt-1" role="status" style="color: #4a0078; left: -1.5em;top : 0.5em;" id="spinnerAwb">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>

                        </div>
                        <label for="noflight" class="form-label mt-3">Flight Number<span class="text-danger">*</span></label>
                        <div>
                            <select class="default-tom-select" id="noflight" data-optionsdata="" placeholder="Select Flight Number" name="noflight" required style="width: 100%;" data-constname="selectFlight">
                                <option></option>
                            </select>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label for="comodity" class="form-label mt-3">Cargo Type<span class="text-danger">*</span></label> <br>
                                <div>
                                    <select class="default-tom-select" data-placeholder="Select Cargo Type" required data-sampledata='' id="cargoType" name="shipment_type" data-constname="selectCType">
                                        <option></option>
                                        <?php
                                        while ($class = $allclass->fetch_object()) {
                                        ?>
                                            <option value="<?= $class->class_code ?>"><?= $class->class_code . ' [' . $class->type . ']' ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="comodity" class="form-label mt-3">Comodity<span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="comodity" placeholder="GAB/PKT/CONSOLE" name="comodity" required>
                            </div>
                        </div>

                        <label for="agent" class="form-label mt-3">Agent Name<span class="text-danger">*</span></label>
                        <select name="agent" id="agent" class="default-tom-select" data-optionsdata="" placeholder="Select Agent" required data-constname="selectAgent">
                            <option></option>
                            <?php
                            while ($c_agent = $allagent->fetch_object()) {
                            ?>
                                <option value="<?= $c_agent->agent_name ?>"><?= $c_agent->agent_name ?></option>
                            <?php
                            }
                            ?>
                        </select>

                        <label for="shipper" class="form-label mt-3">Shipper Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="shipper" placeholder="Shipper Name" name="shipper" required onkeyup="$(this).val($(this).val().toUpperCase())">

                        <label for="shipper_address" class="form-label mt-3">Shipper Address<span class="text-danger">*</span></label>
                        <textarea name="shipper_address" class="form-control form-control-sm" rows="3" id="shipper_address" required></textarea>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="mb-3">
                        <label for="pic" class="form-label">Person In Charge<span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm mb-3" id="pic" placeholder="PIC Name" name="pic" required>
                        <label for="qty" class="form-label">Quantity<span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-sm" placeholder="Quantity" aria-label="Quantity" aria-describedby="basic-addon2" id="qty" placeholder="quantity-xx*" name="qty" required>
                            <span class="input-group-text input-group-sm" id="basic-addon2">Pcs</span>
                        </div>
                        <!-- <input type="text" class="form-control form-control-sm" id="qty" placeholder="quantity-xx*" name="qty" required> -->
                        <label for="weight" class="form-label mt-3">Weight<span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-sm" placeholder="Wight" aria-label="Wight" aria-describedby="basic-addon2" id="weight" placeholder="weight-kg*" name="weight" required>
                            <span class="input-group-text input-group-sm" id="basic-addon2">Kg</span>
                        </div>
                        <!-- <input type="text" class="form-control form-control-sm" id="weight" placeholder="weight-kg*" name="weight" required> -->
                        <div class="row g-0 p-0 m-0">
                            <div class="col-md-9">
                                <label for="volume" class="form-label mt-3">Volume</label>
                                <input type="text" class="form-control form-control-sm" id="volume" placeholder="volume-xx-kg*" name="volume" hidden>
                                <!-- <input type="text" class="form-control form-control-sm" id="volumeX" placeholder="volume-xx-kg*" name="volume_x" disabled> -->
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm" placeholder="Volume" aria-label="Volume" aria-describedby="basic-addon2" id="volumeX" placeholder="weight-kg*" name="volume_x" disabled>
                                    <span class="input-group-text input-group-sm" id="basic-addon2">Kg</span>
                                </div>
                                <input type="text" class="form-control form-control-sm" id="method" placeholder="volume-xx-kg*" name="method" hidden>
                            </div>

                            <div class="col-md-3 d-flex align-items-end justify-content-center">
                                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalVolume">Volume</button>
                            </div>
                        </div>
                        <label for="pic" class="form-label mt-3">Regulated Agent<span class="text-danger">*</span></label>
                        <select class="default-tom-select" data-placeholder="Select Regulated Agent" name='ra' id="ra" required data-constname="selectRA">
                            <option></option>
                            <?php
                            if (@$allra) {
                                while ($c_ra = $allra->fetch_object()) {
                            ?>
                                    <option value="<?= $c_ra->ra_id ?>"><?= $c_ra->ra_name; ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                        <div class="mt-5 d-flex justify-content-end">
                            <button type="submit" class="s_print btn" id="s_print" name="s_print">Save & Print</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
                <button type="button" class="btn btn-sm btn-info mb-2" id="addNew" onclick="addNewRow($('#qty').val())">Add</button>
                <input type="text" id="value">
                <table class="">
                    <thead class="tex-center">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">PCS</th>
                            <th class="text-center">Length</th>
                            <th class="text-center">Width</th>
                            <th class="text-center">Height</th>
                            <th class="text-center">Value</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="calculate" onclick="calculate()">Calculate & Save</button>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="modalAddAgent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog thedialog">
        <div class="modal-content themodal">
            <div class="modal-header theheader">
                <h5 class="modal-title" id="exampleModalLabel">Add new agent name</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-4 mb-3">
                    <div class="col-4">
                        <label for="new_agent"><b>New agent name</b></label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="new_agent" onkeyup="capital_font($(this))">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="agent_npwp"><b>NPWP</b></label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control npwp-input" id="agent_npwp">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="agent_address"><b>Address</b></label>
                    </div>
                    <div class="col-6">
                        <textarea name="agent_address" id="agent_address" class="form-control form-control-sm" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ungu btn" id="save_agent" onclick="add_and_save_agent($(this))">Save</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalAddRA" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog thedialog">
        <div class="modal-content themodal">
            <div class="modal-header theheader">
                <h5 class="modal-title" id="exampleModalLabel">Add new regulated agent</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 d-flex">
                        <div>

                        </div>
                    </div>
                </div>
                <div class="row pt-4">
                    <div class="col-4">
                        <label for="new_ra"><b>Regulated Agent Name</b></label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="new_ra" onkeyup="capital_font($(this))">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ungu btn" id="save_ra" onclick="add_and_save_ra($('#new_ra').val())">Save</button>
            </div>
        </div>
    </div>
</div>





<script src="assets/jquery/jquery-3.6.0.js" crossorigin="anonymous"></script>
<script src="assets/select2/select2.min.js"></script>
<script src="assets/tom-select/tom-select.complete.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $("#spinnerAwb").hide();
        $(".select2").select2({
            placeholder: $(this).data('placeholder'),
        });
        $(".select2-selection").css({
            'height': '31px',
            'border-color': '#4a0074',
        });
        initSmallTomSelect();
        initDefaultTomSelect();
    });
    var automode = "off";

    $('.npwp-input').on('input', function() {
        this.value = this.value.replace(/[^0-9.,-]/g, '');

        var numOnly = this.value.replace(/[^0-9]/g, '').length;
        if (numOnly == 15 || numOnly == 16) {
            $("#save_agent").prop('disabled', false);
        } else {
            $("#save_agent").prop('disabled', true);
        }
    });

    function switchButton() {
        if (automode === "off") {
            automode = 'on';
            $("#capsule").removeClass("capsule-off");
            $("#capsule").addClass("capsule-on");
            $("#status").removeClass("status-off");
            $("#status").addClass("status-on");
            $("#ball").removeClass("ball-off");
            $("#ball").addClass("ball-on");
            $("#status").text("On");
            $("#s_print").addClass("disabled");
            $(".doption").hide();
        } else {
            automode = 'off';
            $("#capsule").removeClass("capsule-on");
            $("#capsule").addClass("capsule-off");
            $("#status").removeClass("status-on");
            $("#status").addClass("status-off");
            $("#ball").removeClass("ball-on");
            $("#ball").addClass("ball-off");
            $("#status").text("Off");
            $("#s_print").removeClass("disabled");
            $(".doption").show();

            $("#comodity").val('');
            $("#comodity").val('');
            $("#shipper").val('');
            $("#qty").val('');
            $("#weight").val('');
            $("#volume").val('');
            $("#volumeX").val('');
        }
        $("#modeStatus").val(automode);
        // if(automode==='off'){

        // }if(automode==="on"){

        // };
    };

    $("#awb").keypress(function(e) {
        if (e.which == 13) {
            if ($("#awalan").val() == '273' || $("#awalan").val() == '181') {
                $("#noflight").html('');

                $("#spinnerAwb").show();
                var data = $("#awb").val(),
                    awalan = $("#awalan").val(),
                    sub1 = data.substring(0, 4),
                    sub2 = data.substring(4, 8),
                    target = awalan + "-" + sub1 + "-" + sub2,
                    urldata = "api/rimbunapi_awb.php?data=" + target;
                console.log(urldata);
                $(".nrow").hide();
                $.ajax({
                    url: urldata,
                    type: "GET",
                    data: {},
                    success: function(result) {
                        const hasil = JSON.parse(result);
                        const rstatus = hasil['success'];
                        $(".select2").select2({
                            placeholder: $(this).data('placeholder'),
                        });
                        if (rstatus === true) {
                            var length = parseInt(hasil['data'][0]["length"]),
                                width = parseInt(hasil['data'][0]["width"]),
                                height = parseInt(hasil['data'][0]["height"]),
                                volumex = length * width * height * hasil['data'][0]["qty"] / 6000;
                            volumex = Math.ceil(volumex);
                            // console.log(hasil['data'][0]["cargo_type"]);
                            $("#comodity").val(hasil['data'][0]["goods_info"]);
                            $("#shipper").val(hasil['data'][0]["shipper_name"]);
                            $("#qty").val(hasil['data'][0]["qty"]);
                            $("#weight").val(hasil['data'][0]["total_gw"]);
                            $("#volume").val(volumex);
                            $("#volumeX").val(volumex);
                            $("#method").val('api');
                            $("#toastArea").prepend('<div class="mb-1" style="width : 100%">' +
                                '<div class="toast-header" style="background-color: rgba(116, 217, 0, 0.5);">' +
                                '<!-- <img src="..." class="rounded me-2" alt="..."> -->' +
                                '<strong class="me-auto">Success !</strong>' +
                                '<button type="button" class="btn-close close-toast" aria-label="Close"></button>' +
                                '</div>' +
                                '<div class="toast-body" style="background-color: rgba(196, 196, 196, 0.5);">' +
                                'Data found !' +
                                '</div>' +
                                '</div>');
                            $("#spinnerAwb").hide();
                            $(".close-toast").click(function() {
                                var closeBtn = $(this).parent().parent();
                                closeBtn.remove();
                            });
                            setTimeout(function() {
                                $(".close-toast").click();
                            }, 5000);

                            // $("#noflight").val(hasil['data'][0]["cargo_type"]);
                            // $("#agent").val(hasil['data'][0]["cargo_type"]);
                            var newurl = "api/apiflight.php?data=" +
                                $.ajax({
                                    url: "api/apiflight.php?data=" + hasil['data'][0]["iata_dest"],
                                    type: "GET",
                                    data: {},
                                    success: function(result2) {
                                        const hasil2 = result2;
                                        console.log(volumex);
                                        $(".apisel").remove();
                                        $(".doption").hide();
                                        $("#noflight").prepend(result2);
                                        // $("#noflight option: ots-bpn").val
                                    }
                                });
                            $("#s_print").removeClass("disabled");
                        } else {
                            // Returns a Bootstrap toast instance
                            console.log(hasil);
                            console.log(hasil['success']);
                            console.log("gagal");
                            $(".apisel").remove();
                            $(".doption").show();
                            $("#comodity").val('');
                            $("#shipper").val('');
                            $("#qty").val('');
                            $("#weight").val('');
                            $("#volume").val('');
                            $("#volumeX").val('');
                            $("#method").val('manual');
                            $("#toastArea").prepend('<div class="mb-1">' +
                                '<div class="toast-header" style="background-color: rgba(186, 119, 119, 0.5);">' +
                                '<!-- <img src="..." class="rounded me-2" alt="..."> -->' +
                                '<strong class="me-auto">Failed !</strong>' +
                                '<button type="button" class="btn-close close-toast" aria-label="Close"></button>' +
                                '</div>' +
                                '<div class="toast-body" style="background-color: rgba(196, 196, 196, 0.5);">' +
                                'Data not found !' +
                                '</div>' +
                                '</div>');
                            $("#spinnerAwb").hide();
                            $(".close-toast").click(function() {
                                var closeBtn = $(this).parent().parent();
                                closeBtn.remove();
                            });
                            setTimeout(function() {
                                $(".close-toast").click();
                            }, 5000);
                        }
                    }
                })
            }
        }
    })


    $('#mainForm').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    function getFlight(element) {
        let value = element.val();
        $.ajax({
            url: 'ajax/get_flight.php?smu_code=' + value,
            type: 'get',
            data: {},
            beforeSend: function() {
                $("#noflight").html('');
                selectFlight.clear();
                selectFlight.clearOptions();
            },
            success: async function(data) {
                let result = JSON.parse(data);
                console.log(result);
                $("#noflight").append('<option></option>');
                let array = []
                for (var i = 0; i < result.length; i++) {
                    let id = result[i][2];
                    let text = result[i][2];
                    // $("#noflight").append('<option value="' + id + '">' + text + '</option>');
                    array.push({
                        value: id,
                        text: text
                    });
                }
                selectFlight.addOption(array)
                // $("#noflight").select2({
                //     placeholder: $(this).data('placeholder')
                // });
                $("#noflight").trigger('change');
            }
        });
    }

    function addNewRow(qty) {
        let quantity = parseInt(qty);
        let readyRow = $("#tbody").children().length;
        let vol_qty = 0;
        for (var i = readyRow - 1; i >= 0; i--) {
            vol_qty = vol_qty + parseInt($("#tbody tr:eq(" + i + ") td:eq(1) input").val());
        };
        if (quantity > 0 && vol_qty < quantity) {
            $("#tbody").append("<tr>" +
                "<td class='px-2'><button type='button' class='btn btn-sm btn-danger' onclick='delete_row($(this))'><i class='fas fa-trash-alt'></i></button></td>" +
                "<td><input class='form-control' type='number' id='p' style='background-color: white' onkeyup='input_validasi($(this)); hitung_dimensi($(this))'></td>" +
                "<td><input class='form-control' type='number' id='l' style='background-color: white' onkeyup='hitung_dimensi($(this))'></td>" +
                "<td><input class='form-control' type='number' id='w' style='background-color: white' onkeyup='hitung_dimensi($(this))'></td>" +
                "<td><input class='form-control' type='number' id='h' style='background-color: white' onkeyup='hitung_dimensi($(this))'></td>" +
                "<td><input class='form-control' type='number' id='v' readonly></td>" +
                "</tr>");
        } else {
            alert('Total pcs sudah mencukupi');
        }
    }

    function input_validasi(element) {
        let nilai = element.val();

        let readyRow = $("#tbody").children().length;
        let validasi_total = 0;
        for (var i = readyRow - 1; i >= 0; i--) {
            validasi_total = validasi_total + parseInt($("#tbody tr:eq(" + i + ") td:eq(1) input").val());
        };
        if (validasi_total > parseInt($("#qty").val())) {
            element.val('').trigger('change');
            alert('Nilai input sudah melebihi quantity');
        } else {
            element.val(nilai);
        }
    }

    function hitung_dimensi(element) {
        let parent = element.parent().parent();

        let pcs = parent.find('td:eq(1) input').val();
        let length = parent.find('td:eq(2) input').val();
        let width = parent.find('td:eq(3) input').val();
        let height = parent.find('td:eq(4) input').val();

        if (pcs != '' && length != '' && width != '' && height != '') {
            let volume = ((length * width * height) / 6000) * pcs;

            parent.find('td:eq(5) input').val(parseFloat(volume).toFixed(2));
        } else {
            parent.find('td:eq(5) input').val('');

            $("#volume").val('');
            $("#volumeX").val('');
            $("#value").val('');
        }
    }

    function calculate() {
        let readyRow = $("#tbody").children().length;
        let grand_total = 0;
        for (var i = readyRow - 1; i >= 0; i--) {
            let result = parseFloat($("#tbody tr:eq(" + i + ") td:eq(5) input").val());
            grand_total = grand_total + result;
        };

        let grand_result = Math.round(grand_total);

        $("#value").val(grand_result);

        $("#volume").val(grand_result);
        $("#volumeX").val(grand_result);
        $("#modalVolume").modal('hide');
    }

    function delete_row(element) {

        let parent = element.parent().parent();
        parent.remove();

        $("#volume").val('');
        $("#volumeX").val('');
        $("#value").val('');
    }

    function capital_font(element) {
        let value = element.val();
        let new_value = value.toUpperCase();
        element.val(new_value);
    }

    function add_and_save_agent(button) {
        let agent_name = $("#new_agent").val();
        let npwp = $("#agent_npwp").val();
        let address = $("#agent_address").val();
        if (agent_name !== '' && npwp !== '' && address !== '') {
            if (confirm('Are you sure to add the agent')) {
                $.ajax({
                    url: 'ajax/agent_ajax.php',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'add_agent': true,
                        'agent_name': agent_name,
                        'agent_npwp': npwp,
                        'agent_address': address
                    }
                }).then(result => {
                    if (result.status == 200) {
                        // let new_options = new Option(agent_name, agent_name, false, false);
                        // $("#agent").append(new_options).trigger("change");
                        selectAgent.addOption([{
                            value: agent_name,
                            text: agent_name
                        }]);
                        selectAgent.setValue(agent_name);
                        alert("1 agent added !");
                    } else {
                        alert("Add agent error !");
                    }
                    $("#new_agent").val('');
                    $("#modalAddAgent").modal('hide');

                }).catch(error => {
                    if (error.responseJSON) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: `Agent failed to add (${error.responseJSON.message})`,
                        })
                    }
                })
            }
        } else {
            Swal.fire('Failed', 'All field must be filled', 'error');
        }
    }

    function add_and_save_ra(ra_name) {
        if (ra_name !== '') {
            if (confirm('Are you sure to add the regulated agent')) {
                $.ajax({
                    url: 'ajax/ra_name.php?ra_name=' + ra_name,
                    type: 'post',
                    data: {},
                    success: function(data) {
                        let result = JSON.parse(data);
                        if (result['status'] == 200) {
                            // let new_options = new Option(result.ra_name, result.ra_id, false, false);
                            // $("#ra").append(new_options).trigger("change");
                            selectRA.addOption([{
                                value: result.ra_id,
                                text: result.ra_name
                            }])
                            selectRA.setValue(result.ra_id);
                            alert("1 regulated agent added !");
                        } else {
                            alert("Add regulated agent error !");
                        }
                        $("#new_ra").val('');
                        $("#modalAddRA").modal('hide');
                    }
                });
            }
        }
    }

    const initSmallTomSelect = () => {
        $(document).find('.small-tom-select').each(function() {
            let element = $(this);
            new TomSelect(element[0], {
                onInitialize: function() {
                    this.control.classList.add('position-relative')
                    this.control_input.classList.add('position-absolute', 'start-50', 'top-50', 'translate-middle', 'form-control-sm', 'bg-white', 'px-1rem');
                    this.control_input.style.height = '100%';
                    this.control_input.style.width = '100%';
                    this.control_input.style.backgroundColor = 'white';
                    this.wrapper.classList.add('rounded');
                    this.wrapper.style.border = 'solid 1px #4a0078';
                },
                onChange: function(value) {
                    $(this.control_input).val(value);
                },
                onItemAdd: function(value) {
                    $(this.control_input).val(value);
                },
                onDropdownOpen: function() {
                    let value = $(this.control).find('.item');
                    if (value[0]) {
                        dvalue = $(value[0]).data('value');
                        $(this.control_input).val(dvalue);
                    }
                }

            });
        })
    }

    const initDefaultTomSelect = () => {
        $(document).find('.default-tom-select').each(function() {
            let constName = $(this).data('constname');
            let element = $(this);
            globalThis[constName] = new TomSelect(element[0], {
                onInitialize: function() {
                    this.control_input.classList.add('non-border')
                    $(this.control_input).prop('id', constName + '_input');
                    this.wrapper.classList.add('rounded');
                    this.wrapper.style.border = 'solid 1px #4a0078';
                }
            });
        })
    }
</script>