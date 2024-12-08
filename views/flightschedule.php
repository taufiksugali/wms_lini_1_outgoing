<?php
include('models/m_smu_code.php');
include('models/m_airline.php');

$airline = new Airline($connection);
$d_airline = $airline->call_all_airline();

if (@$_POST['date'] && @$_POST['airline']) {
    $sFlight = $airline->getScheduleFlight($_POST['airline'], $_POST['date']);
    $airlineData = $airline->getAirlineById($_POST['airline']);
}

?>
<link href="assets/datetimepicker-master/jquery.datetimepicker.css" rel="stylesheet" />
<div class="kontener2 px-5 py-4">
    <nav style="--bs-breadcrumb-divider: '》';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item ps-4" aria-current="page"><i class="fa-solid fa-database"></i> Acceptance</h3>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Flight Schedule</h3>
            </li>
        </ol>
    </nav>
    <div class="content p-4" style="font-family: roboto;">
        <h6><i class="fa-solid fa-circle-plus"></i> Add flight</h6>

        <div class="row g-0 p-0 m-0">
            <div class="description col-sm-2 px-3">
                <h5>Flight Schedule</h5>
                <p>Add flight schedule daily to get the time of departure on BTB</p>
            </div>
            <div class="col-sm-10 px-3">
                <form class="sesreport" action="" method="post">
                    <div>
                        <div class="row">
                            <div class="col-sm-3 mb-2">
                                <label for="">Select Airline:</label>
                                <select class="select2 form-select form-select-sm" name="airline" id="airline" data-placeholder="Select Airline">
                                    <option></option>
                                    <?php
                                    if (@$d_airline) {
                                        while ($airline = $d_airline->fetch_object()) {
                                    ?>
                                            <option value="<?= $airline->airline_id ?>" <?= @$_POST['airline'] && $_POST['airline'] == $airline->airline_id ? 'selected' : '' ?>><?= $airline->airline_name ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 mb-2">
                                <label class="" for="">Date : </label>
                                <input type="text" class="form-control form-control-sm mb-3 datetimepicker" name="date" value="<?= @$_POST['date'] ?? date('Y-m-d') ?>">
                                <button type="submit" class="btn btn-sm " name="search">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
                <?php
                if (@$airlineData) {
                    echo $airlineData->airline_name;
                ?>
                    <div class="mt-4">
                        <table class="table table-striped table-sm" id="schedule-table">
                            <thead class="text-center bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Flight</th>
                                    <th>Date</th>
                                    <th style="width: 25%;">ETD</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (@$sFlight) {
                                    $nomor = 0;
                                    $date = $_POST['date'];
                                    while ($flight = $sFlight->fetch_object()) {
                                        $nomor++;
                                ?>
                                        <tr>
                                            <td class="text-center"><?= $nomor; ?></td>
                                            <td class="text-start"><?= $flight->flight_no; ?></td>
                                            <td class="text-center"><?= $flight->schedule_date ? date('d-m-Y', strtotime($flight->schedule_date)) : date('d-m-Y', strtotime($date)); ?></td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <?php
                                                    if (@$flight->schedule_time) {
                                                    ?>
                                                        <div class="input-group input-group-sm" style="max-width: 300px;">
                                                            <input type="text" class="form-control form-control-sm datetime " readonly style="background-color: transparent;" value="<?= date('Y-m-d H:i', strtotime($flight->schedule_time)); ?>">
                                                            <div class="input-group-append">
                                                                <button type="button" class="ms-1 btn btn-sm btn-warning rounded-0 rounded-end" onclick="updateSchedule($(this))" data-flight_id="<?= $flight->flight_id; ?>" data-date="<?= $flight->schedule_date; ?>" data-schedule_id="<?= $flight->schedule_id ?>">Update</button>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <div class="input-group input-group-sm" style="max-width: 300px;">
                                                            <input type="text" class="form-control form-control-sm datetime " readonly style="background-color: transparent;">
                                                            <div class="input-group-append">
                                                                <button type="button" class="ms-1 btn btn-sm btn-primary rounded-0 rounded-end" onclick="saveSchedule($(this))" data-flight_id="<?= $flight->flight_id; ?>" data-date="<?= $date; ?>">Save</button>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>



<!-- Modal edit flight-->


<!-- <script src="assets/jquery/jquery-3.6.0.js" crossorigin="anonymous"></script> -->
<script src="assets/jquery/jquery-3.6.0.js" crossorigin="anonymous"></script>
<script src="assets/select2/select2.min.js"></script>
<script src="assets/dataTables/datatables.js"></script>
<script src="assets/datetimepicker-master/jquery.datetimepicker.js"></script>
<script>
    var cdate = '<?= @$_POST['date'] ?? date('Y-m-d') ?>';
    var limitDate = '<?= @$_POST['date'] ?? date('Y-m-d') ?>';
    var listTable;
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: $(this).data('placeholder'),
        });

        $(".datetimepicker").datetimepicker({
            format: 'Y-m-d',
            timepicker: false
        })
        limitDate = addDays(cdate, 2);

        listTable = $("#schedule-table").DataTable();
        datetimeInit();

        listTable.on('page.dt', function() {
            datetimeInit()
        });
        $('#schedule-table_filter input').on('mousedown', function() {
            datetimeInit()
        })


    })
    // var cdate = '2024-12-08'
    const datetimeInit = () => {
        $('.datetime').datetimepicker({
            step: 5,
            format: 'Y-m-d H:i',
            minDate: cdate,
            maxDate: limitDate,
        })
    }

    const saveSchedule = (e) => {
        let button = e;
        let flight_id = e.data('flight_id');
        let date = e.data('date');
        let time = e.parent().parent().find('input').val();
        if (time == '') {
            Swal.fire('Warning', 'Please input time', 'warning');
        } else {
            Swal.fire({
                title: 'Save Flight ETD?',
                showDenyButton: true,
                confirmButtonText: 'Save',
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'ajax/get_flight.php?action=save_schedule',
                        data: {
                            flight_id: flight_id,
                            date: date,
                            time: time
                        },
                        type: 'post',
                        dataType: 'json',
                    }).then(result => {
                        if (result.status == 200) {
                            button.removeClass('btn-primary');
                            button.addClass('btn-warning')
                            button.text('Update');
                            button.data('schedule_id', result.data.schedule_id);
                            button.prop('onclick', 'updateSchedule($(this))');
                            Swal.fire('Saved', 'Schedule has been saved', 'success');
                        }
                    }).catch(error => {
                        if (error.responseJSON.message) {
                            Swal.fire('Failed', error.responseJSON.message, 'error');
                        } else {
                            Swal.fire('Failed', 'Something went wrong', 'error');
                        }
                    })
                }
            })
        }
    }

    $('#schedule-table_filter input').on('input', function() {
        console.log('tester');
        datetimeInit()
    })

    const addDays = (date, days) => {
        const result = new Date(date);
        result.setDate(result.getDate() + days);
        return result.toISOString().split('T')[0];
    };

    const updateSchedule = (e) => {
        let button = e;
        let schedule_id = e.data('schedule_id');
        let date = e.data('date');
        let time = e.parent().parent().find('input').val();
        if (time == '') {
            Swal.fire('Warning', 'Please input time', 'warning');
        } else {
            Swal.fire({
                title: 'Update Flight ETD?',
                showDenyButton: true,
                confirmButtonText: 'Update',
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'ajax/get_flight.php?action=update_schedule',
                        data: {
                            date: date,
                            time: time,
                            schedule_id: schedule_id,
                        },
                        type: 'post',
                        dataType: 'json',
                    }).then(result => {
                        if (result.status == 200) {
                            Swal.fire('Updated', 'Schedule has been updated', 'success');
                        } else if (result.message) {
                            Swal.fire('Failed', result.message, 'error');
                        } else {
                            Swal.fire('Failed', 'Something went wrong', 'error');
                        }
                    }).catch(error => {
                        if (error.responseJSON.message) {
                            Swal.fire('Failed', error.responseJSON.message, 'error');
                        } else {
                            Swal.fire('Failed', 'Something went wrong', 'error');
                        }
                    })
                }
            })
        }
    }
</script>