<?php
include('models/m_agent.php');
$agentClass = new Agent($connection);

$proccess = "none";
$proccess2 = "none";
$message = '';

if (isset($_POST['add_agent'])) {
    if (!empty($_POST['agent_name']) && !empty($_POST['agent_npwp']) && !empty($_POST['agent_address'])) {
        $data = [
            'agent_name' => strtoupper($_POST['agent_name']),
            'agent_npwp' => $_POST['agent_npwp'],
            'agent_address' => $_POST['agent_address'],
        ];
        $insert = $agentClass->insertAgent($data);
        if ($insert == 'inserted') {
            $_SESSION['proses'] = "berhasil";
        } else {
            $proccess2 = "block";
            $message = @$insert->getMessage();
        }
    } else {
        $proccess2 = "block";
        $message = 'Please fill in all fields';
    }
}

if (isset($_POST['update_agent'])) {
    if (!empty($_POST['uagent_name']) && !empty($_POST['uagent_npwp']) && !empty($_POST['uagent_address']) && !empty($_POST['uagent_id'])) {
        $data = [
            'agent_name' => strtoupper($_POST['uagent_name']),
            'agent_npwp' => $_POST['uagent_npwp'],
            'agent_address' => $_POST['uagent_address'],
            'agent_status' => intval($_POST['status']),
        ];
        $agent_id = $_POST['uagent_id'];
        $insert = $agentClass->updateAgent($agent_id, $data);
        if ($insert == 'updated') {
            $_SESSION['proses'] = "berhasil";
        } else {
            $proccess2 = "block";
            $message = @$insert->getMessage();
        }
    } else {
        $proccess2 = "block";
        $message = 'Please fill in all fields';
    }
}

function old($post)
{
    global $proccess2;
    if ($proccess2 == "block") {
        return @$_POST[$post] ?? '';
    } else {
        return '';
    }
}

if (isset($_SESSION['proses'])) {
    if ($_SESSION['proses'] == "berhasil") {
        $proccess = "block";
        $message = 'The data you have entered has been successfully entered';
        unset($_SESSION['proses']);
    } else if ($_SESSION['proses'] == "updated") {
        $proccess = "block";
        $message = 'The agent data has been updated successfully';
        unset($_SESSION['proses']);
    }
}
?>

<style>
    #tableFlight_wrapper .row::nth-child(1) {
        width: fit-content;
        overflow: auto;
    }
</style>

<div class="position-relative alert-active">
    <div class="d-alert position-absolute start-50 translate-middle" style="display: <?php echo $proccess; ?>" data-aos="fade-right" data-aos-duration="2000">
        <div class="alert alert-dismissible  alert-success fade show" role="alert">
            <strong>Success!</strong> <?= @$message; ?>.
            <button type="button" class="alert-close btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
<div class="position-relative alert-active">
    <div class="d-alert position-absolute start-50 translate-middle" style="display: <?php echo $proccess2; ?>" data-aos="fade-right" data-aos-duration="2000">
        <div class="alert alert-dismissible  alert-danger fade show" role="alert">
            <strong>Failed!</strong> <?= @$message; ?>.
            <button type="button" class="alert-close btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
<div class="kontener2 px-5 py-4">
    <nav style="--bs-breadcrumb-divider: '》';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item ps-4" aria-current="page"><i class="fa-solid fa-database"></i> Acceptance</h3>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Add flight</h3>
            </li>
        </ol>
    </nav>
    <div class="content p-4" style="font-family: roboto;">
        <h6><i class="fa-solid fa-circle-plus"></i> Add Agent</h6>
        <div class="row g-0 p-0 m-0">
            <div class="description col-sm-2 px-3">
                <h5>Agent</h5>
                <p>Add and manage agent data</p>
            </div>
            <div class="col-md-3 px-3">
                <form action="" method="post">
                    <div class="form-group mb-3">
                        <label for="agent_name">
                            Name
                        </label>
                        <input type="text" name="agent_name" id="agent_name" class="form-control form-control-sm" value="<?= @old('agent_name') ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="agent_npwp">
                            NPWP
                        </label>
                        <input type="text" name="agent_npwp" id="agent_npwp" class="form-control form-control-sm npwp-input" value="<?= @old('agent_npwp') ?>">
                        <div class="text-muted" style="font-size: 0.8rem;">The NPWP number must contain between 15 and 16 digits.</div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="agent_address">
                            Address
                        </label>
                        <textarea name="agent_address" id="agent_address" class="form-control form-control-sm" rows="2"><?= @old('agent_address') ?></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-dark btn-sm mt-3" name="add_agent" id="add_agent">Submit</button>
                    </div>
                </form>
            </div>
            <div class="col-sm-7 ">
                <div class="tableflightnumber" style="max-height: 700px !important;">
                    <?php
                    $result = $agentClass->getAllAgent();
                    ?>
                    <table class="table table-hover text-nowrap" id="tableFlight">
                        <thead>
                            <tr>
                                <th style="position: sticky;top: 0;">#</th>
                                <th style="position: sticky;top: 0;">Agent Name</th>
                                <th style="position: sticky;top: 0;">NPWP</th>
                                <th style="position: sticky;top: 0;">Address</th>
                                <th style="position: sticky;top: 0;">Status</th>
                                <th style="position: sticky;top: 0;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nomor = 0;
                            while ($agent = $result->fetch_object()) :
                                $nomor++; ?>
                                <tr>
                                    <td><?php echo $nomor; ?></td>
                                    <td><?php echo $agent->agent_name; ?></td>
                                    <td><?php echo $agent->agent_npwp; ?></td>
                                    <td>
                                        <?php
                                        $limitedText = substr($agent->agent_address, 0, 25) . (strlen($agent->agent_address) > 25 ? '...' : '');
                                        echo '<a href="javascript:void(0);" class="text-dark" style="text-decoration: none;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="' . $agent->agent_address . '">' . $limitedText . '<a>';
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if ($agent->agent_status == '1') {
                                            echo '<span class="badge bg-success">Active</span>';
                                        } else {
                                            echo '<span class="badge bg-danger">Inactive</span>';
                                        }

                                        ?>
                                    </td>
                                    <td>
                                        <a href="javascript:;" data-id="<?php echo $agent->agent_id; ?>" data-agent_name="<?= $agent->agent_name; ?>" data-agent_npwp="<?= $agent->agent_npwp; ?>" data-agent_address="<?= $agent->agent_address; ?>" data-bs-toggle="modal" data-bs-target="#modalEditAgent" data-status="<?= $agent->agent_status; ?>">
                                            <button type="button" class="btn btn-sm btn-primary"><i class="fa-solid fa-pen-to-square"></i></button>
                                        </a>
                                        <a href="javascript:;" class="btn btn-sm btn-danger" onclick="removeAgent($(this))" data-id="<?php echo $agent->agent_id; ?>">
                                            <i class="fa-solid fa-delete-left"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal edit flight-->
<div class="modal fade" id="modalEditAgent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  ">
        <div class="modal-content modal-md themodal">
            <div class="modal-header theheader">
                <h5 class="modal-title" id="exampleModalLabel">Update Agent Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                <div class="modal-body" style="height: auto !important;">
                    <input type="text" name="uagent_id" id="uagent_id" hidden>
                    <div class="form-group mb-3">
                        <label for="uagent_name">
                            Name
                        </label>
                        <input type="text" name="uagent_name" id="uagent_name" class="form-control form-control-sm" value="<?= @old('uagent_name') ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="uagent_npwp">
                            NPWP
                        </label>
                        <input type="text" name="uagent_npwp" id="uagent_npwp" class="form-control form-control-sm npwp-input-modal" value="<?= @old('uagent_npwp') ?>">
                        <div class="text-muted" style="font-size: 0.8rem;">The NPWP number must contain between 15 and 16 digits.</div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="uagent_address">
                            Address
                        </label>
                        <textarea name="uagent_address" id="uagent_address" class="form-control form-control-sm" rows="2"><?= @old('uagent_address') ?></textarea>
                    </div>
                    <div class="form-check d-flex align-items-center">
                        <input class="form-check-input me-3" type="radio" name="status" id="active" value="1">
                        <label class="form-check-label text-primary" for="active">
                            Active
                        </label>
                    </div>
                    <div class="form-check d-flex align-items-center">
                        <input class="form-check-input me-3" type="radio" name="status" id="inactive" value="0">
                        <label class="form-check-label text-danger" for="inactive">
                            Inactive
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-dark btn-sm" name="update_agent" id="update_agent">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="assets/jquery/jquery-3.6.0.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {})

    $('.npwp-input').on('input', function() {
        this.value = this.value.replace(/[^0-9.,-]/g, '');

        var numOnly = this.value.replace(/[^0-9]/g, '').length;
        if (numOnly == 15 || numOnly == 16) {
            $("#add_agent").prop('disabled', false);
        } else {
            $("#add_agent").prop('disabled', true);
        }
    });

    $('.npwp-input-modal').on('input', function() {
        this.value = this.value.replace(/[^0-9.,-]/g, '');

        var numOnly = this.value.replace(/[^0-9]/g, '').length;
        if (numOnly == 15 || numOnly == 16) {
            $("#update_agent").prop('disabled', false);
        } else {
            $("#update_agent").prop('disabled', true);
        }
    });

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    $("#modalEditAgent").on("show.bs.modal", function(event) {
        let button = $(event.relatedTarget);
        let id = button.data('id');
        let agent_name = button.data('agent_name');
        let agent_npwp = button.data('agent_npwp');
        let agent_address = button.data('agent_address');
        let status = button.data('status');
        $("#uagent_id").val(id);
        $("#uagent_name").val(agent_name);
        $("#uagent_npwp").val(agent_npwp);
        $("#uagent_address").val(agent_address);

        if (status == 1) {
            $("#inactive").prop('checked', false);
            $("#active").prop('checked', true);
        } else {
            $("#inactive").prop('checked', true);
            $("#active").prop('checked', false);
        }
    })

    const removeAgent = (element) => {
        let id = element.data('id');
        Swal.fire({
            title: 'Delete Agent',
            text: "Are you sure you want to delete this agent?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'ajax/agent_ajax.php',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        delete_agent: true,
                        id: id
                    }
                }).then(result => {
                    if (result.status == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Agent has been deleted',
                        }).then(() => {
                            window.location.reload();
                        })
                    }
                }).catch(error => {
                    if (error.responseJSON) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: `Agent failed to delete(${error.responseJSON.message})`,
                        })
                    }
                })
            }
        })
    }
</script>